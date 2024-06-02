<?php
    require_once 'db_connection.php';
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
    $database = new DbConnection();
    $conn = $database->getConnection();

    $company_id = $_SESSION['id'];
    $query = "SELECT nome_legale, media_recensioni FROM company_statistics_reviews WHERE company_id = '".$company_id."'";
    $res = mysqli_query($conn, $query) or die("Error: ".mysqli_error($conn));
    while( $row = mysqli_fetch_assoc($res) ){
        $company_name = $row["nome_legale"];
        $company_avgReview = (is_null($row["media_recensioni"])?0:round($row["media_recensioni"],2));
    }
    mysqli_free_result( $res );

    $query = "SELECT count(a.id) as numero_attivita, avg(discount) as prezzo_medio FROM activitys a
                GROUP BY a.company_id
                HAVING a.company_id = '".$company_id."'";
    $res = mysqli_query($conn, $query) or die("Error: ".mysqli_error($conn));
    while( $row = mysqli_fetch_assoc($res) ){
        $company_numActivities = $row["numero_attivita"];
        $company_avgPrice = (is_null($row["prezzo_medio"])?0:round($row["prezzo_medio"],2));
    }

    $query = "SELECT currency_symbol FROM users_currency_symbol WHERE user_id = '".$company_id."'";
    $res = mysqli_query($conn, $query) or die("Error: ".mysqli_error($conn));
    $currency = mysqli_fetch_assoc($res)["currency_symbol"];



    if(isset($_GET['discount']) && isset($_GET['activity_id'])){
        $errors = array();
        $new_price = mysqli_escape_string($conn, $_GET['discount']);
        $activity_id = mysqli_escape_string($conn, $_GET['activity_id']);
        $query = "SELECT price FROM activitys WHERE id = '".$activity_id."'";
        $res = mysqli_query($conn, $query) or die("Error: ".mysqli_error($conn));
        if (mysqli_fetch_assoc($res)['price'] > $new_price) {
            $query = "UPDATE activitys SET discount = '".$new_price."' WHERE id = '".$activity_id."'";
            $res = mysqli_query($conn, $query) or die("Error: ".mysqli_error($conn));
        }else{
            $errors[] = 'Non puoi inserire un prezzo più alto di quello di partenza';
        }

    }
?>


<html>
    <head>
        <meta charset="UTF-8">
        <title>Partner HomePage | Partner-GetYourGuide</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="home_partner.css" />
        <script src="home_partner.js" defer></script>
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    </head>

    <body>
        <header>
            <div id="partner_home_header">
                <a href="#"><div class="logo_img M_icon"></div></a>
                <div class="nav_partner_header">
                    <div class="nav_item blue_link" data-menu="addActivity" data-link="add_activity.php"><img class="S_icon" src="MEDIA/ICONS/add_circle.svg"><span class="item_des">Aggiungi Attività</span></div>
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
        <input type="hidden" name="currency" id="currency_input" value="<?php echo($currency)?>">
        <section id="sec_below_header">
            <div class="container_bheader">
                <h1 class="main_title"><?php print_r($company_name)?></h1>
                <div class="stats_container_big">
                    <div class="stats_container">
                        <div class="img_stats_container"><img class="stats_img" src="MEDIA/ICONS/stars.svg"></div>
                        <div class="stats_num"><?php print_r($company_avgReview)?></div>
                        <div class="stats_des">Media delle recensioni</div>
                    </div>
                    <div class="stats_container">
                        <div class="img_stats_container"><img class="stats_img" src="MEDIA/ICONS/sell.svg"></div>
                        <div class="stats_num"><?php print_r($company_avgPrice.$currency)?></div>
                        <div class="stats_des">Costo medio delle tue attività</div>
                    </div>
                    <div class="stats_container">
                        <div class="img_stats_container"><img class="stats_img" src="MEDIA/ICONS/numbers.svg"></div>
                        <div class="stats_num"><?php print_r($company_numActivities)?></div>
                        <div class="stats_des">Numero di attività inserite</div>
                    </div>
                </div>
            </div>
        </section>

    <section class="main_section">
        <h1>Le tue attività</h1>
        <div class="activities_container">
            <?php
                if(!empty($errors)){
                    foreach($errors as $error){
                        echo '<div class="errore_server">
                                <h5>Errore:</h5>
                                <span class="text_error">'.$error.'</span>
                            </div>'; 
                    }
                }
            ?>
            <!-- 
            <div class="activity_container hidden" data-id="">
                <div class="activity_des">
                    <img class="img_activity" src="">
                    <div class="orange_sep"></div>
                    <div class="activity_text_des">
                        <span class="activity_type">TOUR GUIDATO</span>
                        <span class="activity_title">Roma: fontana di Trevi</span>
                        <span class="activity_duration">3 ore</span>
                        <span class="activity_price"><span class="price old">31 EUR</span> <span class="price new">31 EUR</span> a persona</span>
                    </div>
                </div>
                <div class="forms_container">
                    <form name="discount_apply" action="">
                        <div class="input_container">
                            <label for="discount">Applica sconto</label>
                            <input type="number" class="discount number_input" name="discount" min="00.00" step="0.01" placeholder="10.00">
                            <span class="price_error hidden">Il prezzo deve essere inferiore a quello precedente</span>
                        </div>
                        <input type="submit" class="submit_discount" name="Applica" value="Applica">
                    </form>
                    <form  name="del_activity" action="">
                        <label for="delete_activity">Elimina attività</label>
                        <input type="submit" class="delete_activity" name="Elimina" value="Elimina">
                    </form>

                </div>
            </div>
             -->
            <div class="no_activity hidden">
                <div>Non hai ancora inserito nulla</div>
                <div class="add_button" data-link="add_activity.php">Aggiungi la tua prima attività</div>
            </div>



        </div>
        

    </section>











        <!-- 
        <div id="des_user_sec">
            <div id="container_in">
                <h1 class="main_title">Migliora il tuo profilo</h1>
                <form id="update_informazioni" action="" name="update_info" method="post">
                        <div class="input_container">
                            <label for="phone">Numero di telefono</label>
                            <input type="tel" class="L_input_text"  id="phone" name="phone" required>
                        </div>

                        <div class="input_container">
                            <label for="sede">Sede</label>
                            <input type="text" class="L_input_text"  id="sede" name="sede" required>
                        </div>

                        <div class="input_container">
                            <label for="website">Sito Web</label>
                            <input type="url" class="L_input_text"  id="website" name="website" required>
                        </div>

                        <input type="submit" name="invio" value="Salva informazioni">
                </form>
                
            </div>

        </div>
         -->
        <?php include 'partner_footer.php'; ?>
    </body>
</html>