# Homework: sito web completo

## Descrizione

Lo scopo di questo progetto è realizzare un sito web composto da 3-5 pagine distinte, oltre alle pagine di registrazione, login e logout. Il progetto integra le conoscenze acquisite durante il corso riguardo HTML, CSS, JavaScript, PHP e interazione con DBMS, estendendo la tematica sviluppata nei precedenti mini-homework. Si completa quindi a scopi didattici il sito di getyourguide iniziato nei mini-homework precedenti.

In particolare, il sito utilizza API REST da PHP per servizi che richiedono l’utilizzo di credenziali segrete.
## Funzionalità Richieste

1. **Registrazione, Login, Logout**:
    - Meccanismo di registrazione, login, e logout degli utenti.
    - Validazione dei dati sia lato client che lato server.
    - Validazioni minime lato client:
        - Username già in uso.
        - Password con struttura ben definita (lunghezza minima, presenza di maiuscole, numeri, simboli, ecc.).

2. **Home Page**:
    - Contenuti caricati tramite API REST accedendo a pagine PHP del sito.
    - Meccanismi di interazione con l’utente tramite JavaScript e richieste asincrone.
    - Salvataggio su database delle informazioni inserite dall’utente (es. "like" a elementi, recensioni, salvataggio tra preferiti, ricerca di contenuti, ecc.).

3. **Pagine Aggiuntive**:
    - Oltre alla home page, altre 2-4 pagine a scelta.
    - Caricamento di contenuti tramite API REST.

4. **Ricerca Contenuti**:
    - Possibilità di ricercare contenuti tramite API REST esterne.
    - Inserimento nel database di alcuni dei contenuti scelti dall’utente.

5. **Richieste Asincrone**:
    - Uso di richieste asincrone tramite JavaScript per evitare ricaricamenti della pagina.

## Struttura del Progetto

Il progetto è organizzato come segue:

```plaintext
├── index.php                # Home Page
├── signup.php               # Pagina di Registrazione per utenti di tipo customer
├── partnerSignup.php        # Pagina di Registrazione per utenti di tipo partner
├── login.php                # Pagina di Login
├── logout.php               # Script di Logout
├── supplierPortal.php       # Pagina di benvenuto per partners non loggati
├── home_partner.php         # Homepage personalizzata per utenti di tipo partner
├── add_activity.php         # Pagina per utenti di tipo partner finalizzata all'inserimento di un nuovo tour
├── activity.php             # Pagina dinamica per visualizzare nel dettaglio un'attività e le sue recensioni
├── favorites.php            # Pagina per utenti di tipo customer dove si visualizzano i preferiti
├── profile.php              # Pagina dinamica per modificare alcuni dei dati inseriti in fase di registrazione
├── apis
│   ├── exchangeCurrencies.php         # API per aggiornare i tassi di cambio e effettuare la conversione delle valute
│   ├── spotifyApi.php                 # API per richiesta di unna playlist a tema viaggio
├── sql
│   └── db_getyourguide.sql  # Script per Creazione Database
└── README.md                # Documentazione del Progetto
```
I file PHP qui non descritti si occupano di gestire l'interazione tra le pagine principali del sito web, l'interazione con il database e la validazione di alcuni campi.

## Utilizzo

- **Homepage**: Accedi alla homepage (`index.php`).
- **Registrazione customer**: Dalla navbar, alla sezione account->accedi o registrati (`signup.php`) puoi creare un nuovo account di tipo customer.
- **Registrazione partner**: Dalla navbar, vai alla sezione Area partner (`supplierPortal.php`) da qui clicca su **iscriviti ora** e compila il form di registrazione (`partnerSignup.php`).
  - **Partner: aggiungi attività**: Dalla navbar, vai alla sezione aggiungi attività (`add_activity.php`) e compila il form per aggiungere un'attività.
  - **Partner: homepage**: Alla homepage (`home_partner.php`) puoi visualizzare le statistiche sulle tue attività, applicare sconti o eliminare quelle esistenti.
- **Login**: Se hai già un account, accedi alla pagina di login (`login.php`) e accedi con le tue credenziali.
- **Home Page**: Interagisci con i contenuti caricati dinamicamente e utilizza le funzionalità interattive come "like", vedrai i preferiti alla sezione Preferiti della navbar (`favorites.php`).
- **Visualizza un'attività**: Cliccando su un'attività (`activity.php`) puoi aggiungere o rimuovere like e scrivere una recensione se hai un account di tipo customer. La pagina non prevede interazioni per utenti di tipo partner o per utenti non loggati.
- **Ricerca**: Utilizza la funzionalità di ricerca per trovare le attività che desideri (disponibile per tutti i tipi di utente).
- **Cambia valuta**: Da account->valuta puoi sempre selezionare la valuta preferita in cui desideri visualizzare il prezzo delle attività.
- **Aggiorna profilo**: Da account->profilo puoi modificare le informazioni inserite in fase di registrazione. I form valideranno i dati inseriti (non è possibile avere l'email di un altro utente). Servirà ricordare la password precedente per aggiornarla e inserirne una nuova.




