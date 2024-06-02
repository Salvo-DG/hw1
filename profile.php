<?php
require_once 'db_connection.php';
require_once 'userCheck.php';
$database = new DbConnection();
$conn = $database->getConnection();
session_start();


// Verifica se l'utente è loggato
    if(isset($_SESSION["id"])){
            $query = "SELECT * from users where id='".$_SESSION['id']."'";
            $id = $_SESSION['id'];
            $res = mysqli_query($conn, $query) or die('Error:'.mysqli_error($conn));
            $row = mysqli_fetch_assoc($res);
            $nome = $row['nome'];
            $cognome = $row['cognome'];
            $email =$row['email'];
    }else{
        header('Location: index.php');
        exit;
    }
    $errors = array();
    if(isset($id)){
        if(isset($_GET['name']) && isset($_GET['surname'])){
            $newName = mysqli_escape_string($conn, $_GET['name']);
            $newSurname = mysqli_escape_string($conn, $_GET['surname']);
            if($newName != '' && $newSurname != ''){
                $name = $newName;
                $surname = $newSurname;

                $query = "UPDATE users SET nome = '".$newName."' where id='".$id."'";
                $res = mysqli_query($conn, $query) or die("Error: ".mysqli_error($conn));

                $query = "UPDATE users SET cognome = '".$newSurname."' where id='".$id."'";
                $res = mysqli_query($conn, $query) or die("Error: ".mysqli_error($conn));

                header("Location: profile.php");
            }else{
                $errors[] = "Inserire dei valori validi";
            }

        }

        if(isset($_GET["email"])){
            $newEmail = mysqli_escape_string($conn, $_GET["email"]);
            if($newEmail == $email){
                $errors[] = "L'email deve essere diversa da quella attuale";
            }else{
                $user = new User($newEmail);
                if($user->validateEmail()){
                    $query = "SELECT * from users where email = '".$newEmail."'";
                    $res = mysqli_query($conn, $query) or die("Error: ".mysqli_error($conn));
                    if(mysqli_num_rows($res) > 0){
                        $errors[] = "Non è possibile effettuare la modifica:<br>Esiste già un utente con questa email";
                    }else{
                        $email = $newEmail;
                        $query = "UPDATE users SET email = '".$email."' where id = '".$id."'";
                        $res = mysqli_query($conn, $query) or die("Error: ".mysqli_error($conn));
                    }
                }else{
                    $errors[] = "Inserire una mail valida";
                }
            }


        }

        if(isset($_GET["currency"])){

            $query = "SELECT * FROM currencys where id = '".$_GET['currency']."'";
            $res = mysqli_query($conn, $query) or die("Error: ".mysqli_error($conn));
            if(mysqli_num_rows($res) > 0){
                $_SESSION['currency'] = $_GET['currency'];
                $query = "UPDATE users set currency_id = '".$_GET['currency']."' where id = '".$id."'";
                $res = mysqli_query($conn, $query) or die("Error: ".mysqli_error($conn));

            }else{
                $errors[] = "Hai provato a selezionare una valuta inesistente";
            }

            $_GET = array();


        }

        if(isset($_POST["oldPwd"]) && isset($_POST['newPwd'])){
            $oldPwd = mysqli_escape_string( $conn, $_POST['oldPwd']);
            $newPwd = mysqli_escape_string( $conn, $_POST['newPwd']);

            $query = "SELECT password FROM users where id='".$id."'";
            $res = mysqli_query($conn, $query) or die("Error: ".mysqli_error($conn));
            if(mysqli_num_rows($res) > 0){
                $oldPwd_hashed = mysqli_fetch_assoc( $res )['password'];
                if(password_verify($oldPwd, $oldPwd_hashed)){
                    $user = new User(null, $newPwd);
                    if($user->validatePassword()){
                        $newPwd_hashed = password_hash($newPwd, PASSWORD_DEFAULT);
                        $query = "UPDATE users set password = '".$newPwd_hashed."' where id = '".$id."'";
                        $res = mysqli_query($conn, $query) or die("Error: ".mysqli_error($conn));
                    }else{
                        $errors[] = "La password nuova non rispetta i criteri stabiliti";
                    }

                }else{
                    $errors[] = 'La password attuale inserita è errata';
                }
            }
        }


    }

?>
<html>
<head>
    <meta charset="UTF-8">
    <title>Profilo | GetYourGuide</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="profile.css" />
    <script src="profile.js" defer></script>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>
