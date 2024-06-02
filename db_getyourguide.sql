-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Giu 02, 2024 alle 11:34
-- Versione del server: 10.4.32-MariaDB
-- Versione PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_getyourguide`
--

DELIMITER $$
--
-- Procedure
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `addActivity` (IN `p_company_id` INT, IN `p_activity_type` INT, IN `p_city` VARCHAR(50), IN `p_price` DECIMAL(10,2), IN `p_duration` INT, IN `p_title` VARCHAR(255), IN `p_section` INT, OUT `p_activity_id` INT)   BEGIN
    -- Start transaction
    START TRANSACTION;

    -- Insert into activitys table
    INSERT INTO activitys (company_id, activity_type, city, price, duration, title, section)
    VALUES (p_company_id, p_activity_type, p_city, p_price, p_duration, p_title, p_section);
    
    -- Get the last inserted ID
    SET p_activity_id = LAST_INSERT_ID();

    -- Commit transaction
    COMMIT;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `addCustomer` (IN `c_nome` VARCHAR(50), IN `c_cognome` VARCHAR(50), IN `c_email` VARCHAR(100), IN `c_password` VARCHAR(255), IN `c_currency_id` INT, IN `c_tipo_utente` ENUM('P','C'), OUT `user_id` INT)   BEGIN

    START TRANSACTION;

    INSERT INTO users (nome, cognome, email, password, currency_id, tipo_utente)
    VALUES (c_nome, c_cognome, c_email, c_password, c_currency_id, c_tipo_utente);
	SET user_id = last_insert_id();
    COMMIT;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `addPartner` (IN `p_nome` VARCHAR(50), IN `p_cognome` VARCHAR(50), IN `p_email` VARCHAR(100), IN `p_password` VARCHAR(255), IN `p_currency_id` INT, IN `p_tipo_utente` ENUM('P','C'), IN `p_nome_legale` VARCHAR(100), IN `p_city` VARCHAR(255), IN `p_tipo_attivita` ENUM('I','A'), OUT `p_user_id` INT)   BEGIN

    -- Insert the user
    START TRANSACTION;
    INSERT INTO users (nome, cognome, email, password, currency_id, tipo_utente)
    VALUES (p_nome, p_cognome, p_email, p_password, p_currency_id, p_tipo_utente);
    SET p_user_id = LAST_INSERT_ID();

    -- Insert the company
    INSERT INTO companys (id, nome_legale, sede, tipo_attivita)
    VALUES (p_user_id, p_nome_legale, p_city, p_tipo_attivita);
    COMMIT;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_activity` (IN `activityId` INT)   BEGIN

    -- Inizio della transazione
    START TRANSACTION;

    -- Elimina tutte le recensioni associate all'attività
    DELETE FROM reviews WHERE activity_id = activityId;

    -- Elimina l'attività
    DELETE FROM activity_images WHERE id_activity = activityId;
    DELETE FROM activity_descriptions WHERE activity_id = activityId;
    DELETE FROM activity_infos WHERE activity_id = activityId;
    DELETE FROM activitys WHERE id = activityId;

    -- Commit della transazione
    COMMIT;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Struttura della tabella `activitys`
--

