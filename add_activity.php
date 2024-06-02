<?php

    // Avvia la sessione
    session_start();
    // Verifica se l'utente è loggato
    if(!isset($_SESSION['id']))
    {
        // Vai alla login
        header("Location: login.php");
        exit;
    }else{
        if(isset($_SESSION['user_type'])){
            if($_SESSION['user_type'] !== "P"){
                header("Location: index.php");
            }
        }
    }
    require_once("db_connection.php");
    $database = new DbConnection();
    $conn = $database->getConnection();

    $id_partner = $_SESSION["id"];
    $query = "SELECT currency_symbol FROM users_currency_symbol WHERE user_id = '".$id_partner."'";
    $res = mysqli_query($conn, $query) or die("Error: ".mysqli_error($conn));
    $currency = mysqli_fetch_assoc($res)["currency_symbol"];

    function arePostFieldsSet($required_fields) {
        foreach ($required_fields as $field) {
            if (!isset($_POST[$field])) {
                return false;
            }
        }
        return true;
    }

    function calcDurationInMinutes($min, $hours){
        return $min + $hours *60;
    }

    $required_fields = ['title', 'place', 'short_des', 'long_des','activity_type', 'd_ore','d_minuti', 'price', 'img_tour', 'activity_section'];
    if (arePostFieldsSet($required_fields)){
        $title = mysqli_escape_string($conn, $_POST['title']);
        $place = mysqli_escape_string($conn, $_POST['place']);
        $price = mysqli_escape_string($conn, $_POST['price']);
        $type = mysqli_escape_string($conn, $_POST['activity_type']);
        $short_des = mysqli_escape_string($conn, $_POST['short_des']);
        $long_des = mysqli_escape_string($conn, $_POST['long_des']);
        $d_ore = mysqli_escape_string($conn, $_POST['d_ore']);
        $d_minuti = mysqli_escape_string($conn, $_POST['d_minuti']);
        $img = mysqli_escape_string($conn, $_POST['img_tour']);
        $section = mysqli_escape_string($conn, $_POST['activity_section']);

        $errors = array();
        $info = array();
        for ($i = 1; $i < 6;  $i++){
            $name_info = "info". $i;

            if(isset($_POST["$name_info"])){
                $info[] = mysqli_escape_string($conn, $_POST["$name_info"]);
            }
        }
        
        $duration = calcDurationInMinutes($d_minuti, $d_ore);
        
        $query = "SELECT id FROM activitys WHERE company_id =  '".$id_partner."' and title = '".$title."'";
        $res = mysqli_query($conn, $query) or die("Error: ".mysqli_error($conn));
        if(mysqli_num_rows($res) > 0){
            $errors[] = 'L\'attività è già presente';
        }else{
            if (strlen($title)<80){
                $query = "call addActivity('".$id_partner."','".$type."','".$place."','".$price."','".$duration."', '".$title."','".$section."', @p_activity_id)";
                $res = mysqli_query($conn, $query) or die("Error: ".mysqli_error($conn));
                if ($res){
                    $query = "SELECT @p_activity_id as activity_id";
                    $res = mysqli_query($conn, $query) or die("Error: ".mysqli_error($conn));
                    $id_activity = mysqli_fetch_assoc($res)["activity_id"];
                    $database->freeResult( $res );
                }

                $query = "INSERT INTO activity_descriptions (activity_id, short_des, long_des, isErasable, isBus) VALUE ('".$id_activity."', '".$short_des."', '".$long_des."', '".(isset($_POST["free_canc"]) ? true:false)."', '".(isset($_POST["navetta"]) ? true:false)."')";
                $res = mysqli_query($conn, $query) or die("Error: ".mysqli_error($conn));

                $query = "INSERT INTO activity_images(img_path, id_activity) VALUE ('".$img."', '".$id_activity."')";
                $res = mysqli_query($conn, $query) or die("Error: ".mysqli_error($conn));

                
                foreach($info as $key => $value){
                    $query = "INSERT INTO activity_infos (activity_id, main_info) VALUE ('".$id_activity."', '".$value."') ";
                    $res = mysqli_query($conn, $query) or die("Error: ".mysqli_error($conn));
                }
            }else{
                $errors[] = 'Il titolo eccede la lunghezza massima di 80 caratteri';
            }
            if(empty($errors)){
                header("Location: home_partner.php");
                exit;
            }

        }



    }

?>