<body>
    <input type="hidden" name="user_type" id="user_type" value="<?php if(isset($_SESSION['user_type'])){echo $_SESSION['user_type'];}?>">

    <header>
        <div id="customer_simple_header">
            <a href="index.php"><div class="logo_img M_icon"></div></a>
            <div class="nav_customer_header">
            <div class="nav_item blue_link" data-menu="joinAsPartner" data-link="supplierPortal.php"><span class="item_des">Area partner</span></div>
            <div class="nav_item blue_link" data-menu="favorites" data-link="favorites.php"><img class="S_icon" src="MEDIA/ICONS/heart_blue.svg"><span class="item_des">Preferiti</span></div>
                <div class="nav_item blue_link" data-menu="account" data-link="#">
                    <img class="S_icon" src="MEDIA/ICONS/account_blu.svg">
                    <span class="item_des"><?php echo $nome?></span>
                    <div class="dropdw_big_container hidden">
                        <a href="logout.php" class="dropdown_item_container" data-item="logout">
                            <img class="icon-menu" src="MEDIA/ICONS/logout.svg">
                            <span class="dropdown_item">Logout</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <div class="modal-view error_view hidden">
        <div class="modal-container error_view">
            <div class="c-modal-header">
                <img class="icon-menu" src="MEDIA/ICONS/close.svg">
                <h3 class="c-modal-header-title">Errore   </h3>
            </div>
            <section class="c-modal-content">
                <span class="error_modal_view">Per effettuare questa azione è necessario eseguire il login come utente</span>
            </section>
        </div>
    </div>
    <article id="main_section">
        <h1>Le tue informazioni</h1>
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
        <div class="errore_server hidden">
            <h5>Errore:</h5>
            <span class="text_error">testo dell'errore</span>
        </div>
        <form action="" name="nameSurname">
            <div class="inputContainer">
                <div class="doubleInput">
                    <div class="small_input_container">
                        <label for="name">Nome</label>
                        <input type="text" name="name" id = "name" placeholder="<?php echo $nome?>">
                        <span class="error_input hidden">Descrizione dell'errore</span>
                    </div>
                    <div class="small_input_container">
                        <label for="name">Cognome</label>
                        <input type="text" name="surname" id="surname" placeholder="<?php echo $cognome?>">
                        <span class="error_input hidden">Descrizione dell'errore</span>
                    </div>
                </div>
                <input type="submit" value="Salva modifiche">
            </div>
        </form>

        <form action="" name="email">
            <div class="inputContainer">
                <label for="email">Email</label>
                <input type="text" name="email" id="email" placeholder="<?php echo $email?>">
                <span class="error_input hidden">Descrizione dell'errore</span>
                <input type="submit" value="Salva modifiche">

            </div>
        </form>

        <form action="" name="currency">
            <div class="inputContainer">
                <label for="currency_select">Valuta preferita</label>
                <select name="currency" id="currency_select">
                    <option class="currency_option" value="">Seleziona una valuta</option>
                </select>
                <span class="error_input hidden">Descrizione dell'errore</span>
                <input type="submit" value="Salva modifiche">
            </div>
        </form>

        <form action="profile.php" name="password" method="post">
            <div class="inputContainer">
                <div class="doubleInput">
                    <div class="small_input_container">
                        <label for="oldPwd">Password attuale</label>
                        <input type="password" name="oldPwd" id="oldPwd">
                        <span class="error_input hidden">Descrizione dell'errore</span>
                    </div>
                    <div class="small_input_container">
                        <label for="newPwd">Password nuova</label>
                        <input type="password" name="newPwd" id="newPwd">
                        <span class="error_input hidden">Descrizione dell'errore</span>
                        <div class="pass_check" data-request="length">Tra 8 e 30 caratteri</div>
                        <div class="pass_check" data-request="num_special_char">Includere almeno un numero ed un carattere speciale (!@#$%^&*(),.?":{}|<>)</div>
                        <div class="pass_check" data-request="UpperLower_char">Lettere maiuscole e minuscole</div>
                        <div class="pass_check" data-request="no_tab">Nessuno spazio vuoto</div>
                    </div>
                </div>
                <input type="submit" value="Modifica password">
            </div>
        </form>
    </article>

    

        <?php include_once 'footer.php';?>
    </body>
    </html>