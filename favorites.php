<?php
require_once 'db_connection.php';
$database = new DbConnection();
$conn = $database->getConnection();
session_start();



// Verifica se l'utente è loggato
if(isset($_SESSION["id"])){
    if(isset($_SESSION["user_type"]) && $_SESSION['user_type'] == 'C'){
        $customer_logged = true;
        $query = "SELECT nome from users where id='".$_SESSION['id']."'";
        $res = mysqli_query($conn, $query) or die('Error:'.mysqli_error($conn));
        $username = mysqli_fetch_assoc($res)['nome'];
    }else{
        header('Location: index.php');
        exit;
    }
}else{
    header('Location: index.php');
    exit;
}



?>




<html>
<head>
    <meta charset="UTF-8">
    <title>I tuoi preferiti | GetYourGuide</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="favorites.css" />
    <script src="favorites.js" defer></script>
    <script src="exchangeCurrencies.js" defer></script>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <div id="customer_simple_header">
            <a href="index.php"><div class="logo_img M_icon"></div></a>
            <div class="nav_customer_header">
            <div class="nav_item blue_link" data-menu="joinAsPartner" data-link="supplierPortal.php"><span class="item_des">Diventa un partner</span></div>
                <div class="nav_item blue_link" data-menu="account" data-link="#">
                    <img class="S_icon" src="MEDIA/ICONS/account_blu.svg">
                    <span class="item_des"><?php echo $username?></span>
                    <div class="dropdw_big_container hidden">
                        <a href="profile.php" class="dropdown_item_container" data-item="profile">
                            <img class="icon-menu" src="MEDIA/ICONS/profile_black.svg">
                            <span class="dropdown_item">Profilo</span>
                        </a>
                        <a href="#" class="dropdown_item_container" data-item="currency">
                            <img class="icon-menu" src="MEDIA/ICONS/currency.svg">
                            <span class="dropdown_item">Valuta</span>
                        </a>
                        <a href="logout.php" class="dropdown_item_container" data-item="logout">
                            <img class="icon-menu" src="MEDIA/ICONS/logout.svg">
                            <span class="dropdown_item">Logout</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>
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
    <article id="home-page">
        <section class="barra-contenuti-homepage">
                <h2 class="bch-title" data-section="main">Le tue attività preferite</h2>
                <div class="activities_container">
                    <div class="activity_container hidden" data-activity-link="" data-activity-id="">
                        <img class="activity_img" src="https://cdn.getyourguide.com/img/tour/5d6d13a9b46c8.jpeg/98.jpg">
                        <div class="like_container">
                            <img src="like_void.svg" class="like-icon">
                        </div>
                        <div class="orange_sep"></div>
                        <div class="activity_description">
                            <span class="activity_type">TOUR GUIDATO</span>
                            <span class="activity_title">Londra: tour a piedi sulle tracce di Jack lo squartatore</span>
                            <span class="activity_duration">2 ore</span>
                            <div class="activity_review">
                                <img src="MEDIA/ICONS/star_review.svg">
                                <img src="MEDIA/ICONS/star_review.svg">
                                <img src="MEDIA/ICONS/star_review.svg">
                                <img src="MEDIA/ICONS/star_review.svg">
                                <img src="MEDIA/ICONS/star_review.svg">
                                <span class="act_review_score">4.7</span>
                            </div>
                            <span class="activity_price" data-price="" data-discount="">Da  18,71 € a persona</span>
                        </div>
                    </div>   
                </div>  
        </section>
        <section class="barra-contenuti-homepage">
            <div class="newsletter_big_container">
                <img class="img_news" src="MEDIA/SFONDI/newsletter-background.jpg">
                <div class="news_des_container">
                    <div class="des_container">
                        <h2 class="news_title">Il tuo viaggio inizia qui</h2>
                        <h5 class="news_des">Iscriviti ora per ricevere consigli di viaggio, itinerari personalizzati e ispirazione per le vacanze direttamente nella tua casella di posta.</h5>
                        <input type="submit" name="submit_news_registration" class="news_submit" value="Registrati adesso">
                    </div>
                </div>
            </div>
        </section>
    </article>
    <?php include 'footer.php';?>

</body>