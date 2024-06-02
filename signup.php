<?php
require_once 'userCheck.php';
require_once 'db_connection.php';

session_start();
    $errors = array();
    $warnings = array();
    if(isset($_SESSION["id"]) && isset($_SESSION["user_type"]))
    {
        if ($_SESSION["user_type"] === "P"){
            $warnings[] = "Logout effettuato dalla sessione partner";
            session_destroy();
            session_start();
        }
        elseif ($_SESSION["user_type"] === "C"){
            header("Location: index.php");
            exit;
        }
    }
    function arePostFieldsSet($required_fields) {
        foreach ($required_fields as $field) {
            if (!isset($_POST[$field])) {
                return false;
            }
        }
        return true;
    }

    $required_fields = ['name','surname', 'email', 'currency','password'];

    if(arePostFieldsSet($required_fields)) {
        $warnings = array();
        $errors = array();
        $database = new DbConnection();
        $conn = $database->getConnection();

        $user = new User($_POST['email'], $_POST['password']);
        if($user->validateEmail() && $user->validatePassword()) {
            $name = mysqli_escape_string($conn, $_POST['name']);
            $email = mysqli_escape_string($conn, $_POST['email']);
            $password = mysqli_escape_string($conn, $_POST['password']);
            $surname = mysqli_escape_string($conn, $_POST['surname']);
            $currency = mysqli_escape_string($conn, $_POST['currency']);
            $user_type = 'C';



            $query = "SELECT * FROM users WHERE email = '".$email."'";
            $res = mysqli_query( $conn, $query ) or die("Error: ".mysqli_error($conn));
            if(mysqli_num_rows($res) > 0) {
                $errors[] = "Utente già registrato, accedere dalla pagina di login";
            }else{
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                $query = "call addCustomer('".$name."','".$surname."','".$email."','".$hashedPassword."','".$currency."','".$user_type."',  @user_id)";
                $res = mysqli_query( $conn, $query ) or die("Error: ".mysqli_error($conn));
                if($res){
                    $query = "SELECT @user_id as id";
                    $res = mysqli_query( $conn, $query ) or die("Error: ".mysqli_error($conn));
                    $user_id = mysqli_fetch_assoc($res)['id'];   
                }
                $_SESSION['id'] = $user_id;
                $_SESSION["user_type"] = $user_type;
                $_SESSION['currency'] = $currency;
                header("Location: index.php");
                exit;

            }

        }else{
            $errors[] = "Email o password non rispettano i criteri stabiliti.";
        }


    }


?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Signup | GetYourGuide</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="signup.css" />
        <script src="signup.js" defer></script>
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
                    <h1 class="input_title">Registrazione</h1>
                    <form action="" method="post" name="signUp_customer">
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
                            <label for="email">Email</label>
                            <input type="text" class="L_input_text"  id="email" name="email" placeholder="mariorossi@gmail.com">
                            <div class="error_message hidden" data-input="email">Inserire una mail nel formato: mariorossi@gmail.com</div>
                            <span>La utilizzerai per entrare nel tuo account</span>
                        </div>
                        <div class="input_container">
                            <label for="currency_selection">Qual è la valuta che usi di solito?</label>
                            <select name="currency" class="M_select" id="currency_selection">
                                <option class="currency_option" value="">Seleziona una valuta</option>
                            </select>
                            <div class="error_message hidden" data-input="currency-selection">Selezionare una valuta</div>
                            <span>Potrai sempre modificarla in seguito</span>
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
                            <input type="submit" name="invio" value="Crea un account">
                        </div>
                    </form>
                </div>
            </div>
            <div>
                Hai già un account? <a href="login.php" class="blue_link">Log in</a>
            </div>


        </section>
        <?php include 'footer.php'; ?>
    </body>
</html>