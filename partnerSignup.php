<?php
    include_once 'userCheck.php';
    require_once 'db_connection.php';
    require_once 'usefulFunctions.php';


    // Avvia la sessione
    session_start();
    $errors = array();
    $warnings = array();
    // Verifica l'accesso
    if(isset($_SESSION["id"]) && isset($_SESSION["user_type"]))
    {
        if ($_SESSION["user_type"] === "P"){
            header("Location: home_partner.php");
        exit;
        }
        elseif ($_SESSION["user_type"] === "C"){
            $warnings[] = 'Logout effettuato dalla sessione utente';
            session_destroy();
            session_start();
        }
    }

    $required_fields = ['activity_type', 'city', 'l_name', 'value', 'name','surname', 'email', 'password'];


    if (arePostFieldsSet($required_fields)){
        $warnings = array();
        $errors = array();
        //Connessione al db
        $database = new DbConnection();
        $conn = $database->getConnection();

        $user = new User($_POST['email'], $_POST['password']);

        if ($user->validateEmail() && $user->validatePassword()){
        
            $name = mysqli_real_escape_string($conn,  $_POST['name']);
            $surname = mysqli_real_escape_string($conn,  $_POST['surname']);
            $email = mysqli_real_escape_string($conn,  $_POST['email']);
            $password = mysqli_real_escape_string($conn,  $_POST['password']);
            $activity_type = mysqli_real_escape_string($conn,  $_POST['activity_type']);
            $city = mysqli_real_escape_string($conn,  $_POST['city']);
            $l_name = mysqli_real_escape_string($conn,  $_POST['l_name']);
            $currency = mysqli_real_escape_string($conn,  $_POST['value']);
            $user_type = "P";

            $query = "SELECT * FROM users WHERE email = '".$email."'";
            $res = mysqli_query( $conn, $query) or die("Error:".mysqli_error( $conn));
            if (mysqli_num_rows($res) > 0) {
                $errors[] = "Utente già registrato, accedere dalla pagina di login";
            }
            else {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $query = "call addPartner('".$name."', '".$surname."', '".$email."', '".$hashedPassword."', '".$currency."', '".$user_type."','".$l_name."', '".$city."', '".$activity_type."', @partner_id)";
                $res = mysqli_query( $conn, $query) or die("Error: ".mysqli_error( $conn));
                if($res){
                    $query = "SELECT @partner_id as partner_id";
                    $res = mysqli_query( $conn, $query) or die("Error: ".mysqli_error( $conn));
                    $id = mysqli_fetch_assoc($res)["partner_id"];
                }
                $_SESSION["id"] = $id;
                $_SESSION["user_type"] = $user_type;
                $_SESSION['currency'] = $currency;
                header("Location: home_partner.php");
                exit;

            }
        }else {
            $errors[] = "Formato email o password non valido";
            
        }
    }

?>