CREATE TABLE `activitys` (
  `id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `activity_type` int(11) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `discount` decimal(10,2) DEFAULT NULL,
  `duration` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `update_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `section` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `activitys`
--

INSERT INTO `activitys` (`id`, `company_id`, `activity_type`, `city`, `price`, `discount`, `duration`, `title`, `created_at`, `update_at`, `section`) VALUES
(1, 1, 1, 'Roma, fontana di Trevi', 30.00, 22.00, 150, 'Roma: Scopri la Fontana di Trevi e il tour dei sotterranei', '2024-05-24 16:42:51', '2024-06-01 14:15:29', 2),
(2, 5, 1, 'Roma, stadio Olimpico', 25.00, 20.00, 240, 'Stadio Olimpico: scopri la storia della Roma', '2024-05-25 08:56:26', '2024-05-25 21:33:59', 1),
(4, 1, 2, 'Catania, Etna', 75.00, 75.00, 320, 'Etna: Tour guidato della cima del vulcano con funivia', '2024-05-25 09:27:12', '2024-05-25 21:34:20', 4),
(9, 1, 4, 'Roma, musei vaticani.', 45.00, 36.00, 165, 'Roma: Tour del Vaticano, della Cappella Sistina e della Basilica di San Pietro', '2024-05-30 23:18:32', '2024-05-30 23:18:59', 2),
(10, 1, 4, 'New York, MoMA', 100.00, 80.00, 60, 'NYC: Tour del MoMA prima dell\'orario di apertura con un esperto d\'arte', '2024-05-30 23:22:13', '2024-05-31 10:41:11', 2),
(11, 14, 6, 'Milano, Italia', 85.00, 85.00, 180, 'Milano: Corso di cucina classica della cucina italiana con pasto', '2024-05-31 14:03:28', '2024-05-31 14:03:28', 3),
(12, 16, 7, 'Torino, Italia', 69.00, 69.00, 150, 'Torino: tour gastronomico guidato con degustazione di cioccolato e vino', '2024-05-31 15:50:06', '2024-05-31 15:50:06', 3),
(14, 19, 3, 'Taormina, Messina, Italia', 80.00, 80.00, 120, 'Messina: Tour in barca al tramonto e osservazione dei delfini', '2024-06-01 11:23:16', '2024-06-01 11:23:16', 4),
(15, 19, 3, 'Taormina, Messina, Italia', 60.00, 60.00, 150, 'Tour della costa di Taormina e ricerca dei delfini', '2024-06-01 11:25:15', '2024-06-01 11:25:15', 4),
(16, 20, 8, 'Etna, Catania, Italia', 65.00, 65.00, 300, 'Etna: trekking sul cratere sommitale con funivia e opzione 4x4', '2024-06-01 11:29:17', '2024-06-01 11:29:17', 1),
(17, 21, 4, 'Chiesa di San Carlo Bartolomeo, Vienna', 33.00, 33.00, 75, 'Vienna: concerto Le quattro stagioni di Vivaldi presso la Karlskirche', '2024-06-01 11:35:41', '2024-06-01 11:35:41', 2),
(19, 22, 1, 'Berlino, Germania', 16.00, 16.00, 150, 'Berlino: Tour della Camera Plenaria, del Duomo e del quartiere governativo', '2024-06-01 11:39:55', '2024-06-01 11:39:55', 2),
(20, 23, 3, 'Fiume Tevere, Roma, Italia', 40.00, 40.00, 120, 'Roma: Tour di rafting urbano sul fiume Tevere con pizza romana', '2024-06-01 11:43:08', '2024-06-01 11:43:08', 1),
(21, 24, 1, 'Madison Square Garden, New york', 46.00, 46.00, 60, 'NYC: Esperienza del tour al Madison Square Garden', '2024-06-01 11:47:09', '2024-06-01 11:47:09', 1);

--
-- Trigger `activitys`
--
DELIMITER $$
CREATE TRIGGER `before_activity_insert` BEFORE INSERT ON `activitys` FOR EACH ROW BEGIN
    IF NEW.discount IS NULL THEN
        SET NEW.discount = NEW.price;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struttura della tabella `activity_descriptions`
--

CREATE TABLE `activity_descriptions` (
  `activity_id` int(11) DEFAULT NULL,
  `short_des` varchar(220) DEFAULT NULL,
  `long_des` varchar(2200) DEFAULT NULL,
  `isErasable` tinyint(1) DEFAULT 0,
  `isBus` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `activity_descriptions`
--

INSERT INTO `activity_descriptions` (`activity_id`, `short_des`, `long_des`, `isErasable`, `isBus`) VALUES
(1, 'Partecipa a una visita guidata approfondita di uno dei luoghi più iconici di Roma, la Fontana di Trevi.', 'Approfondisci la storia della Fontana di Trevi con un tour guidato di uno dei luoghi più iconici di Roma. Avventurati oltre la sua imponente facciata e scopri i suoi segreti nascosti e la sua storia prima di esplorare i sotterranei recentemente scoperti.\r\n\r\nIncontra la tua guida e parti per un tour guidato alla scoperta del mistero della Fontana di Trevi. Scopri la sua storia secolare grazie alle spiegazioni dettagliate della tua guida e ammira le figure mitologiche che emergono dalla pietra grezza.\r\n\r\nPoi, scendi a 9 metri di profondità per esplorare il sito archeologico appena scoperto. Segui la tua guida verso un antico acquedotto di 2000 anni, perfettamente funzionante, che fornisce ancora acqua alla fontana sovrastante.\r\n\r\nContinua a vedere i resti di una Domus imperiale e tocca la stratificazione millenaria sotto le strade di Roma. Infine, torna in superficie, dove il tuo tour si concluderà.', 1, 0),
(2, 'Lasciati affascinare dalla passione giallorossa.', 'Lo Stadio Olimpico di Roma è uno dei più iconici impianti sportivi d\'Italia, situato nel complesso sportivo del Foro Italico. Inaugurato nel 1953, lo stadio ha una capienza di oltre 70.000 spettatori ed è utilizzato principalmente per le partite di calcio e atletica leggera. È la sede delle partite casalinghe delle squadre di calcio della Roma e della Lazio, oltre a ospitare eventi sportivi internazionali e concerti di grande richiamo.\r\n\r\nL\'AS Roma, fondata nel 1927, è una delle squadre di calcio più prestigiose e seguite d\'Italia. Con una storia ricca di successi, la Roma ha vinto numerosi titoli nazionali e internazionali, tra cui tre scudetti e diverse Coppe Italia e Supercoppe Italiane. I colori sociali della squadra sono il giallo e il rosso, e la squadra è nota per il suo appassionato seguito di tifosi, chiamati \"romanisti\", che riempiono lo Stadio Olimpico per sostenere i loro beniamini durante le partite.', 0, 1),
(4, 'Escursione ai crateri sommitali dell\'Etna fino al punto più alto consentito con una guida professionista....', 'Salire sulla vetta dei crateri dell\'Etna, una delle escursioni più ambite che un escursionista possa fare. Fai un\'escursione con la guida e ammira la bellezza e la meraviglia del vulcano attivo più alto d\'Europa, fino a 3.000 metri.\r\n\r\nParti dal Rifugio Sapienza a Nicolosi, sali per 2.504 metri con la funivia e inizia la tua escursione. Camminerai per un\'ora e mezza in salita, superando numerosi crateri, in particolare verso il cratere dell\'eruzione del 2001 e il paesaggio lunare che caratterizza l\'area. Durante l\'escursione farai lunghe pause per scattare foto e riposare.\r\n\r\nContinuerai poi ad esplorare la tua escursione verso la Cisternazza e il Belvedere sulla Valle del Bove, considerato il miglior punto panoramico sull\'Etna su un\'abbagliante depressione vulcanica risalente a 64000 anni fa!\r\nQui farete una pausa per ammirare i dintorni, scattare qualche foto e semplicemente ammirare il paesaggio poiché da lassù potrete vedere la costa da Taormina fino a Siracusa.\r\n\r\nDa qui raggiungerete i crateri Barbagallo, fino a 3.000 metri, il punto più alto consentito, e godrete di una vista privilegiata sui crateri sommitali e sulla loro costante emissione di gas e vapori acquei.\r\n\r\nQuindi tornerai a 2500 mt e prenderai la funivia per tornare al Rifugio Sapienza.\r\n\r\nProva l\'incredibile sensazione di essere in cima al mondo in questo bellissimo paesaggio roccioso con il cielo gigante sopra di te.', 1, 0),
(9, 'Approfitta dell\'ingresso prioritario per i Musei Vaticani, la Cappella Sistina e la Basilica di San Pietro. Ammira i capolavori artistici e architettonici provenienti dalle collezioni papali. Contempl', 'Esplora la Città del Vaticano, Patrimonio dell\'Umanità dell\'UNESCO, con un tour guidato dei suoi tesori più importanti. Scopri i Musei Vaticani, la Cappella Sistina, la Basilica di San Pietro e le Tombe dei Papi approfittando dell\'ingresso prioritario incluso a tutti i siti per sfruttare al massimo questa giornata.\r\n\r\nInizia il tour scoprendo i tanti capolavori dell\'arte custoditi nei Musei Vaticani, realizzati da alcuni degli artisti più celebri al mondo. Segui la guida nella lingua da te selezionata e visita le sale più importanti del museo, tra cui la Galleria delle Carte Geografiche e la Galleria degli Arazzi.\r\n\r\nAccedi alla Cappella Sistina per contemplare lo splendore del famoso capolavoro di Michelangelo, il Giudizio Universale, una delle opere d\'arte più celebri mai realizzate. Quindi, esplora i suggestivi interni della Basilica di San Pietro grazie all\'accesso prioritario. Lasciati incantare da uno dei luoghi più sacri al mondo e ammira la celebre Pietà di Michelangelo.', 1, 0),
(10, 'Ammira le opere del Museum of Modern Art prima dell\'apertura in un tour mattutino guidato. Goditi una rara possibilità di esplorare le gallerie...', 'Prova la serenità di un tour esclusivo prima dell\'orario di apertura del Museum of Modern Art (MoMA) di New York City. Unisciti alla tua guida storica dell\'arte professionista in una rilassante passeggiata attraverso le gallerie. Connettiti a un livello più profondo con le opere in mostra prima dell\'apertura al pubblico.\r\n\r\n\r\n\r\nIncontra la mattina presto al MoMA e approfitta di 1 ora di accesso esclusivo alle gallerie tramite un ingresso privato. Con la tua guida e un gruppo più piccolo, goditi la pace e la tranquillità per apprezzare alcune delle opere in mostra, comprese quelle che attirano folle più grandi di visitatori. \r\n\r\n\r\n\r\nCon un\'esperienza più intima pensata per gli amanti dell\'arte, ottieni un apprezzamento più profondo per alcuni dei pezzi in mostra. Scopri alcuni fatti e aneddoti affascinanti sugli artisti e le loro tecniche.\r\n\r\n\r\n\r\nSuccessivamente, esplora i 6 piani delle gallerie d\'arte a tuo piacimento. Scopri un\'ampia gamma di arte moderna e contemporanea, dalla pittura e scultura europea del 1880 al cinema contemporaneo, al design e alla performance art.\r\n\r\n\r\n\r\nAmmira opere famose come Monet Ninfee, La notte stellata,  di Picasso Les Demoiselles d\'Avignon, Dance (I), e  Lattine di zuppa Campbell. Guarda i pezzi contemporanei di Elizabeth Murray, Cindy Sherman, Ai Weiwei e molti altri.\r\n\r\n\r\n\r\nCome parte di questa esperienza, riceverai anche un ingresso aggiuntivo al MoMA PS1, il centro d\'arte contemporanea di Long Island City, nel Queens. Scopri le mostre che promuovono le ultime opere innovative e sperimentali della scena artistica contemporanea.', 1, 0),
(11, 'Partecipa a un corso di cucina pratico con uno chef professionista a Milano...', 'Impara l\'arte della cucina italiana attraverso la pratica durante un workshop nel cuore di Milano. Scopri come preparare piatti italiani deliziosi, semplici e salutari, seguiti da un pasto in famiglia a casa dello chef.\r\n\r\nRecati in un edificio storico per incontrare il tuo chef e inizia a selezionare gli ingredienti migliori. Assaggia una varietà di oli d\'oliva e aceto balsamico di alta qualità provenienti dall\'Italia, serviti con pane artigianale.\r\n\r\nSuccessivamente, ti immergerai nel processo di preparazione della pasta fresca da zero utilizzando uova, farina e una speciale macchina per la pasta. Poi, crea due salse diverse: una classica al pomodoro e una tradizionale al parmigiano.\r\n\r\nScopri anche la storia e le tecniche di preparazione del tradizionale dessert italiano, il Tiramisù, che gusterai insieme al gruppo come piatto finale. Durante l\'esperienza, completa le tue creazioni culinarie con una bottiglia di vino italiano di alta qualità.', 0, 1),
(12, 'Esplora la cucina e la cultura piemontese con questo tour gastronomico guidato da una guida locale.', 'Il tour inizierà con una panoramica di Torino e del suo ruolo in Italia. Come il cibo e il vino hanno prosperato in questo territorio.\r\n\r\nDato che sei un bohémien, inizieremo il tour con un\'esperienza al cioccolato. Assaggia prima di tutto il Bicerin, la tradizionale tazza calda consumata in questi caffè dell\'800.\r\n\r\nPasseremo poi a scoprire come i cioccolatieri locali hanno lavorato il cacao per la nostra felicità. Assaggeremo una selezione di cioccolatini tradizionali come il Gianduiotto, il Cremino e il Cri Cri e passeremo alle versioni scure di qualità superiore realizzate con il Criollo. Parlando dei problemi e delle sfide dell\'approvvigionamento globale.\r\n\r\nTorino si è sviluppata come una comunità di cittadini provenienti da tutte le regioni d\'Italia, ognuno dei quali ha portato con sé le proprie ricette e tradizioni.\r\n\r\nDal mare più vicino alla città, vicino alle Cinque Terre, assaggia la Focaccia Ligure di una bottega a conduzione familiare e scopri come un ottimo olio d\'oliva si sposa con una focaccia soffice e gustosa.\r\n\r\nTorino e il Piemonte sono famosi per i loro vini, provenienti da vigneti alle pendici delle Alpi. Le due degustazioni di vini bianchi e rossi saranno abbinate a prosciutto e formaggio.\r\n\r\nVieni affamato, assetato ed entusiasta di imparare cose nuove e pronto ad esplorare i sapori di Torino.', 1, 1),
(14, 'Esplora le splendide baie dell\'Isola Bella e ammira le sue bellezze, tra cui Capo Taormina e la Grotta delle Sirene. Fermati a fare snorkeling nelle acque cristalline.', 'Partecipa a questo tour in barca di 2 ore per esplorare la costa di Taormina. Il tour partirà dal porto di Giardini Naxos e ti darà la possibilità di esplorare la bellissima costa tra Giardini Naxos e Taormina. Durante il viaggio visiterai anche alcune grotte spettacolari: Grotta del Giorno, Grotta delle Sirene, Grotta Azzurra e Grotta del Corallo.\r\n\r\nCon questo tour potrai ammirare alcuni luoghi meravigliosi, come Capo Schisò, Capo Taormina, Scoglio della croce, Scoglio dei fichi d\'india, Isola bella, baia di Mazzaro, baia di San Nicola e Scoglio dello \"Ziu Innaru\".\r\nNaviga alla ricerca di un branco di tursiopi selvatici nel loro habitat naturale, dove la probabilità di avvistamento è di circa l\'80%.\r\nIl tour prevede una sosta per un tuffo o per fare snorkeling nelle acque cristalline della baia di Taormina, seguita da un rinfresco a base di frutta, spuntini e bevande.', 1, 0),
(15, 'Vivi una grande emozione: la meraviglia della costa di Taormina e i delfini che nuotano in libertà nella baia. Tour in barca con aperitivo.', 'Dopo l\'imbarco navigheremo verso il Promontorio di Capotaormina. Visiteremo la Grotta dell\'Amore e faremo il giro del Promontorio. Avremo l\'opportunità di ammirare dal mare l\'Hotel Capotaormina, incastonato nel Promontorio e unico nel suo stile elegante, retrò e iconico.\r\nProseguiremo verso l\'Isola Bella, visiteremo la Grotta Azzurra e faremo una pausa bagno e un piacevole e rinfrescante aperitivo.\r\nLa navigazione proseguirà verso la baia, alla ricerca dei delfini che nuotano in libertà nella baia. Uno spettacolo unico e affascinante. I delfini non temono l\'uomo e si avvicinano alla barca, rallegrandoci con le loro allegre nuotate, evoluzioni e tuffi.\r\nAvrai tutto il tempo per scattare foto e video che potrai subito condividere con i tuoi amici e follower sui social media.\r\nDopo esserci divertiti con i delfini, torneremo a goderci la luce della baia, il Monte Tauro dal mare e il maestoso Etna.', 1, 0),
(16, 'Attraversa la cima dell\'Etna, il vulcano attivo più alto d\'Europa, con una guida vulcanica in questo tour di trekking per piccoli gruppi...', 'Raggiungi la cima del vulcano attivo più alto d\'Europa con una guida vulcanologa. Scopri la vista dai crateri sommitali, esplora un tunnel di lava e la Valle del Bove.\r\n\r\nIncontra la tua guida presso l\'ufficio del partner locale di fronte alla stazione della funivia dell\'Etna sud. Dalla cima della funivia attraverserai paesaggi lunari a bordo di un 4x4 fino a 2850 metri di altitudine nell\'area di Torre del Filosofo.\r\n\r\nInizia il trekking fino a raggiungere il bordo della Bocca Nuova (3300 metri), un cratere aperto nel 1968, uno dei più impressionanti della zona. Passeggia fino alla cima e goditi un panorama a 360° e, se il tempo lo permette, potrai vedere fino alle Isole Eolie e quasi tutta la Sicilia settentrionale.\r\n\r\nScendi dai crateri sommitali e attraversa i crateri dell\'eruzione del 2002, poi percorri una divertente discesa di sabbia vulcanica e ammira l\'anfiteatro vulcanico della Valle del Bove, una spettacolare caldera lunga 8 km e larga 4 km. Torna in funivia al punto di partenza.', 1, 0),
(17, 'Assisti a un meraviglioso concerto di musica classica con un gruppo di strumenti d\'epoca nella splendida Karlskirche di Vienna. ', 'Lasciati incantare da Le quattro stagioni di Vivaldi, un capolavoro della musica classica. Visita la Karlskirche di Vienna e assisti a un concerto di un quartetto d\'archi e di un basso continuo. L\'esibizione dell\'Orchestra 1756, un gruppo classico che suona strumenti d\'epoca, renderà questa esperienza davvero straordinaria.\r\n\r\nViaggia indietro nel tempo grazie a questa performance serale e vivi momenti indimenticabili all\'insegna di sonorità storiche. Le quattro stagioni includono 4 dei 12 rivoluzionari concerti di violino che interpretano ogni stagione dell\'anno.\r\n\r\nQuesto capolavoro ha tratto ispirazione da 4 sonetti. Lasciati condurre in un mondo ricco di suoni, con tuoni e fulmini, ghiaccio che scricchiola e uccelli che cantano, un giovane pastore addormentato, battute di caccia e molto altro.', 1, 1),
(19, 'Partecipa a un tour guidato nel distretto governativo di Berlino e visita la cupola del Reichstag.', '\r\nIn questo tour guidato del parlamento e del distretto governativo di Berlino, conoscerai parte della storia politica della Germania. Inizia il tuo viaggio dal parlamento e dal distretto governativo.\r\n\r\n\r\n\r\nAttraversa il confine del settore storico e ottieni informazioni interessanti sull\'edificio del Reichstag e sulla sua complicata posizione presso il Muro di Berlino. Successivamente, sali sulla cupola del Reichstag per saperne di più sui compiti e sui doveri del Bundestag. \r\n\r\n\r\n\r\nAscolta l\'affascinante storia dietro l\'edificio del Reichstag, la sua trasformazione in un moderno punto di riferimento progettato da Lord Foster e l\'interessante concetto ecologico su cui è stato costruito il lavoro di Foster. Scopri Berlino dall\'alto, goditi lo skyline della città e scopri di più sulla storia della città.', 1, 0),
(20, 'Parti per un\'adrenalinica avventura di rafting sul fiume Tevere a Roma. Ammira le bellezze della città e sbarca vicino al Ponte Palatino a Trastevere per gustare una pizza alla romana.', '\r\nPercorri il fiume Tevere che scorre nel cuore di Roma con l\'unico fornitore certificato dell\'attività in città. Ammira i luoghi della città da una prospettiva unica, visita l\'Isola Tiberina e gusta una deliziosa pizza alla romana dopo l\'escursione.\r\n\r\nIncontro nel cuore di Roma, vicino a Piazza del Popolo, prima di dirigersi verso il punto di imbarco allo Scalo de Pinedo, sul Tevere. Preparati con tutto l\'equipaggiamento necessario per la tua escursione di rafting urbano, tra cui gommone, casco, pagaia e giubbotto di salvataggio.\r\n\r\nDopo il briefing tecnico, parti per il tuo viaggio lungo il fiume Tevere. Ammira la città da una prospettiva davvero unica, ammirando la vista di alcuni dei suoi monumenti dal fiume.\r\n\r\nPassa accanto a Castel Sant\'Angelo fino a raggiungere l\'Isola Tiberina. Scopri qualcosa su quest\'isola storica prima di continuare l\'avventura verso Ponte Garibaldi, dove avrai l\'incredibile opportunità di cimentarti in vere e proprie rapide nel centro della città.\r\n\r\nInfine, sbarca vicino al Ponte Palatino. Assapora una deliziosa e autentica pizza alla romana per completare la tua esperienza.', 1, 1),
(21, 'Regalati una visita speciale al Madison Square Garden. Partecipa a questo nuovo tour all\'interno dell\'arena più famosa del mondo e scopri di più sui momenti memorabili... ', '\r\nPartecipa a questo tour e scopri perché il Madison Square Garden è l\'arena sportiva più famosa del mondo.\r\n\r\nIl pass con l\'accesso completo ti permetterà di visitare alcune aree esclusive del famoso impianto sportivo. Rivivi alcuni momenti storici del Garden, con la mostra Defining Moments e la retrospettiva Garden 366. Approfitta del trattamento VIP nelle sale private ed esplora lo stadio in cui si sono svolti eventi storici del mondo dello sport e dello spettacolo. \r\n\r\nTroverai sempre una sorpresa! Potrai ammirare il campo da basket ricoperto di ghiaccio? Un palcoscenico in costruzione?', 1, 0);

-- --------------------------------------------------------

--
-- Struttura stand-in per le viste `activity_details`
-- (Vedi sotto per la vista effettiva)
--
CREATE TABLE `activity_details` (
`activity_id` int(11)
,`currency_company` int(11)
,`fornitore` varchar(100)
,`activity_type` varchar(255)
,`city` varchar(50)
,`price` decimal(10,2)
,`discount` decimal(10,2)
,`duration` int(11)
,`title` varchar(255)
,`short_des` varchar(220)
,`long_des` varchar(2200)
,`isErasable` tinyint(1)
,`isBus` tinyint(1)
,`url_img` varchar(255)
);

-- --------------------------------------------------------

--
-- Struttura della tabella `activity_images`
--

CREATE TABLE `activity_images` (
  `img_path` int(11) DEFAULT NULL,
  `id_activity` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `activity_images`
--

INSERT INTO `activity_images` (`img_path`, `id_activity`) VALUES
(5, 1),
(9, 2),
(15, 4),
(3, 9),
(38, 10),
(40, 11),
(39, 12),
(17, 14),
(19, 15),
(16, 16),
(14, 17),
(30, 19),
(12, 20),
(9, 21);

-- --------------------------------------------------------

--
-- Struttura della tabella `activity_infos`
--

CREATE TABLE `activity_infos` (
  `activity_id` int(11) DEFAULT NULL,
  `main_info` varchar(110) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `update_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `activity_infos`
--

INSERT INTO `activity_infos` (`activity_id`, `main_info`, `created_at`, `update_at`) VALUES
(1, 'Scopri le origini e i misteri nascosti della splendida Fontana di Trevi di Roma', '2024-05-24 16:42:51', '2024-05-24 16:42:51'),
(1, 'Dirigiti a nove metri sotto terra per vedere l\'acquedotto di 2000 anni perfettamente funzionante.', '2024-05-24 16:42:51', '2024-05-24 16:42:51'),
(1, 'Goditi un tour a piedi veloce e approfondito dedicato alla Fontana di Trevi', '2024-05-24 16:42:51', '2024-05-24 16:42:51'),
(1, 'Naviga tra le rovine sotto le strade di Roma e ammira una domus imperiale', '2024-05-24 16:42:51', '2024-05-24 16:42:51'),
(2, 'Accedi allo stadio Olimpico', '2024-05-25 08:56:26', '2024-05-25 08:56:26'),
(2, 'Vivi la rivalità con la Lazio', '2024-05-25 08:56:26', '2024-05-25 08:56:26'),
(2, 'Incontra la squadra AS. Roma', '2024-05-25 08:56:26', '2024-05-25 08:56:26'),
(4, 'Fai un\'escursione sul vulcano Etna per vedere i crateri principali', '2024-05-25 09:27:12', '2024-05-25 09:27:12'),
(4, 'Ammira la vista dalla funivia', '2024-05-25 09:27:12', '2024-05-25 09:27:12'),
(4, 'Scopri il paesaggio lunare', '2024-05-25 09:27:12', '2024-05-25 09:27:12'),
(9, 'Scopri i tesori della Città del Vaticano, Patrimonio dell\'Umanità dell\'UNESCO', '2024-05-30 23:18:32', '2024-05-30 23:18:32'),
(9, 'Salta la fila per i Musei Vaticani, la Cappella Sistina e la Basilica di San Pietro', '2024-05-30 23:18:32', '2024-05-30 23:18:32'),
(9, 'Arricchisci la tua esperienza con una guida esperta nella lingua da te scelta', '2024-05-30 23:18:32', '2024-05-30 23:18:32'),
(10, 'Accedi alle gallerie tramite un ingresso privato ed esplora il MoMA senza folla', '2024-05-30 23:22:13', '2024-05-30 23:22:13'),
(10, 'Guarda una varietà di opere famose tra cui quelle di Monet, Van Gogh e Picasso', '2024-05-30 23:22:13', '2024-05-30 23:22:13'),
(10, 'Approfitta dell\'accesso aggiuntivo al centro d\'arte contemporanea MoMA PS1', '2024-05-30 23:22:13', '2024-05-30 23:22:13'),
(11, 'Assaggia alcuni dei migliori oli d\'oliva e aceti balsamici d\'Italia.', '2024-05-31 14:03:28', '2024-05-31 14:03:28'),
(11, 'Scatena lo chef che c\'è in te con un workshop di cucina interattivo a Milano', '2024-05-31 14:03:28', '2024-05-31 14:03:28'),
(11, 'Impara i segreti della preparazione della pasta, dalla lavorazione dell\'impasto alla sua formatura', '2024-05-31 14:03:28', '2024-05-31 14:03:28'),
(12, '10 degustazioni selezionate di cibo, dal cioccolato al formaggio e al prosciutto.', '2024-05-31 15:50:06', '2024-05-31 15:50:06'),
(12, 'Una facile degustazione di due bicchieri con i due vini più iconici del Piemonte', '2024-05-31 15:50:06', '2024-05-31 15:50:06'),
(14, 'Nuota o fai snorkeling nelle acque cristalline dell\'Isola Bella', '2024-06-01 11:23:16', '2024-06-01 11:23:16'),
(14, 'Ammira la bellezza della costa di Taormina con un tour in barca di 2 ore', '2024-06-01 11:23:16', '2024-06-01 11:23:16'),
(15, 'Tour della costa e ricerca dei delfini da vivere in contemporanea', '2024-06-01 11:25:15', '2024-06-01 11:25:15'),
(15, 'Rilassati con l\'aperitivo', '2024-06-01 11:25:15', '2024-06-01 11:25:15'),
(16, 'Sali sul vulcano attivo più alto d\'Europa e ammira la vista mozzafiato dalla cima', '2024-06-01 11:29:17', '2024-06-01 11:29:17'),
(16, 'Osserva il meraviglioso panorama intorno all\'Etna in compagnia di una guida vulcanologica', '2024-06-01 11:29:17', '2024-06-01 11:29:17'),
(16, 'Percepisci la potenza pura della natura mentre esamini i segni delle passate eruzioni dell\'Etna', '2024-06-01 11:29:17', '2024-06-01 11:29:17'),
(16, 'Rilassati durante un giro in funivia e risali il fianco del vulcano attivo', '2024-06-01 11:29:17', '2024-06-01 11:29:17'),
(17, 'Assisti a un fantastico concerto di musica classica presso la Karlskirche di Vienna', '2024-06-01 11:35:41', '2024-06-01 11:35:41'),
(17, 'Ascolta un\'esibizione dell\'Orchestra 1756, un complesso di musicisti classici che suona strumenti d\'', '2024-06-01 11:35:41', '2024-06-01 11:35:41'),
(19, 'Esplora il contesto storico e politico di Berlino e ammira il parlamento e il quartiere governativo ', '2024-06-01 11:39:55', '2024-06-01 11:39:55'),
(19, 'Visita il Plenarsaal e/o la cupola del Reichstag', '2024-06-01 11:39:55', '2024-06-01 11:39:55'),
(20, 'Scopri Roma da un punto di vista esclusivo e unico con un tour di rafting', '2024-06-01 11:43:08', '2024-06-01 11:43:08'),
(20, 'Galleggia lungo il fiume Tevere e ammira la città da una prospettiva unica.', '2024-06-01 11:43:08', '2024-06-01 11:43:08'),
(20, 'Ammira i panorami e goditi una pausa sulla pittoresca Isola Tiberina.', '2024-06-01 11:43:08', '2024-06-01 11:43:08'),
(21, 'Scopri tutto sui quasi 150 anni di storia del Garden grazie a una guida esperta che conosce la stori', '2024-06-01 11:47:09', '2024-06-01 11:47:09'),
(21, 'Goditi l\'accesso esclusivo al backstage dell\'arena e osserva da vicino l\'iconico soffito concavo dal', '2024-06-01 11:47:09', '2024-06-01 11:47:09');

-- --------------------------------------------------------

--
-- Struttura della tabella `activity_likes`
--

CREATE TABLE `activity_likes` (
  `user_id` int(11) NOT NULL,
  `activity_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `activity_likes`
--

INSERT INTO `activity_likes` (`user_id`, `activity_id`, `created_at`) VALUES
(9, 2, '2024-06-01 14:12:09'),
(9, 15, '2024-06-01 14:13:04'),
(10, 1, '2024-05-31 14:29:56'),
(10, 2, '2024-05-31 14:30:02'),
(10, 4, '2024-05-31 14:30:01'),
(10, 9, '2024-05-31 13:56:16'),
(10, 10, '2024-05-31 14:29:57'),
(10, 11, '2024-05-31 14:29:59'),
(11, 1, '2024-05-31 09:43:25'),
(11, 2, '2024-05-31 09:43:17'),
(11, 4, '2024-05-31 09:43:28'),
(11, 9, '2024-05-31 09:43:26'),
(15, 1, '2024-05-31 15:43:06'),
(17, 1, '2024-05-31 15:59:46'),
(17, 2, '2024-05-31 16:02:20'),
(25, 2, '2024-06-01 11:49:10'),
(25, 20, '2024-06-01 11:49:11'),
(26, 11, '2024-06-01 11:49:41'),
(26, 12, '2024-06-01 11:49:41'),
(27, 14, '2024-06-01 11:50:17'),
(28, 17, '2024-06-01 11:51:18'),
(29, 10, '2024-06-01 11:52:03'),
(29, 21, '2024-06-01 11:52:00');

-- --------------------------------------------------------

--
-- Struttura della tabella `activity_types`
--

CREATE TABLE `activity_types` (
  `id` int(11) NOT NULL,
  `activity` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `activity_types`
--

INSERT INTO `activity_types` (`id`, `activity`) VALUES
(1, 'TOUR GUIDATO'),
(2, 'ESCURSIONE'),
(3, 'ATTIVITÀ ACQUATICA'),
(4, 'ATTIVITÀ CULTURALE'),
(5, 'SPORT ALL\'APERTO'),
(6, 'LEZIONE DI CUCINA'),
(7, 'DEGUSTAZIONE DI VINI'),
(8, 'ESCURSIONE IN MONTAGNA'),
(9, 'TOUR STORICO'),
(10, 'SPETTACOLO TEATRALE');

-- --------------------------------------------------------

--
-- Struttura stand-in per le viste `activity_with_avg_reviews`
-- (Vedi sotto per la vista effettiva)
--
CREATE TABLE `activity_with_avg_reviews` (
`id` int(11)
,`company_id` int(11)
,`activity_type` int(11)
,`city` varchar(50)
,`price` decimal(10,2)
,`discount` decimal(10,2)
,`duration` int(11)
,`title` varchar(255)
,`section` int(11)
,`avg_rating` decimal(14,5)
);

-- --------------------------------------------------------

--
-- Struttura della tabella `companys`
--

CREATE TABLE `companys` (
  `id` int(11) NOT NULL,
  `nome_legale` varchar(100) NOT NULL,
  `sede` varchar(255) DEFAULT NULL,
  `tipo_attivita` enum('I','A') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `update_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `companys`
--

INSERT INTO `companys` (`id`, `nome_legale`, `sede`, `tipo_attivita`, `created_at`, `update_at`) VALUES
(1, 'Isola Bella Escursioni SRLS', 'Catania, Italia', 'I', '2024-05-24 16:25:11', '2024-05-31 10:01:50'),
(2, 'Monte Etna SRLS', 'Catania, Italia', 'I', '2024-05-24 16:28:56', '2024-05-31 10:01:54'),
(5, 'A Magica SRL', 'Roma, Italia', 'A', '2024-05-25 08:28:20', '2024-05-31 10:02:25'),
(6, 'Isola Lachea Barche', 'Catania, Italia', 'I', '2024-05-25 10:36:38', '2024-05-31 10:02:00'),
(8, 'Pompei escursioni', 'Napoli, Italia', 'A', '2024-05-25 11:45:51', '2024-05-31 10:02:46'),
(12, 'Mole Antonelliana SRL', 'Torino, Italia', 'A', '2024-05-31 10:09:35', '2024-05-31 10:09:35'),
(14, 'MediasetTour', 'Milano, Italia', 'A', '2024-05-31 14:01:20', '2024-05-31 14:01:20'),
(16, 'Juventus SPA', 'Torino, Italia', 'A', '2024-05-31 15:47:34', '2024-05-31 15:47:34'),
(18, 'ARKASA', 'Aci Catena', 'A', '2024-05-31 16:05:39', '2024-05-31 16:05:39'),
(19, 'TourSulloStretto SRLS', 'Messina, Italia', 'I', '2024-06-01 11:20:22', '2024-06-01 11:20:22'),
(20, 'AttivitàSportive Catania', 'Catania, Italia', 'A', '2024-06-01 11:26:45', '2024-06-01 11:26:45'),
(21, 'Orchester 1756 GmbH', 'Vienna, Austria', 'A', '2024-06-01 11:32:39', '2024-06-01 11:32:39'),
(22, 'Paaßens & Kniestedt Berlin kompakt GmbH', 'Berlino, Germania', 'I', '2024-06-01 11:37:20', '2024-06-01 11:37:20'),
(23, 'Canoa Kayak Academy', 'Roma, Italia', 'A', '2024-06-01 11:41:13', '2024-06-01 11:41:13'),
(24, 'Madison Square Garden Entertainment', 'New York, USA', 'A', '2024-06-01 11:44:48', '2024-06-01 11:44:48');

-- --------------------------------------------------------

--
-- Struttura stand-in per le viste `company_statistics_reviews`
-- (Vedi sotto per la vista effettiva)
--
CREATE TABLE `company_statistics_reviews` (
`company_id` int(11)
,`nome_legale` varchar(100)
,`media_recensioni` decimal(14,5)
);

-- --------------------------------------------------------

--
-- Struttura della tabella `currencys`
--

CREATE TABLE `currencys` (
  `id` int(11) NOT NULL,
  `symbol` varchar(10) DEFAULT NULL,
  `code` varchar(3) NOT NULL,
  `name` varchar(50) NOT NULL,
  `usd_exchange` decimal(10,6) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `update_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `currencys`
--

INSERT INTO `currencys` (`id`, `symbol`, `code`, `name`, `usd_exchange`, `created_at`, `update_at`) VALUES
(1, '$', 'USD', 'US Dollar', 1.000000, '2024-05-24 16:13:57', '2024-05-26 20:11:57'),
(2, '€', 'EUR', 'Euro', 0.921730, '2024-05-24 16:13:57', '2024-06-01 11:18:19'),
(3, '¥', 'JPY', 'Japanese Yen', 157.388615, '2024-05-24 16:13:57', '2024-06-01 11:18:19'),
(4, '£', 'GBP', 'British Pound Sterling', 0.785100, '2024-05-24 16:13:57', '2024-06-01 11:18:19'),
(5, 'A$', 'AUD', 'Australian Dollar', 1.503530, '2024-05-24 16:13:57', '2024-06-01 11:18:19'),
(6, 'C$', 'CAD', 'Canadian Dollar', 1.363040, '2024-05-24 16:13:57', '2024-06-01 11:18:19'),
(7, 'CHF', 'CHF', 'Swiss Franc', 0.902390, '2024-05-24 16:13:57', '2024-06-01 11:18:19'),
(8, '¥', 'CNY', 'Chinese Yuan', 7.243001, '2024-05-24 16:13:57', '2024-06-02 09:02:15'),
(9, 'HK$', 'HKD', 'Hong Kong Dollar', 7.819931, '2024-05-24 16:13:57', '2024-06-01 11:18:19'),
(10, '₹', 'INR', 'Indian Rupee', 83.434364, '2024-05-24 16:13:57', '2024-06-02 09:02:15');

-- --------------------------------------------------------

--
-- Struttura della tabella `images`
--

CREATE TABLE `images` (
  `id` int(11) NOT NULL,
  `img_path` varchar(255) DEFAULT NULL,
  `img_description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `images`
--

INSERT INTO `images` (`id`, `img_path`, `img_description`) VALUES
(1, 'https://cdn.getyourguide.com/img/tour/6447e402a3a10.jpeg/132.webp', 'foro romano, Roma'),
(2, 'https://cdn.getyourguide.com/img/tour/6447e402a3a10.jpeg/132.webp', 'castel sant\'angelo, Roma'),
(3, 'https://cdn.getyourguide.com/img/tour/9c93fe080e4b2ff2.jpeg/132.webp', 'musei vaticani, Roma'),
(4, 'https://cdn.getyourguide.com/img/tour/33cca66c19886c9f.jpeg/132.webp', 'colosseo, Roma'),
(5, 'https://cdn.getyourguide.com/img/tour/ce64fbd0e83f3a3c.jpeg/132.webp', 'fontana di trevi, Roma'),
(6, 'https://cdn.getyourguide.com/img/tour/faa2e3720a481a5c.jpeg/132.webp', 'escursione, pickup'),
(7, 'https://cdn.getyourguide.com/img/tour/ae79a5feac3910c5.jpeg/132.webp', 'escursione, barca a vela'),
(8, 'https://cdn.getyourguide.com/img/tour/02804a4e26cd4eaf7102b59fbeffc4fd14f80b48eb0af36e2dddeeb5b5ead364.jpg/132.webp', 'tempio greco, atene'),
(9, 'https://cdn.getyourguide.com/img/tour/5fa9a7ce9b23b5ec1dced7059650bd419089812919aff709aedec2d31afd177d.jpg/132.webp', 'stadio'),
(10, 'https://cdn.getyourguide.com/img/tour/cf879ee295abc8e4.jpeg/132.webp', 'torre Eiffel, Parigi'),
(11, 'https://cdn.getyourguide.com/img/tour/0af3a8038d3121b1.jpeg/132.webp', 'louvre, Parigi'),
(12, 'https://cdn.getyourguide.com/img/tour/973a5de8d237a9dd.jpeg/132.webp', 'crociera sul fiume'),
(13, 'https://cdn.getyourguide.com/img/tour/3cb5496c667b2e57781fb3c9a3d63780a814ba404f7ca6750dd647b6cea5dc6e.jpg/132.webp', 'cucina'),
(14, 'https://cdn.getyourguide.com/img/tour/3016f2213157ac1d.jpeg/132.webp', 'museo, palazzo reale'),
(15, 'https://cdn.getyourguide.com/img/tour/055c70c5f5c63698.jpeg/132.webp', 'escursione, Etna'),
(16, 'https://cdn.getyourguide.com/img/tour/648b611438ded.jpeg/132.webp', 'trekking, Etna'),
(17, 'https://cdn.getyourguide.com/img/tour/1bbffb4e3de50f8daf58f94fc172a5087fd5b84d94b0538bf14f387513d12b30.jpg/132.webp', 'aperitivo barca'),
(18, 'https://cdn.getyourguide.com/img/tour/5fc93331a7ca7.jpeg/132.webp', 'Catania'),
(19, 'https://cdn.getyourguide.com/img/tour/646a4cc67fcec.jpeg/132.webp', 'mare, Taormina'),
(20, 'https://cdn.getyourguide.com/img/tour/64b7ec2a1f946.jpeg/132.webp', 'street food, Catania'),
(21, 'https://cdn.getyourguide.com/img/tour/5cabae8969524.jpeg/132.webp', 'duomo, Catania'),
(22, 'https://cdn.getyourguide.com/img/tour/630f215272498.jpeg/132.webp', 'aurora boreale'),
(23, 'https://cdn.getyourguide.com/img/tour/635696a72ab7d.jpeg/132.webp', 'treno sulla neve, Svizzera'),
(24, 'https://cdn.getyourguide.com/img/tour/9ae192b45ca97667.jpeg/132.webp', 'crociera sul lago, Svizzera'),
(25, 'https://cdn.getyourguide.com/img/tour/964da07485503f89df83414984455980b9267ea70748bf89bfae74dd44f9a280.jpeg/132.webp', 'mare, Palermo'),
(26, 'https://cdn.getyourguide.com/img/tour/610940c39addf.jpeg/132.webp', 'cibo siciliano'),
(27, 'https://cdn.getyourguide.com/img/tour/5b51bbf9903f5.jpeg/132.webp', 'teatro greco, Siracusa'),
(28, 'https://cdn.getyourguide.com/img/tour/c7eefd51c73b42b048e30d67d39c0f90677cc2147a61a3bd8ecbdc5f51507f1f.jpeg/132.webp', 'piazza, Siracusa'),
(29, 'https://cdn.getyourguide.com/img/tour/a15ec6358d655beb6aecd23bb2d0105785953d3aecfe4c85e462ffa30e70a5e5.jpg/132.webp', 'mare con barca'),
(30, 'https://cdn.getyourguide.com/img/tour/a9e56802cbeea2f944119ad48580aceb70670fda8b36567598d41d7443c71519.jpg/132.webp', 'tour in città, Oxford'),
(31, 'https://cdn.getyourguide.com/img/tour/17e0c2533bd23a79.jpeg/132.webp', 'parco, Stonehenge'),
(32, 'https://cdn.getyourguide.com/img/tour/4d8b6b27bdc3bb06.jpeg/132.webp', 'mulini, Amsterdam'),
(33, 'https://cdn.getyourguide.com/img/tour/5deab6f5aaf49.jpeg/132.webp', 'tour università, Cambridge'),
(34, 'https://cdn.getyourguide.com/img/tour/046b062a217a2ebd.jpeg/132.webp', 'statua greca'),
(35, 'https://cdn.getyourguide.com/img/tour/a9ede7f77aa7c87d1b4a738651d53a362d90f49159a0a657bd39b68a598126d4.jpeg/132.webp', 'corso di cucina'),
(36, 'https://cdn.getyourguide.com/img/tour/c22b532864bd5854b7d3c93776bc8ae42701c47c90b791353eb008a26c1c4b9e.jpg/132.webp', 'duomo, Firenze'),
(37, 'https://cdn.getyourguide.com/img/tour/b0adf0e6b0a9b7a4.jpeg/132.webp', 'golfo, Napoli'),
(38, 'https://cdn.getyourguide.com/img/tour/662d3259dae4fb9c.jpeg/132.webp', 'museo, Torino'),
(39, 'https://cdn.getyourguide.com/img/tour/62a08b35f3aba.jpeg/132.webp', 'piazza, Torino'),
(40, 'https://cdn.getyourguide.com/img/tour/01e6dcda12c45b19.jpeg/132.webp', 'duomo, Milano'),
(41, 'https://cdn.getyourguide.com/img/tour/949d5a8f453dbcf5a5910a20f42ea34e3cc2517ef820040a1c513473986ebfa9.jpeg/132.webp', 'sciare'),
(42, 'https://cdn.getyourguide.com/img/tour/019e62ca03e48aed.jpeg/132.webp', 'acquario, Genova'),
(43, 'https://cdn.getyourguide.com/img/tour/ea6cf11df5dfbff8.jpeg/132.webp', 'cinque terre, Liguria'),
(44, 'https://cdn.getyourguide.com/img/tour/0f02ada20b1678fe.jpeg/132.webp', 'Venezia');

-- --------------------------------------------------------

--
-- Struttura della tabella `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `activity_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `rating` decimal(10,1) DEFAULT NULL,
  `review` varchar(1000) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `reviews`
--

INSERT INTO `reviews` (`id`, `activity_id`, `user_id`, `rating`, `review`) VALUES
(4, 4, 10, 3.0, 'L\'attività è stata discreta, ma nulla di speciale. Il personale era abbastanza cortese, anche se a volte sembrava disinteressato. Le strutture potrebbero essere migliorate. Nel complesso, è stata un\'esperienza passabile, ma non credo che tornerò.'),
(5, 2, 9, 2.0, 'L\'attività è stata discreta, ma nulla di speciale. Il personale era abbastanza cortese, anche se a volte sembrava disinteressato. Le strutture potrebbero essere migliorate. Nel complesso, è stata un\'esperienza passabile, ma non credo che tornerò.'),
(10, 2, 10, 5.0, 'Ho avuto un\'esperienza fantastica con questa attività. Il personale è stato incredibilmente gentile e disponibile. Le strutture erano pulite e ben mantenute. Consiglio vivamente a chiunque di provare questa attività.'),
(12, 10, 10, 4.0, 'Il tour del Museo di Arte Contemporanea è un\'esperienza che consiglio vivamente a chiunque sia interessato all\'arte moderna e contemporanea.La combinazione di una collezione impressionante, una guida esperta e momenti interattivi rende questa visita un\'opportunità imperdibile per arricchire la propria conoscenza artistica. Con qualche piccolo aggiustamento nella gestione del tempo, questo tour potrebbe facilmente raggiungere la perfezione.'),
(14, 1, 15, 5.0, 'La primera vez que la vi, me dejó sin aliento. Esta obra maestra barroca es simplemente majestuosa. Los detalles escultóricos son increíbles, cada figura parece cobrar vida con la luz del sol. Es un espectáculo tanto de día como de noche, cuando las luces realzan su belleza de una manera mágica.  La tradición de lanzar una moneda al agua, asegurando así tu regreso a Roma, es una de esas experiencias que no podés dejar pasar. '),
(15, 12, 15, 5.0, 'Ho avuto un\'esperienza fantastica con questa attività. Il personale è stato incredibilmente gentile e disponibile. Le strutture erano pulite e ben mantenute. Consiglio vivamente a chiunque di provare questa attività.'),
(17, 2, 29, 5.0, 'I had an amazing time at the XYZ Sports Center! The facilities are top-notch, the staff is incredibly friendly and knowledgeable, and the variety of activities available is impressive. The instructors are very professional and make sure everyone is comfortable and safe. I highly recommend this place to anyone looking for a great sports experience!'),
(18, 2, 28, 4.0, 'Der XYZ Sportzentrum ist wirklich großartig! Die Ausstattung ist modern und gut gepflegt. Das Personal ist freundlich und kompetent, und die Auswahl an Aktivitäten ist vielfältig. Einziger kleiner Kritikpunkt ist, dass es manchmal etwas überfüllt ist. Insgesamt aber eine tolle Erfahrung und ich komme gerne wieder.'),
(19, 2, 27, 3.0, 'L\'XYZ Centro Sportivo offre una buona varietà di attività e le strutture sono abbastanza ben tenute. Tuttavia, ho trovato il personale un po\' disorganizzato e le lezioni spesso iniziano in ritardo. Con qualche miglioramento nell\'organizzazione, potrebbe diventare un ottimo posto per fare sport.'),
(20, 2, 25, 2.0, 'Sono rimasto deluso dal XYZ Centro Sportivo. Le attrezzature non erano in buone condizioni e il personale sembrava poco motivato. Inoltre, l\'igiene nelle docce e negli spogliatoi lasciava molto a desiderare. Spero che migliorino questi aspetti in futuro.'),
(21, 2, 26, 1.0, 'Pessima esperienza al XYZ Centro Sportivo. Le strutture erano sporche e mal curate, e il personale è stato scortese e poco professionale. Ho dovuto aspettare molto per iniziare l\'attività e la qualità del servizio è stata davvero deludente. Non consiglio assolutamente questo posto.');

-- --------------------------------------------------------

--
-- Struttura della tabella `sections`
--

CREATE TABLE `sections` (
  `id` int(11) NOT NULL,
  `sectionName` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `sections`
--

INSERT INTO `sections` (`id`, `sectionName`) VALUES
(1, 'Sport'),
(2, 'Cultura'),
(3, 'Gastronomia'),
(4, 'Natura');

-- --------------------------------------------------------

--
-- Struttura della tabella `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `cognome` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `currency_id` int(11) DEFAULT NULL,
  `tipo_utente` enum('P','C') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `update_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `users`
--

INSERT INTO `users` (`id`, `nome`, `cognome`, `email`, `password`, `currency_id`, `tipo_utente`, `created_at`, `update_at`) VALUES
(1, 'Salvatore', 'Di Giacomo', 'salvodigiacomo@gmail.com', '$2y$10$EzeiSuD/GUBGvapa5ChDdeXNBrAMWt31T8OwVYFVoDn8pUwvjL7gG', 2, 'P', '2024-05-24 16:25:11', '2024-05-31 15:02:57'),
(2, 'Mario', 'Rossi', 'mariorossi@gmail.com', '$2y$10$UYONvYd/qTSiSWeuLZA8qeiTbHOpz3VvqLxKZTMcSVc2s6ZaweGMS', 2, 'P', '2024-05-24 16:28:56', '2024-05-30 21:47:25'),
(5, 'Francesco', 'Totti', 'ilcapitano@gmail.com', '$2y$10$3TckwzbwS89JucdDWffqT.9ykEsQ7jMFgHuc68MU2G4D70wCTOyY6', 2, 'P', '2024-05-25 08:28:20', '2024-06-01 12:01:32'),
(6, 'Marianna', 'D\'Andrea', 'mariannadandrea@gmail.com', '$2y$10$Yt1lnrGe9XtJw4x0/poQpO7RCzMvrTS3Zuc8lk2kQXyzcvyAQf1RO', 2, 'P', '2024-05-25 10:36:38', '2024-05-30 21:47:25'),
(7, 'Giuseppe', 'Amantia', 'giuseppeamantia@gmail.com', '$2y$10$VMYYpBLHaqXgAmzgRcH6m.2ZPpPJpqd9gxze939UJo27wVCAK/Itq', 2, 'C', '2024-05-25 11:30:01', '2024-05-30 21:47:25'),
(8, 'Ciro', 'Insigne', 'ciroinsigne@gmail.com', '$2y$10$MQqepWhQACBCO0B.cjsJ4u0hF6s2WZsW5QHvlItHNUVD8MNAELo0K', 2, 'P', '2024-05-25 11:45:51', '2024-05-30 21:47:25'),
(9, 'Riry', 'D\'Andrea', 'rirydandrea@gmail.com', '$2y$10$tht8eKDUT7BH6iIOI4KDHe2txWHLuKjLdLRvSuvlI63JFcnZJennW', 2, 'C', '2024-05-25 20:48:20', '2024-06-01 14:23:44'),
(10, 'Antonella', 'Greco', 'antonellagreco75@gmail.com', '$2y$10$cmvfVx2Wo40JCfdU5rQocOhzn77uGrvdmGCR9XBTUNqiFsdF6KAMK', 2, 'C', '2024-05-28 20:52:29', '2024-05-31 13:56:00'),
(11, 'Filippo', 'Bagnaia', 'filippofama@gmail.com', '$2y$10$I9H06H8tYVAIxWwHF1VQheDbLKqUH0yTh025hhKpCUYg6UkAEAg5q', 4, 'C', '2024-05-31 09:42:12', '2024-05-31 09:42:12'),
(12, 'Stefano', 'Bianco', 'stefanobianco@gmail.com', '$2y$10$rdbUU3GS45nCgVE8Y9TRHuFWoGUNQFUvchRGvaWkJyYO2IBaEs4aW', 2, 'P', '2024-05-31 10:09:35', '2024-05-31 10:09:35'),
(13, 'Salvatore', 'Morimoto', 'morimoto@gmail.com', '$2y$10$vZI/nC4hOkSd5MVv5THtbOoJDrQTKPYZFrypY7SyQpTIgb0qVbUXW', 3, 'C', '2024-05-31 10:32:26', '2024-05-31 10:32:26'),
(14, 'Silvio', 'Berlusconi', 'ilpresidente@gmail.com', '$2y$10$FByNLdQTXZ7jwWuyVcWv0eMe.0kBFwz6ckzKN3udVweX1eUk.sSFu', 1, 'P', '2024-05-31 14:01:20', '2024-05-31 14:01:20'),
(15, 'Paulo', 'Dybala', 'paulodybala@outlook.it', '$2y$10$GxWRCAflQVm7I02IYe2cE.HilRkkq2sgt2sESY8BBaAq.bfsN5tHS', 1, 'C', '2024-05-31 15:42:19', '2024-05-31 15:44:49'),
(16, 'Gianni', 'Agnelli', 'avvocato@juventus.owner.it', '$2y$10$CPkS0cBYjlL03x9eYq1Jpe8tzOOiHUXJA6wYvrEXk4MipY1Xwc2Qe', 2, 'P', '2024-05-31 15:47:34', '2024-05-31 15:47:34'),
(17, 'Valeria', 'Di Giacomo', 'valeriadigiacomo07@gmail.com', '$2y$10$LklTVGyf/Ji.p9Q6pLRk0.xi1PLlYvlDGj28RCqg4YR/S93EKAx3q', 2, 'C', '2024-05-31 15:58:45', '2024-05-31 15:58:45'),
(18, 'Giorgia', 'Pappalardo', 'pappalardogiorgia@gmail.com', '$2y$10$AgsuxPmCyi0H7Xoyej5yEutpcWFEBj/mo/Wl8CYqBGLzRrYBPAJ1.', 2, 'P', '2024-05-31 16:05:39', '2024-05-31 16:05:39'),
(19, 'Giovanni', 'Acquaviva', 'giovanniacquaviva@gmail.com', '$2y$10$/J6bTne/vRQBJYYMgxLzTuir9pGisrQt4zWWv0jgJxBNwsCwykbji', 2, 'P', '2024-06-01 11:20:22', '2024-06-01 11:20:22'),
(20, 'Turi', 'Passalacqua', 'turipassalaqua@gmail.com', '$2y$10$xyF56YGysAGHOi6dvsKcdOaUKCXbUQKw9a59ivRwGLt8zVJPR8p3m', 2, 'P', '2024-06-01 11:26:45', '2024-06-01 11:26:45'),
(21, 'Antonio', 'Vivaldi', 'antoniovivaldi@gmail.com', '$2y$10$3TckwzbwS89JucdDWffqT.9ykEsQ7jMFgHuc68MU2G4D70wCTOyY6', 2, 'P', '2024-06-01 11:32:39', '2024-06-01 11:32:39'),
(22, 'Thomas', 'Muller', 'thomasmuller@bayern.com', '$2y$10$eU7uNC2Lfz9ot7TllGM.gOQUwlS2clJ/7QZFOXTlKkn0eG82t/Ak6', 2, 'P', '2024-06-01 11:37:20', '2024-06-01 11:37:20'),
(23, 'Daniele', 'De Rossi', 'danielederossi@asroma.com', '$2y$10$696puWvAyjK4NIrIDHt2Cegjr/.zPz0DcxcnHZ9QKlu0.zPjtYfwy', 2, 'P', '2024-06-01 11:41:13', '2024-06-01 11:41:13'),
(24, 'Lebron', 'James', 'lebronjames@gmail.com', '$2y$10$BVJfwu4rVbDVLfpcY7TTwem6530PR3G4M7osoQovo7aWSq6Z1ClRK', 1, 'P', '2024-06-01 11:44:48', '2024-06-01 11:44:48'),
(25, 'Giulia', 'Bianchi', 'giuliabianchi@gmail.com', '$2y$10$zCAN1.2HWLsG1DaE.q6.2./rizlOBENQU0RGsZHwhOURxHPZmDPlG', 2, 'C', '2024-06-01 11:49:07', '2024-06-01 11:49:07'),
(26, 'Matteo', 'Conti', 'matteoconti@gmail.com', '$2y$10$YbqQ/tljqIzxJgm6pl0/OeE4Z41pHlmibl8Lg6zrazSVKaigodX2e', 2, 'C', '2024-06-01 11:49:38', '2024-06-01 11:49:38'),
(27, 'Andrea', 'Galli', 'andreagalli@gmail.com', '$2y$10$GZuRJPctsWsx/OYaIvEJd.Th1qfR3mG/ze8s/L.2QRnHQ7fGZ7cgG', 2, 'C', '2024-06-01 11:50:14', '2024-06-01 11:50:14'),
(28, 'Claudia', 'Hoffman', 'claudiahoffman@gmail.com', '$2y$10$HyqauJ02sV9L/Qr.0ZUqION84vUTcKZDE/ggPpM/aRw5s./SvHgj.', 2, 'C', '2024-06-01 11:51:09', '2024-06-01 11:51:09'),
(29, 'Jessica', 'Williams', 'jessicawilliams@gmail.com', '$2y$10$OM2sWfNqWLQI1IeyWySLZeadyXkptYXNdnHxTOXBbwZC8pz6hSwRC', 1, 'C', '2024-06-01 11:51:55', '2024-06-01 11:51:55');

-- --------------------------------------------------------

--
-- Struttura stand-in per le viste `users_currency_symbol`
-- (Vedi sotto per la vista effettiva)
--
CREATE TABLE `users_currency_symbol` (
`user_id` int(11)
,`currency_id` int(11)
,`currency_symbol` varchar(10)
);

-- --------------------------------------------------------

--
-- Struttura per vista `activity_details`
--
DROP TABLE IF EXISTS `activity_details`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `activity_details`  AS SELECT `a`.`id` AS `activity_id`, `cu`.`id` AS `currency_company`, `c`.`nome_legale` AS `fornitore`, `at`.`activity` AS `activity_type`, `a`.`city` AS `city`, `a`.`price` AS `price`, `a`.`discount` AS `discount`, `a`.`duration` AS `duration`, `a`.`title` AS `title`, `ad`.`short_des` AS `short_des`, `ad`.`long_des` AS `long_des`, `ad`.`isErasable` AS `isErasable`, `ad`.`isBus` AS `isBus`, `i`.`img_path` AS `url_img` FROM (((((((`activitys` `a` left join `activity_types` `at` on(`a`.`activity_type` = `at`.`id`)) left join `activity_descriptions` `ad` on(`a`.`id` = `ad`.`activity_id`)) left join `activity_images` `ai_img` on(`a`.`id` = `ai_img`.`id_activity`)) left join `images` `i` on(`ai_img`.`img_path` = `i`.`id`)) left join `companys` `c` on(`a`.`company_id` = `c`.`id`)) left join `users` `u` on(`u`.`id` = `c`.`id`)) left join `currencys` `cu` on(`cu`.`id` = `u`.`currency_id`)) ;

-- --------------------------------------------------------

--
-- Struttura per vista `activity_with_avg_reviews`
--
DROP TABLE IF EXISTS `activity_with_avg_reviews`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `activity_with_avg_reviews`  AS SELECT `a`.`id` AS `id`, `a`.`company_id` AS `company_id`, `a`.`activity_type` AS `activity_type`, `a`.`city` AS `city`, `a`.`price` AS `price`, `a`.`discount` AS `discount`, `a`.`duration` AS `duration`, `a`.`title` AS `title`, `a`.`section` AS `section`, CASE WHEN `r`.`avg_rating` is null THEN 0 ELSE `r`.`avg_rating` END AS `avg_rating` FROM (`activitys` `a` left join (select `reviews`.`activity_id` AS `activity_id`,avg(`reviews`.`rating`) AS `avg_rating` from `reviews` group by `reviews`.`activity_id`) `r` on(`a`.`id` = `r`.`activity_id`)) ;

-- --------------------------------------------------------

--
-- Struttura per vista `company_statistics_reviews`
--
DROP TABLE IF EXISTS `company_statistics_reviews`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `company_statistics_reviews`  AS SELECT `c`.`id` AS `company_id`, `c`.`nome_legale` AS `nome_legale`, avg(`r`.`rating`) AS `media_recensioni` FROM ((`companys` `c` left join `activitys` `a` on(`c`.`id` = `a`.`company_id`)) left join `reviews` `r` on(`a`.`id` = `r`.`activity_id`)) GROUP BY `c`.`id` ;

-- --------------------------------------------------------

--
-- Struttura per vista `users_currency_symbol`
--
DROP TABLE IF EXISTS `users_currency_symbol`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `users_currency_symbol`  AS SELECT `u`.`id` AS `user_id`, `c`.`id` AS `currency_id`, `c`.`symbol` AS `currency_symbol` FROM (`users` `u` left join `currencys` `c` on(`u`.`currency_id` = `c`.`id`)) ;

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `activitys`
--
ALTER TABLE `activitys`
  ADD PRIMARY KEY (`id`,`company_id`,`title`),
  ADD KEY `activity_type` (`activity_type`),
  ADD KEY `company_id` (`company_id`);

--
-- Indici per le tabelle `activity_descriptions`
--
ALTER TABLE `activity_descriptions`
  ADD KEY `activity_id` (`activity_id`);

--
-- Indici per le tabelle `activity_images`
--
ALTER TABLE `activity_images`
  ADD KEY `img_path` (`img_path`),
  ADD KEY `id_activity` (`id_activity`);

--
-- Indici per le tabelle `activity_infos`
--
ALTER TABLE `activity_infos`
  ADD KEY `activity_id` (`activity_id`);

--
-- Indici per le tabelle `activity_likes`
--
ALTER TABLE `activity_likes`
  ADD PRIMARY KEY (`user_id`,`activity_id`),
  ADD KEY `activity_id` (`activity_id`);

--
-- Indici per le tabelle `activity_types`
--
ALTER TABLE `activity_types`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `companys`
--
ALTER TABLE `companys`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `currencys`
--
ALTER TABLE `currencys`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `activity_id` (`activity_id`);

--
-- Indici per le tabelle `sections`
--
ALTER TABLE `sections`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `activitys`
--
ALTER TABLE `activitys`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT per la tabella `activity_types`
--
ALTER TABLE `activity_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT per la tabella `currencys`
--
ALTER TABLE `currencys`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT per la tabella `images`
--
ALTER TABLE `images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT per la tabella `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT per la tabella `sections`
--
ALTER TABLE `sections`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT per la tabella `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `activitys`
--
ALTER TABLE `activitys`
  ADD CONSTRAINT `activitys_ibfk_1` FOREIGN KEY (`activity_type`) REFERENCES `activity_types` (`id`),
  ADD CONSTRAINT `activitys_ibfk_2` FOREIGN KEY (`company_id`) REFERENCES `companys` (`id`);

--
-- Limiti per la tabella `activity_descriptions`
--
ALTER TABLE `activity_descriptions`
  ADD CONSTRAINT `activity_descriptions_ibfk_1` FOREIGN KEY (`activity_id`) REFERENCES `activitys` (`id`);

--
-- Limiti per la tabella `activity_images`
--
ALTER TABLE `activity_images`
  ADD CONSTRAINT `activity_images_ibfk_1` FOREIGN KEY (`img_path`) REFERENCES `images` (`id`),
  ADD CONSTRAINT `activity_images_ibfk_2` FOREIGN KEY (`id_activity`) REFERENCES `activitys` (`id`);

--
-- Limiti per la tabella `activity_infos`
--
ALTER TABLE `activity_infos`
  ADD CONSTRAINT `activity_infos_ibfk_1` FOREIGN KEY (`activity_id`) REFERENCES `activitys` (`id`);

--
-- Limiti per la tabella `activity_likes`
--
ALTER TABLE `activity_likes`
  ADD CONSTRAINT `activity_likes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `activity_likes_ibfk_2` FOREIGN KEY (`activity_id`) REFERENCES `activitys` (`id`);

--
-- Limiti per la tabella `companys`
--
ALTER TABLE `companys`
  ADD CONSTRAINT `companys_ibfk_1` FOREIGN KEY (`id`) REFERENCES `users` (`id`);

--
-- Limiti per la tabella `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`activity_id`) REFERENCES `activitys` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
