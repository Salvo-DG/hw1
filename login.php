<?php
    require_once 'db_connection.php';
    // Avvia la sessione
    session_start();
    // Verifica l'accesso
    if(isset($_SESSION["id"]) && isset( $_SESSION["user_type"]))
    {
        if($_SESSION["user_type"] == "P"){
            header("Location: home_partner.php");
            exit;
        }else{
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

    $required_fields = ['email', 'password'];

    if (arePostFieldsSet($required_fields)){

        //Connessione al db
        $database = new DbConnection();
        $conn = $database->getConnection();

        
        $email = mysqli_real_escape_string($conn,  $_POST['email']);
        $password = mysqli_real_escape_string($conn,  $_POST['password']);


        $query = "SELECT * FROM users WHERE email = '".$email."'";
        $res = mysqli_query( $conn, $query) or die("Error:".mysqli_error( $conn));
        if (mysqli_num_rows($res) > 0) {
            $row = mysqli_fetch_assoc($res);
            if(password_verify( $password, $row["password"]) ) {
                $id = $row["id"];
                $user_type = $row["tipo_utente"];

                $_SESSION['id'] = $id;
                $_SESSION['user_type'] = $user_type;
                $_SESSION['currency'] = $row['currency_id'];

                $database->freeResult( $res );
                $database->__destruct();
                if ($user_type == "P") {
                    header("Location: home_partner.php");
                }elseif ($user_type == "C") {
                    header("Location: index.php");
                }

                exit;
            }else{
                $errore = true;
            }
            
        }else{
                $errore = true;
        }
    }

?>





<html>
    <head>
        <meta charset="UTF-8">
        <title>Login Page | GetYourGuide</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="login.css" />
        <script src="login.js" defer></script>
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    </head>

    <body>
        <?php
            include 's_header.php';
        ?>
            <section id="main">
                <div class="container_ex">
                    <div class="container_in">
                        <h1 class="input_title">Login</h1>
                        <form name="partnerLogin" method="post">
                            <div class="input_container">
                                <label for="email">Email</label>
                                <input type="text" id="email" name="email" placeholder="Inserisci la tua email">
                                <div class="error_message hidden" data-input="email">Inserire una mail valida</div>
                            </div>

                            <div class="input_container">
                                <label for="password">Password</label>
                                <input type="password" id="password" name="password" placeholder="Inserisci la tua password">
                                <div class="error_message hidden" data-input="password">Compilare questo campo</div>
                            </div>
                            <?php
                                // Verifica la presenza di errori
                                if(isset($errore))
                                {
                                    echo "<p class='errore'>";
                                    echo "Credenziali non valide o Utente non registrato";
                                    echo "</p>";
                                }
                            ?>
                            <input type="submit" name="invio" value="Log in">
                            





                        </form>
                        <div> Non hai ancora un account? <a href="signup.php" class="blue_link">Registrati</a></div>
                        <div> Vuoi vendere su GetYourGuide? <a href="partnerSignup.php" class="blue_link">Diventa un nostro partner</a></div>
                    </div>
                </div>
            </section>
            <?php include 'footer.php'; ?>
    </body>
    


</html>