<html>
    <head>
        <meta charset="UTF-8">
        <title>Partner Signup|Partner-GetYourGuide</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="partnerSignup.css" />
        <script src="partnerSignup.js" defer></script>
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    </head>

    <body>
        <?php 
        
            include 's_header.php';


            if(!empty($errors)){
                foreach($errors as $error){
                    echo '<div class="errore_server">
                            <h5>Errore:</h5>
                            <span class="text_error">'.$error.'</span>
                        </div>'; 
                }
            }
            if(!empty($warnings)){
                foreach($warnings as $warning){
                    echo '<div class="warning_server">
                            <h5>Attenzione:</h5>
                            <span class="text_error">'.$warning.'</span>
                        </div>'; 
                }
            }

        ?>
        <section id="main_section">
            <div class="container_ex">
                <div class="container_in">
                    <h1 class="input_title">Unisciti a noi come partner</h1>
                    <form action="" method="post" name="signUp_partner">
                        <div class="input_container">
                            <div class="input_des">Come gestisci la tua azienda?</div>
                            <div>
                                <input type="radio" class="radio_option" name="activity_type" value="A">Azienda registrata
                            </div>
                            <div>
                            <input type="radio" class="radio_option" name="activity_type" value="I">Individuo registrato
                            </div>
                            <div class="error_message hidden" data-input="type_activity">Selezionare un'opzione</div>
                        </div>
                        <div class="input_container">
                            <label for="city_selection">In quali località offri le tue attività? </label>
                            <input type="text" class="L_input_text"  id="city_selection" name="city" placeholder="Catania, Italia" >
                            <div class="error_message hidden" data-input="city-selection">Compilare questo campo</div>
                            <span>Inserisci la località dove ha sede la tua azienda</span>
                        </div>
                        <div class="input_container">
                            <label for="legal_name">Inserisci il nome legale dell'attività</label>
                            <input type="text" class="L_input_text"  id="legal_name" name="l_name" placeholder="Guida Etna S.R.L." >
                            <div class="error_message hidden" data-input="legalName-input">Inserire nome legale</div>
                        </div>
                        <div class="input_container">
                            <label for="value_selection">Come preferisci ricevere i pagamenti?</label>
                            <select name="value" class="M_select" id="value_selection">
                                <option class="value_option" value="">Seleziona una valuta</option>
                            </select>
                            <div class="error_message hidden" data-input="value-selection">Selezionare una valuta</div>
                        </div>
                        <div class="L_in_container">
                            <div class="input_container">
                                <label for="name">Nome</label>
                                <input type="text" class="S_input_text"  id="name" name="name" placeholder="Mario" >
                                <div class="error_message hidden" data-input="name-input">Inserire nome</div>
                            </div>
                            <div class="input_container">
                                <label for="surname">Cognome</label>
                                <input type="text" class="S_input_text"  id="surname" name="surname" placeholder="Rossi" >
                                <div class="error_message hidden" data-input="surname-input">Inserire cognome</div>
                            </div>
                            
                        </div>
                        <div class="input_container">
                            <label for="city_selection">Email</label>
                            <input type="text" class="L_input_text"  id="email" name="email" placeholder="mariorossi@gmail.com">
                            <div class="error_message hidden" data-input="email">Inserire una mail nel formato: mariorossi@gmail.com</div>
                            <span>La utilizzerai per entrare nel tuo account</span>
                        </div>

                        <div class="input_container">
                            <label for="password">Password</label>
                            <input type="password" class="L_input_text"  id="password"  name="password">
                            <div class="error_message hidden" data-input="password-input">Inserire password rispettando i criteri stabiliti.</div>
                            <div class="pass_check" data-request="length">Tra 8 e 30 caratteri</div>
                            <div class="pass_check" data-request="num_special_char">Includere almeno un numero ed un carattere speciale (!@#$%^&*(),.?":{}|<>)</div>
                            <div class="pass_check" data-request="UpperLower_char">Lettere maiuscole e minuscole</div>
                            <div class="pass_check" data-request="no_tab">Nessuno spazio vuoto</div>
                        </div>

                        <div class="input_container">
                            <label for="terms">Termini e condizioni</label>
                            <div class="check_and_des">
                                <input type="checkbox" id="terms" name="terms" value="accepted">
                                <span class="checkb_des">
                                    Ho letto e accetto i Termini e Condizioni del Fornitore e l'Informativa sulla Privacy.
                                </span>
                            </div>
                            <div class="error_message hidden" data-input="term">Accettare per proseguire</div>
                        </div>
                        <div class="end_button">
                            <div class="back_button t_button">Indietro</div>
                            <input type="submit" name="invio" value="Crea un account">
                        </div>
                    </form>
                </div>
            </div>
            <div>
                Hai già un account? <a href="login.php" class="blue_link">Log in</a>
            </div>


        </section>


        <?php include 'partner_footer.php'; ?>
    </body>

</html>