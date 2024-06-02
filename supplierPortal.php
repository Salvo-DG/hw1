<?php
    session_start();
    if(isset($_SESSION['id']) && isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'P'){
        header('Location: home_partner.php');
    }
?>


<html>
    <head>
        <meta charset="UTF-8">
        <title>Supplier Portal | GetYourGuide</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="supplierPortal.css" />
        <script src="supplierPortal.js" defer></script>
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    </head>
    <body>

        
        <header>
            <div id="simpleHeader">
                <a href="index.php"><div class="logo_img M_icon"></div></a>
                <div class="leftIcon_with_des">
                    <img src="MEDIA/ICONS/profile.svg" class="XS_icon">
                    <a class="blue_link" href="login.php">Log in</a>
                </div>
            </div>
        </header>

        <div class="L_Image_pageSection">
            <h1 class="L_Img_Title">Collabora con noi per far crescere la tua attività di viaggio</h1>
            <div class="L_Img_button blue_button" data-link="partnerSignup.php">Iscriviti ora</div>
        </div>

        <section class="NoBack_section">
            <div class="align_div_section">
                <div class="Icon_with_below_text">
                    <img src="MEDIA/ICONS/search.svg" class="S_Icon_with_back">
                    <h4>Risparmia tempo prezioso</h4>
                    <p class="small_paragraph_center">Utilizza i nostri strumenti per risparmiare tempo in modo da poterti concentrare maggiormente sulle tue esperienze indimenticabili rispetto alle attività amministrative</p>
                </div>
                <div class="Icon_with_below_text">
                    <img src="MEDIA/ICONS/language_gray.svg" class="S_Icon_with_back">
                    <h4>Espandi la tua clientela</h4>
                    <p class="small_paragraph_center">Ottieni l'accesso a 50 milioni di visitatori al mese da oltre 190 paesi con i tuoi prodotti ottimizzati in 14 lingue</p>
                </div>
                <div class="Icon_with_below_text">
                    <img src="MEDIA/ICONS/search_insights.svg" class="S_Icon_with_back">
                    <h4>Statistiche sui consumatori</h4>
                    <p class="small_paragraph_center">Fai crescere la tua attività e la tua base clienti a modo tuo, utilizzando dati, approfondimenti e tendenze di viaggio che non troverai da nessun'altra parte</p>
                </div>
            </div>
        </section>

        <section class="pink_section_with_Title">
            <div class="align_div_sec_wT">
                <h2 class="section_Title">Unisciti a noi come Partner</h2>
                <div class="align_div">
                    <div class="step-div">
                        <h4>Condividi le tue informazioni</h4>
                        <p class="small_paragraph_center">Ti chiederemo alcuni semplici dettagli di contatto e aziendali</p>
                    </div>
                    <div class="step-div">
                        <h4>Ricevi i pagamenti</h4>
                        <p class="small_paragraph_center">Inserisci le tue informazioni legali e finanziarie e gestisti tutto da qui</p>
                    </div>
                    <div class="step-div">
                        <h4>Aggiungi i tuoi prodotti</h4>
                        <p class="small_paragraph_center">Crea ed aggiungi le tue attività così da raggiungere i nostri utenti</p>
                    </div>
                </div>
                <div class="M_pinksec_button blue_button" data-link="partnerSignup.php">Iscriviti ora</div>
            </div>
        </section>


        <div class="M_Image_pageSection">
            <h2 class="M_Img_Title">Noi sosteniamo il turismo responsabile</h2>
            <h4 class="M_Img_Des">GetYourGuide promuove tour e attività responsabili e sostenibili dal punto di vista ambientale. Se alla tua azienda è stata assegnata una certificazione ecologica da un'agenzia approvata, la promuoveremo sul nostro sito.</h4>
        </div>
        <section class="NoBack_section">
            <div class="align_div_asks">
                <h2 id="title_asks">Domande frequenti</h2>
                <div class="ask_answer">
                    <div class="ask_div">
                        <h5>Chi può iscriversi?</h5>
                        <img class="arrowDown" src="MEDIA/ICONS/arrow_down.svg">
                    </div>
                    <p class="hidden">Collaboriamo sia con aziende che con operatori indipendenti registrati, legalmente conformi e forniamo prodotti di viaggio di alta qualità.<br><br>Offriamo solo esperienze responsabili, socialmente giuste e sostenibili dal punto di vista ambientale, dai tour a piedi alle esperienze culinarie, alle crociere, alle gite di un giorno e agli autobus hop-on hop-off (e persino alle carte SIM).<br><br>Purtroppo non possiamo collaborare con rivenditori, agenzie di viaggio online, società di gestione delle destinazioni o guide private non registrate.</p>
                </div>
                <div class="ask_answer"> 
                    <div class="ask_div">
                        <h5>Come ci si registra?</h5>
                        <img  class="arrowDown" src="MEDIA/ICONS/arrow_down.svg">
                    </div>
                    <p class="hidden">Inserisci le tue informazioni di contatto e alcuni dettagli aziendali, quindi conferma il tuo indirizzo email. Successivamente, accedi al portale dei fornitori GetYourGuide e carica la registrazione della tua azienda, l'assicurazione di responsabilità civile e le informazioni di pagamento.<br><br>

                        Il nostro team di onboarding esaminerà quindi le tue informazioni e riceverai un'e-mail di conferma una volta che sarai stato esaminato dal nostro team.<br><br>

                        Mentre aspetti, puoi iniziare a creare contenuti per i tuoi prodotti nel Portale Fornitori in modo che tutto sia pronto per essere pubblicato una volta completato l'onboarding.</p>
                </div>
                <div class="ask_answer">
                    <div class="ask_div">
                        <h5>Come vengo pagato? Ci sono commissioni?</h5>
                        <img class="arrowDown" src="MEDIA/ICONS/arrow_down.svg">
                    </div>
                    <p class="hidden">Puoi scegliere di ricevere pagamenti mensili senza costi aggiuntivi oppure pagamenti bimestrali con un piccolo sovrapprezzo. In ogni ciclo di pagamento paghiamo tutte le prenotazioni completate, meno la commissione.<br><br>

                        La commissione per ogni prenotazione completata è destinata alla gestione della nostra piattaforma, alla creazione di strumenti, allo sviluppo di approfondimenti e alla promozione dei tuoi prodotti attraverso decine di canali di marketing.<br><br>

                        Le tariffe possono variare a seconda del Paese in cui operi: l'importo esatto verrà condiviso dopo la registrazione.</p>
                </div>
            </div>
        </section>
        <?php include 'partner_footer.php'; ?>
    </body>
</html>



