<?php
require_once 'db_connection.php';
$database = new DbConnection();
$conn = $database->getConnection();
session_start();


// Verifica se l'utente è loggato
if(isset($_SESSION["id"])){
    $customer_logged = false;
    $partner_logged = false;
    if(isset($_SESSION["user_type"]) && $_SESSION['user_type'] == 'C'){
        $customer_logged = true;
        $query = "SELECT nome from users where id='".$_SESSION['id']."'";
        $res = mysqli_query($conn, $query) or die('Error:'.mysqli_error($conn));
        $username = mysqli_fetch_assoc($res)['nome'];
    }elseif(isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'P'){
        $partner_logged = true;
    }
}

if(isset($_GET['activity_id'])){
    $activity_id = $_GET['activity_id'];
}else{
    header('Location: index.php');
}


?>


<html>
<head>
    <meta charset="UTF-8">
    <title>Attività | GetYourGuide</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="activity.css" />
    <script src="activity.js" defer></script>
    <script src="exchangeCurrencies.js" defer></script>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <div id="customer_simple_header">
            <a href="index.php"><div class="logo_img M_icon"></div></a>
            <div class="nav_customer_header">
            <div class="nav_item blue_link" data-menu="joinAsPartner" data-link="supplierPortal.php"><span class="item_des">Area partner</span></div>
            <div class="nav_item blue_link" data-menu="favorites" data-link="favorites.php"><img class="S_icon" src="MEDIA/ICONS/heart_blue.svg"><span class="item_des">Preferiti</span></div>
                <div class="nav_item blue_link" data-menu="account" data-link="#">
                    <img class="S_icon" src="MEDIA/ICONS/account_blu.svg">
                    <span class="item_des"><?php if(isset($username)){echo $username;}else{echo 'Account';}?></span>
                    <div class="dropdw_big_container hidden">
                        <?php if(isset($_SESSION['id'])){
                            echo '<a href="profile.php" class="dropdown_item_container" data-item="profile">
                            <img class="icon-menu" src="MEDIA/ICONS/profile_black.svg">
                            <span class="dropdown_item">Profilo</span>
                        </a>';
                        }
                        ?>
                        <a href="#" class="dropdown_item_container" data-item="currency">
                            <img class="icon-menu" src="MEDIA/ICONS/currency.svg">
                            <span class="dropdown_item">Valuta</span>
                        </a>
                        <?php if(isset($_SESSION['id'])){
                            echo '<a href="logout.php" class="dropdown_item_container" data-item="logout">
                            <img class="icon-menu" src="MEDIA/ICONS/logout.svg">
                            <span class="dropdown_item">Logout</span>
                        </a>';}
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <input type="hidden" name="activity_id" id="activity_id" value="<?php if(isset($activity_id)){echo $activity_id;}?>">
    <input type="hidden" name="user_type" id="user_type" value="<?php if(isset($_SESSION['user_type'])){echo $_SESSION['user_type'];}?>">
    <div class="modal-view hidden" data-ref="currency-choose">
        <div class="modal-container">
            <div class="c-modal-header">
                <img class="icon-menu" src="MEDIA/ICONS/close.svg">
                <h3 class="c-modal-header-title">Seleziona la valuta che preferisci</h3>
            </div>
            <section class="c-modal-content">
                <form action="" name="currency_form" method="get" class="form_modal_view">
                </form>
            </section>
        </div>
    </div>
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
    <article id="activityContainer">
    <!--   <div class="activity_header">
            <span id="place">Luogo dell'attività</span>
            <span id="activity_type">ESCURSIONE IN MONTAGNA</span>
            <h1 id="title">Titolo dell'attività</h1>
            <div class="act_header_des">
                <div class="reviewsAndOperator_container">
                    <div class="reviewScoreContainer">
                        <div class="scoreReviewStars">
                            <img src="MEDIA/ICONS/star_review.svg">
                            <img src="MEDIA/ICONS/star_review.svg">
                            <img src="MEDIA/ICONS/star_review.svg">
                            <img src="MEDIA/ICONS/star_review.svg">
                            <img src="MEDIA/ICONS/star_review.svg">
                        </div>
                        <span class="scoreNum">4.5/5</span>
                    </div>
                    <span class="numReview">4 recensioni</span>
                    <span id="Operator">Fornitore dell'attività: TourOperator</span>
                </div>
                <div class="like_container">
                    <div class="icon_container">
                        <img class="like-icon" src="like_void.svg">
                    </div>

                    <span class="des_like">Aggiungi ai preferiti</span>
                </div>
            </div>
            

        </div>
        <img class="img_activity" src="https://cdn.getyourguide.com/img/tour/b6ede3f022029447.jpeg/98.webp">
        <span class="short_des">Un tour guidato a Roma esplora il Colosseo, il Foro Romano, e il Pantheon, concludendosi alla Fontana di Trevi, arricchito da storie e curiosità sulla vita degli antichi Romani.</span>
        <div class="subSection" data-content="price">
            <h2 class="title_sec">Prezzo del tour</h2>
            <span class="activity_price"></span>
        </div>
        <div class="subSection">
            <h2 class="title_sec">Informazioni sul tour</h2>
            <div class="service">
                <img src="MEDIA/ICONS/logout.svg" alt="">
                <span class="service_des">Cancellazione gratuita</span>
            </div>
                <div class="service">
                    <img src="MEDIA/ICONS/logout.svg" alt="">
                    <span class="service_des">Servizio navetta</span>
                </div>
            </div>

        </div>
        <div class="subSection">
            <div class="info_container" data-content="main-info">
                <div class="titleSection">Informazioni principali</div>
                <div class="textSection"><span class="info">informazione 1</span><span>informazione 2</span></div>
            </div>
            <div class="info_container" data-content="long-des">
                <div class="titleSection">Descrizione completa</div>
                <div class="textSection"><span>Immagina un tour guidato attraverso le antiche strade di Roma, dove la storia si intreccia con la modernità in un affascinante viaggio nel tempo. Il tour inizia al Colosseo, simbolo iconico dell'antica Roma, dove la guida narra le storie dei gladiatori e delle epiche battaglie. Proseguendo, si visita il Foro Romano, cuore pulsante della vita politica e sociale dell'epoca. La guida, con passione e competenza, racconta aneddoti e curiosità sulla vita quotidiana degli antichi Romani. Il tour continua verso il Pantheon, con la sua maestosa cupola, e si conclude alla Fontana di Trevi, dove i partecipanti lanciano una moneta esprimendo un desiderio. Durante l'intero percorso, la guida arricchisce l'esperienza con dettagli storici, leggende e consigli sui migliori posti da visitare e i piatti da assaggiare, rendendo il tour un viaggio indimenticabile attraverso la città eterna.</span></div>
            </div>
        </div>
        <div class="subSection">
            <h2 class="title_sec">Recensioni</h2>
            <div class="Recensioni">
                <div class="reviewBigContainer">
                    <div class="reviewContainer">
                        <div class="header_review">
                            <span class="score">4.7</span>
                            <span class="user">Utente 1</span>
                        </div>
                        <span class="reviewText">testo della recensione</span>
                    </div>
                    <div class="reviewContainer">
                        <div class="header_review">
                            <span class="score">4.7</span>
                            <span class="user">Utente 1</span>
                        </div>
                        <span class="reviewText">testo della recensione</span>
                    </div>
                    <div class="reviewContainer">
                        <div class="header_review">
                            <span class="score">4.7</span>
                            <span class="user">Utente 1</span>
                        </div>
                        <span class="reviewText">testo della recensione</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="subSection">
            <h2 class="title_sec">Lascia una recensione</h2>
            <form action="" id="reviewForm">
                <input type="hidden" name="activity_id" value="">
                <label for="scoreReview">Valutazione da 0 a 5</label>
                <input type="number" name="score" min="0" max="5" step="0.1" id="scoreReview" placeholder="4.5">
                <label for="reviewText">Recensione</label>
                <input type="text" name="reviewText" maxlength="990" id="reviewText" placeholder="Un'esperienza fantastica">
                <input type="submit" name="submitForm" value="Aggiungi la tua recensione">
            </form>
        </div>


        -->
    </article> 
    <?php 
        if(isset($partner_logged) && $partner_logged){
            include_once 'partner_footer.php';
        }else{
            include 'footer.php';
        }
    
    ?>
</body>
</html>