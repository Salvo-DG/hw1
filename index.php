<?php
require_once 'db_connection.php';
$database = new DbConnection();
$conn = $database->getConnection();
session_start();


// Verifica se l'utente è loggato
if(isset($_SESSION["id"])){
        $logged = true;
        $query = "SELECT nome from users where id='".$_SESSION['id']."'";
        $res = mysqli_query($conn, $query) or die('Error:'.mysqli_error($conn));
        $username = mysqli_fetch_assoc($res)['nome'];
}



?>




<html>
<head>
    <meta charset="UTF-8">
    <title>Prenota tour, escursioni e attività online | GetYourGuide</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="index.css" />
    <script src="index.js" defer></script>
    <script src="exchangeCurrencies.js" defer></script>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>
<body>
    <input type="hidden" id="session_id" value="
    <?php
        if(isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'C' ){
            print_r($_SESSION['id']);
        }
    ?>">
    
    <header>
        <div id="head">
            <div class="nav_bar">
                <nav class="logo_search">
                        <a href="#"><div class="logo_img"></div></a>
                        <form id="search_input" name="search_bar_form">
                            <input type="text" class="text_research" name="searchText" maxlength="30" placeholder="Cerca su GetYourGuide...">
                            <input type="submit" class="search-button" value="Cerca">
                        </form>
                </nav>
                <nav class="menu_homepage">
                        <a class="menu_item" data-menu-link="Partner" href="supplierPortal.php"><span class="menu-option">Area partner</span></a>
                        <a class="menu_item" data-menu-link="Preferiti" data-access="loginOnly" href="favorites.php"><img class ="icon-menu" src="MEDIA/ICONS/heart_white.svg"><span class="menu-option">Preferiti</span></a>
                        <div class="menu_item" data-menu-link="Profile" href="#">
                            <img class ="icon-menu" src="MEDIA\ICONS\account_white.svg">
                            <span class="menu-option">
                                <?php 
                                    if (isset($logged)){
                                        echo $username;
                                    }else{echo 'Account';};
                                ?>
                            </span>
                            <div class="profile-menu hidden">
                                <?php 
                                    if (!isset($logged)){
                                        echo '<a class="pr-link" href="login.php">
                                        <div class="profile-item">
                                            <img class ="icon-menu" src="MEDIA\ICONS\login.svg">
                                            <span class="pr-item-description">Accedi o registrati</span>
                                        </div>
                                    </a>';
                                    }
                                
                                if (isset($logged)){
                                    echo
                                        '<a class="pr-link" href="profile.php">
                                            <div class="profile-item">
                                                <img class ="icon-menu" src="MEDIA\ICONS\account_black.svg">
                                                <span class="pr-item-description">Profilo</span>
                                            </div>
                                        </a>';
                                }
                                ?>
                                <a class="pr-link" href="#">
                                    <div class="profile-item">
                                        <img class ="icon-menu" src="MEDIA\ICONS\language.svg">
                                        <span class="pr-item-description">Lingua</span>
                                    </div>
                                </a>
                                <a class="pr-link" data-id="currency" href="#">
                                    <div class="profile-item">
                                        <img class ="icon-menu" src="MEDIA\ICONS\currency.svg">
                                        <span class="pr-item-description">Valuta</span>
                                    </div>
                                </a>
                                <a class="pr-link" href="#">
                                    <div class="profile-item">
                                        <img class ="icon-menu" src="MEDIA\ICONS\sun.svg">
                                        <span class="pr-item-description">Tema</span>
                                    </div>
                                </a>
                                <a class="pr-link" href="#">
                                    <div class="profile-item">
                                        <img class ="icon-menu" src="MEDIA\ICONS\help.svg">
                                        <span class="pr-item-description">Centro Assistenza</span>
                                    </div>
                                </a>
                                <?php 
                                    if (isset($logged)){
                                        echo 
                                            '<a class="pr-link" href="logout.php">
                                                <div class="profile-item">
                                                    <img class ="icon-menu" src="MEDIA\ICONS\logout.svg">
                                                    <span class="pr-item-description">Logout</span>
                                                </div>
                                            </a>';
                                    }
                                ?>


                            </div>
                        </div>
                </nav>
            </div>
            <div id="search_input_mobile">
                <input class="text_research" type="search" placeholder="Dove vuoi andare?">
            </div>
            <!-- todo: cambia link -->
            <section class="header-text-description">
                <h1 class="title">Ricordi di viaggio <br> indimenticabili</h1>
                <div class="img-with-text"><img src="MEDIA/LOGO/logo_bianco_original.svg"><span class="white-text-img">Originals by GetYourGuide</span></div>
                <span class="subheader">Approfitta dell'accesso privato alla Johan Cruijff Arena</span>
                <a class="go-to-experience-link" href="#"><span>Scopri di più su questa esperienza ></span></a>
            </section>
            <!-- TODO: caricare le voci sotto dinamicamente -->
            <div class="header-themes-menu">
                <div class="themes-menu-item hidden"></div>
            </div>
        </div>
        <div id="start-header-block"></div>
        <div id="end-header-block"></div>
        <div id="blue-opacity"></div>
    </header>
    <div class="modal-view hidden" data-ref="currency-choose">
        <div class="modal-container">
            <div class="c-modal-header">
                <img class="icon-menu" src="MEDIA\ICONS\close.svg">
                <h3 class="c-modal-header-title">Seleziona la valuta che preferisci</h3>
            </div>
            <section class="c-modal-content">
                <form action="" name="currency_form" method="get" class="form_modal_view">
                </form>
            </section>
        </div>
    </div>
    <div class="scroll-header-container hidden">
        <div class="header-themes-menu-onScroll">
        </div>
    </div>
    <!--Contenuto della pagina web-->
    <article id="home-page">
    <section class="barra-contenuti-homepage">
            <h2 class="bch-title" data-section="main">Esperienze straordinarie nella sezione Sport</h2>
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
                            <img src="MEDIA\ICONS\star_review.svg">
                            <img src="MEDIA\ICONS\star_review.svg">
                            <img src="MEDIA\ICONS\star_review.svg">
                            <img src="MEDIA\ICONS\star_review.svg">
                            <img src="MEDIA\ICONS\star_review.svg">
                            <span class="act_review_score">4.7</span>
                        </div>
                        <span class="activity_price" data-price="" data-discount="">Da  18,71 € a persona</span>
                    </div>
                </div>   
            </div>  
        </section>


    <div class="modal-view error_view hidden">
        <div class="modal-container error_view">
            <div class="c-modal-header">
                <img class="icon-menu" src="MEDIA\ICONS\close.svg">
                <h3 class="c-modal-header-title">Errore   </h3>
            </div>
            <section class="c-modal-content">
                <span class="error_modal_view">Per effettuare questa azione è necessario eseguire il login come utente</span>
            </section>
        </div>
    </div>


    <!-- TODO: Aggiungere pagina attività e possibilità di inserire le recensioni per gli utenti -->













        <!-- todo: cambia link -->
        </section>
        <section class="barra-contenuti-homepage">
            <h2 class="bch-title">Le attrazioni culturali da non perdere</h2>
            <div class="bch-container-medium">
                <a class="bch-single-block" href="#">
                    <img class="bch-block-img" src="MEDIA/IMG_ACTIVITY/parco_guell.jpeg">
                    <div class="bch-block-text-container">
                        <h3 class="bch-block-activity-title">Parco Guell</h3>
                        <h6 class="bch-block-activity-subtitle">96 attività</h6>
                    </div>
                </a>
                <a class="bch-single-block" href="#">
                    <img class="bch-block-img" src="MEDIA/IMG_ACTIVITY/louvre.jpeg">
                    <div class="bch-block-text-container">
                        <h3 class="bch-block-activity-title">Museo del Louvre</h3>
                        <h6 class="bch-block-activity-subtitle">390 attività</h6>
                    </div>
                </a>
                <a class="bch-single-block" href="#">
                    <img class="bch-block-img" src="MEDIA/IMG_ACTIVITY/londonTower.jpeg">
                    <div class="bch-block-text-container">
                        <h3 class="bch-block-activity-title">Torre di Londra</h3>
                        <h6 class="bch-block-activity-subtitle">213 attività</h6>
                    </div>
                </a>
                <a class="bch-single-block" href="#">
                    <img class="bch-block-img" src="MEDIA/IMG_ACTIVITY/rijksmuseum.jpeg">
                    <div class="bch-block-text-container">
                        <h3 class="bch-block-activity-title">Rijksmuseum</h3>
                        <h6 class="bch-block-activity-subtitle">75 attività</h6>
                    </div>
                </a>
            </div>
        </section>
        <!-- todo: cambia link -->
        <section class="barra-contenuti-homepage">
            <h2 class="bch-title">Culture eccezionali in giro per il mondo</h2>
            <div class="bch-container-small">
                <a class="bch-single-block" href="#">
                    <img class="bch-block-img" src="MEDIA/IMG_ACTIVITY/oxford_small.jpg">
                    <h4 class="bch-block-activity-title">Oxford</h4>
                </a>
                <a class="bch-single-block" href="#">
                    <img class="bch-block-img" src="MEDIA/IMG_ACTIVITY/roma_small.jpg">
                    <h4 class="bch-block-activity-title">Roma</h4>
                </a>
                <a class="bch-single-block" href="#">
                    <img class="bch-block-img" src="MEDIA/IMG_ACTIVITY/atene_small.jpg">
                    <h4 class="bch-block-activity-title">Atene</h4>
                </a>
                <a class="bch-single-block" href="#">
                    <img class="bch-block-img" src="MEDIA/IMG_ACTIVITY/delfi_small.jpg">
                    <h4 class="bch-block-activity-title">Delfi</h4>
                </a>
                <a class="bch-single-block" href="#">
                    <img class="bch-block-img" src="MEDIA/IMG_ACTIVITY/chicago_small.jpg">
                    <h4 class="bch-block-activity-title">Chicago</h4>
                </a>
                <a class="bch-single-block" href="#">
                    <img class="bch-block-img" src="MEDIA/IMG_ACTIVITY/manchester_small.jpg">
                    <h4 class="bch-block-activity-title">Manchester</h4>
                </a>
            </div>
        </section>
        <section class="barra-contenuti-homepage">
            <div class="sottocategorie_header">
                <a class="sc-title-link" href="#"><h4 class="sc-title">Attrazioni turistiche popolari</h4></a>
                <a class="sc-title-link" href="#"><h4 class="sc-title">Le destinazioni migliori</h4></a>
                <a class="sc-title-link" href="#"><h4 class="sc-title">I paesi da visitare</h4></a>
                <a class="sc-title-link" href="#"><h4 class="sc-title">Categorie più cliccate</h4></a>
            </div>
            <div class="sottocategorie_container">
                <a class="sc-block" href="#">
                    <span class="sc-item-title" >Parigi</span>
                    <span class="sc-item-description">1951 tour e attività</span>
                </a>
                <a class="sc-block" href="#">
                    <span class="sc-item-title" >Londra</span>
                    <span class="sc-item-description">1643 tour e attività</span>
                </a>
                <a class="sc-block" href="#">
                    <span class="sc-item-title" >Dubai</span>
                    <span class="sc-item-description">1284 tour e attività</span>
                </a>
                <a class="sc-block" href="#">
                    <span class="sc-item-title" >Roma</span>
                    <span class="sc-item-description">2623 tour e attività</span>
                </a>
                <a class="sc-block" href="#">
                    <span class="sc-item-title" >New York</span>
                    <span class="sc-item-description">983 tour e attività</span>
                </a>
                <a class="sc-block" href="#">
                    <span class="sc-item-title" >Cracovia</span>
                    <span class="sc-item-description">1161 tour e attività</span>
                </a>
                <a class="sc-block" href="#">
                    <span class="sc-item-title" >Amsterdam</span>
                    <span class="sc-item-description">908 tour e attività</span>
                </a>
                <a class="sc-block" href="#">
                    <span class="sc-item-title" >Marrakech</span>
                    <span class="sc-item-description">2124 tour e attività</span>
                </a>
                <a class="sc-block" href="#">
                    <span class="sc-item-title" >Barcellona</span>
                    <span class="sc-item-description">1238 tour e attività</span>
                </a>
                <a class="sc-block" href="#">
                    <span class="sc-item-title" >Budapest</span>
                    <span class="sc-item-description">604 tour e attività</span>
                </a>
                <a class="sc-block" href="#">
                    <span class="sc-item-title" >Praga</span>
                    <span class="sc-item-description">886 tour e attività</span>
                </a>
                <a class="sc-block" href="#">
                    <span class="sc-item-title" >Firenze</span>
                    <span class="sc-item-description">1343 tour e attività</span>
                </a>
            </div>
        </section>

        <section class="barra-contenuti-homepage">
            <h2 class="bch-title">Immergiti nella musica da viaggio</h2>
            <div class="bch-logo-container">
                <img class="big-logo" src="MEDIA\ICONS\spotify.png">
                <span class="big-logo-title">Vivi al meglio la tua vacanza con Spotify</span>
                <span>Clicca sotto per scoprire i consigli di Spotify </span>
                <span class="search-button-cs">Vai!</span>
            </div>
            <div class="spotifyView-container hidden">
                <img class="close-spotify" src="MEDIA\ICONS\close.svg">
                <div class="header-playlist">
                    <img class="playlist-img" src="">
                    <div class="playlist-title-des">
                        <span class="group-type-spotify">Playlist</span>
                        <span class="playlist-title"></span>
                        <span class="playlist-des"></span>
                        <div class="playlist-info">
                            <div class="logo-with-des">
                                <img class="small-logo" src="MEDIA\ICONS\spotify.png">
                                <span class="logo-des">Spotify</span>
                            </div>
                            <span class="playlist-info-text"></span>
                        </div>
                    </div>
                </div>
                <div class="background-songs">
                    <div class="header-list-songs">
                        <span class="progressivo-song">#</span>
                        <span class="title-song">Titolo</span>
                        <span class="album-song">Album</span>
                        <span class="duration-song">Durata</span>
                    </div>
                    <div class="song-container">
                        <div class="spec-song-container hidden">
                            <span class="song_num"></span>
                            <div class="spec-song-des">
                                <img class="album-img" src="">
                                <div class="spec-song-title-artist">
                                    <span class="spec-song-title"></span>
                                    <span class="spec-song-artist"></span>
                                </div>
                            </div>
                            <span class="spec-song-album"></span>
                            <span class="spec-song-duration"></span>
                        </div>
                    </div>
                </div>
            </div>


        </section>


    </article>


    <footer>
        <div id="footer-scheme">
            <section class="navigation_links">
                <div class="navigation_link_block">
                    <h3 class="footer-section">Lingua</h3>
                    <select class="footer-selector" name="footer-language-selector" id="footer-language-selector"><option selected value="Italiano">Italiano</option><option value="English">English</option><option value="Spanish">Spanish</option></select>
                    
                </div>
                <!-- todo: cambia link -->
                <div class="navigation_link_block">
                    <h3 class="footer-section">App</h3>
                    <a class="link-appstore" href="#"><img class= "app-store-img" src="MEDIA/LOGO/button_apple.svg"></a>
                    <a class="link-appstore" href="#"><img class= "app-store-img" src="MEDIA/LOGO/button_google.svg"></a>
                </div>
                <div class="navigation_link_block">
                    <div class="title-footer-section" data-sec-index="1">
                        <h3 class="footer-section">Assistenza</h3>
                        <img class="arrow-icon" src="MEDIA\ICONS\arrow.svg">
                    </div>
                    <div class="link-footer-container" data-child-index="1">
                        <a class="link-footer" href="#">Centro Assistenza</a>
                        <a class="link-footer" href="#">Informazioni legali</a>
                        <a class="link-footer" href="#">Informativa sulla privacy</a>
                        <a class="link-footer" href="#">Cookie e preferenze di marketing</a>
                        <a class="link-footer" href="#">Termini e condizioni generali</a>
                        <a class="link-footer" href="#">Informazioni ai sensi della legge sui servizi digitali</a>
                        <a class="link-footer" href="#">Mappa del sito</a>
                        <a class="link-footer" href="#">Non vendere e non condividere le mie informazioni personali</a>
                    </div>
                </div>
                <div class="navigation_link_block">
                    <div class="title-footer-section" data-sec-index="2">
                        <h3 class="footer-section">Azienda</h3>
                        <img class="arrow-icon" src="MEDIA\ICONS\arrow.svg">
                    </div>
                    <div class="link-footer-container" data-child-index="2">
                        <a class="link-footer" href="#">Chi siamo</a>
                        <a class="link-footer" href="#">Opportunità di lavoro</a>
                        <a class="link-footer" href="#">Blog</a>
                        <a class="link-footer" href="#">Rassegna stampa</a>
                        <a class="link-footer" href="#">Buono regalo</a>
                        <a class="link-footer" href="#">Explorer</a>
                    </div>
                </div>
                <div class="navigation_link_block">
                    <div class="navigation_link_block_small">
                        <div class="title-footer-section" data-sec-index="3">
                            <h3 class="footer-section">Collabora con noi</h3>
                            <img class="arrow-icon" src="MEDIA\ICONS\arrow.svg">
                        </div>
                        <div class="link-footer-container" data-child-index="3">
                        <a class="link-footer" href="#">Come partner di fornitura</a>
                        <a class="link-footer" href="#">Come content creator</a>
                        <a class="link-footer" href="#">Come partner affiliato</a>
                        </div>
                    </div>
                    <div class="navigation_link_block_small">
                        <h3 class="footer-section">Metodi di pagamento</h3>
                        <div class="payment-methods-img">
                            <img class="payment-method" src="MEDIA/PAYMENTS/paypal_border.svg">
                            <img class="payment-method" src="MEDIA/PAYMENTS/mastercard.svg">
                            <img class="payment-method" src="MEDIA/PAYMENTS/visa.svg">
                            <img class="payment-method" src="MEDIA/PAYMENTS/maestro.svg">
                            <img class="payment-method" src="MEDIA/PAYMENTS/amex.svg">
                            <img class="payment-method" src="MEDIA/PAYMENTS/jcb.svg">
                            <img class="payment-method" src="MEDIA/PAYMENTS/discover.svg">
                            <img class="payment-method" src="MEDIA/PAYMENTS/sofort.svg">
                            <img class="payment-method" src="MEDIA/PAYMENTS/klarna.svg">
                            <img class="payment-method" src="MEDIA/PAYMENTS/googlepay.svg">
                            <img class="payment-method" src="MEDIA/PAYMENTS/applepay.svg">
                            <img class="payment-method" src="MEDIA/PAYMENTS/bancontact.svg">
                        </div>
                    </div>
                </div>
    
            </section>
            <section class="navigation_bar">
                <span id="copyright-footer">© 2008 - 2024 GetYourGuide. Sviluppata a Catania.</span>
                <nav class="social_links_nav">
                    <a class="social_link" href="#"><img class= "logo_social_img" src="MEDIA\ICONS\facebook_.png" alt="Facebook"></a>
                    <a class="social_link" href="#"><img class= "logo_social_img" src="MEDIA\ICONS\instagram_.png" alt="Instagram"></a>
                    <a class="social_link" href="#"><img class= "logo_social_img" src="MEDIA\ICONS\x_.png" alt="X"></a>
                    <a class="social_link" href="#"><img class= "logo_social_img" src="MEDIA\ICONS\pinterest_.png" alt="Pinterest"></a>
                    <a class="social_link" href="#"><img class= "logo_social_img" src="MEDIA\ICONS\linkedin_.png" alt="Linkedin"></a></nav>
            </section>
        </div>
        
    </footer>

</body>


</html>