<html>
    <head>
        <meta charset="UTF-8">
        <title>Aggiungi attività | Partner-GetYourGuide</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="add_activity.css" />
        <script src="add_activity.js" defer></script>
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    </head>
    
    <body>
        <header>
            <div id="partner_home_header">
                <a href="home_partner.php"><div class="logo_img M_icon"></div></a>
                <div class="nav_partner_header">
                    <div class="nav_item blue_link" data-menu="account">
                        <img class="S_icon" src="MEDIA/ICONS/account_blu.svg">
                        <span class="item_des">Account</span>
                        <div class="dropdw_big_container hidden">
                        <a href="profile.php" class="dropdown_item_container" data-item="profile">
                            <img class="icon-menu" src="MEDIA/ICONS/profile_black.svg">
                            <span class="dropdown_item">Profilo</span>
                        </a>
                            <a href="logout.php" class="dropdown_item_container">
                                <img src="MEDIA/ICONS/logout.svg">
                                <span class="dropdown_item">Logout</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <?php
        if(isset($errors)){
            foreach($errors as $error){
                echo "<p class='errore'>";
                echo $error;
                echo "</p>";
            }
        }
            ?>
        <section id="main_section">
            <div class="container_ex">
                <div class="container_in">
                    <h1 class="input_title">Aggiungi un'attività</h1>
                    <form action="" method="post" name="add_activity">
                        <div class="input_container">
                        <label for="title">Inserisci il titolo del tour</label>
                            <input type="text" class="L_input_text"  id="title" maxlength="80" name="title" placeholder="Roma: Scopri la fontana di Trevi" >
                            <span>Si suggerisce di inserire parole chiave nel titolo.<br>Questo migliorerà la ricerca dell'attività da parte degli utenti.</span>
                            <div class="gen_error_message hidden" data-input="title-input">Compilare questo campo</div>
                        </div>
                        <div class="input_container">
                            <label for="place">Località del tour</label>
                            <input type="text" class="L_input_text"  id="place" name="place" placeholder="Roma" >
                            <span>Se il tour interessa più località, inserire quella di partenza.</span>
                            <div class="gen_error_message hidden" data-input="place-input">Compilare questo campo</div>
                        </div>
                        <div class="input_container">
                            <label for="short_des">Inserisci una breve descrizione</label>
                            <textarea name="short_des" id="short_des" class="big_text_input" rows="10" maxlength="200" placeholder="Immergiti nell'escursione della fontana di Trevi..."></textarea>
                            <span>Si suggerisce di inserire parole chiave nella breve descrizione dell'attività.<br>Questo migliorerà la ricerca dell'attività da parte degli utenti.</span>

                            <div class="gen_error_message hidden" data-input="short_des_input">Compilare questo campo</div>
                        </div>
                        <div class="input_container">
                            <div class="input_des">Informazioni sul tour</div>
                            <div class="check_container">
                                <input type="checkbox" name="free_canc" value="Yes">Cancellazione gratuita
                            </div>
                            <div class="check_container">
                            <input type="checkbox" name="navetta" value="Yes">Servizio navetta locale
                            </div>
                        </div>

                        <div class="input_container">
                            <div class="input_des">Durata del tour</div>
                            <div class="L_in_container">
                                <select name="d_ore" class="S_select" id="durata_ore">
                                    <option class="ore_option" value="">Ore</option>
                                </select>
                                <select name="d_minuti" class="S_select" id="durata_minuti">
                                    <option class="minuti_option" value="">Minuti</option>
                                </select>
                            </div>
                            <span>Indicare una durata approssimativa.</span>
                            <div class="gen_error_message hidden" data-input="duration_input">Selezionare entrambi i campi</div>
                        </div>

                        <div class="input_container">
                        <label for="price">Prezzo del tour</label>
                            <input type="number" class="XS_input_text"  id="price" name="price" min="0.00" step="0.01" placeholder="25.00" >
                            <span>In <span id="currency_pr"><?php echo $currency?></span>, specificata come valuta preferita in fase di registrazione.</span>
                            <div class="gen_error_message hidden" data-input="price-input">Compilare questo campo</div>
                        </div>

                        
                        <div class="input_container">
                            <label for="activity_type">Tipo di attività</label>
                            <select name="activity_type" class="M_select" id="activity_type">
                                    <option class="type" value="">Selezione un tipo di attività</option>
                            </select>
                            <div class="gen_error_message hidden" data-input="type_input">Selezionare un tipo di attività</div>
                        </div>

                        <div class="input_container">
                            <label for="activity_section">Sezione dell'attività</label>
                            <select name="activity_section" class="M_select" id="activity_section">
                                    <option class="type" value="">Selezione una sezione</option>
                            </select>
                            <div class="gen_error_message hidden" data-input="section_input">Selezionare una sezione</div>
                        </div>

                        <div class="input_container">
                            <div class="input_des">Informazioni in evidenza</div>
                            <div class="variable_input_cont" data-content="main_info">
                                <input type="text" class="L_input_text main_info" name="info1" maxlength="100" placeholder="Risparmia tempo grazie ai biglietti con ingresso prioritario.">
                            </div>
                            <div class="over_error_message hidden" data-input="main-info-input">Il limite massimo di informazioni di evidenza è pari a cinque.</div>
                            <div class="gen_error_message hidden" data-input="main-info-input"></div>

                            <div class="add_remove_buttons">
                                <div class="remove_button">
                                    Rimuovi
                                </div>
                                <div class="add_button">
                                    Aggiungi
                                </div>
                            </div>

                        </div>

                        <div class="input_container">
                            <label for="long_des">Inserisci la descrizione del tour</label>
                            <textarea name="long_des" class="big_text_input" id="long_des" rows="6" maxlength="2000" placeholder="Scopri il Colosseo, il Foro Romano e il colle del Palatino e l'affascinante storia della città eterna. Usufruisci dell'ingresso prioritario per visitare il primo e il secondo livello del Colosseo. Ammira l'Arco di Tito e il luogo in cui fu sepolto Giulio Cesare..."></textarea>
                            <div class="gen_error_message hidden" data-input="long_des_input">Compilare questo campo</div>
                        </div>

                        <div class="input_container">
                            <label for="img_tour">Selezionare un'immagine per il tour</label>
                            <select name="img_tour" class="M_select" id="img_tour">
                                    <option class="img_option" value="">Seleziona immagine</option>
                            </select>
                            <div class="gen_error_message hidden" data-input="img_input">Selezionare un'immagine</div>
                        </div>
                        <div class="end_button">
                            <input type="submit" name="invio" value="Crea il tour">
                        </div>
                    </form>
                </div>
            </div>


        </section>

        <?php include 'partner_footer.php'; ?>
    </body>
</html>

