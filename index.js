function jsonLoadSections(json){
    const headers = document.querySelectorAll('.header-themes-menu,.header-themes-menu-onScroll');
    for (let header of headers){
        for (let sec of json){
            const sectionBut = document.createElement('div');
            sectionBut.classList.add('themes-menu-item');
            sectionBut.dataset.menuCategory = sec.id;
            sectionBut.textContent = sec.sectionName;
            sectionBut.addEventListener('click',menuClick);
            sectionBut.addEventListener('click', loadSectionActivities);
            header.appendChild(sectionBut);
        }

    }
}

function loadSections(){
    const url = 'getDbInfo.php?getSections=1';
    fetch(url, {method:'GET'}).then(onResponse, onError).then(jsonLoadSections);

}



let urlFields = {};

document.addEventListener('DOMContentLoaded', loadSections);


function onScroll(){
    const header = document.querySelector('header');
    const scrollContainer = document.querySelector('.scroll-header-container');
    if (window.scrollY > header.offsetHeight){
        scrollContainer.classList.remove('hidden');        
    }
    else{
        scrollContainer.classList.add("hidden");
    }
}

function closeSectionsFooter(){
    if (window.innerWidth < 768){
        const subsections = document.querySelectorAll('.link-footer-container');
        for (let subsection of subsections){
            subsection.classList.add('hidden');
        }
    }
}

function menuClick(event){
    event.preventDefault();
    const item = event.currentTarget;
    const header = document.querySelector('header');
    let nuovoURL = null;
    let subheader = document.querySelector('.header-text-description .subheader')
    if (item.dataset.menuCategory === '1'){
        nuovoURL = 'MEDIA/SFONDI/sezione_sport.jpg';
        header.style.backgroundImage = "url('" + nuovoURL + "')";
        subheader.textContent = 'Approfitta dell\'accesso privato alla Johan Cruijff Arena'
    }
    if (item.dataset.menuCategory === '2'){
        nuovoURL = 'MEDIA/SFONDI/sezione_cultura.jpg';
        header.style.backgroundImage = "url('" + nuovoURL + "')";
        subheader.textContent = 'Accendi le luci sui musei vaticani'
    }
    if (item.dataset.menuCategory === '3'){
        nuovoURL = 'MEDIA/SFONDI/gastronomia.jpg';
        header.style.backgroundImage = "url('" + nuovoURL + "')";
        subheader.textContent = 'Riscopri le tradizioni culinarie italiane a Roma'
    }
    if (item.dataset.menuCategory === '4'){
        nuovoURL = 'MEDIA/SFONDI/natura.jpg';
        header.style.backgroundImage = "url('" + nuovoURL + "')";
        subheader.textContent = 'Ammira il tramonto e osserva le stelle al Parco Nazionale del Teide'
    }
}



function likeClick(event){
    event.preventDefault();
    event.stopPropagation();
    if (sessionId < 1){
        openErrorModalView();
        return;
    }else{
        const like = event.currentTarget;
        const activity = like.closest('.activity_container').dataset.activityId;
        if (like.classList.contains('liked')){
            like.classList.remove('liked');
            like.src = 'like_void.svg';
            url = "handleLike.php?action="+encodeURIComponent('Unlike')+"&activity="+encodeURIComponent(activity);
        }
        else {
            like.classList.add('liked');
            like.src = 'like_pressed.svg';
            url = "handleLike.php?action="+encodeURIComponent('Like')+"&activity="+encodeURIComponent(activity);
        }
        fetch(url, {method:'get'});
    }
}

function footerSectionClick(event){
    if (window.innerWidth < 768){
        const section = event.currentTarget;
        const index = parseInt(section.dataset.secIndex);
        const subsections = document.querySelectorAll('.link-footer-container');
        for (let subsection of subsections){
            if (parseInt(subsection.dataset.childIndex) === index){
                subsection.classList.toggle('hidden');
            }
        }
    }

}

function profileIconOn(event){
    if (window.innerWidth > 768) {
        const profileIcon = event.currentTarget;
        const profileMenu = profileIcon.querySelector('.profile-menu');
        if (profileMenu.classList.contains('hidden')){
            profileMenu.classList.remove('hidden');
        }
    }


}

function profileIconLeave(event){
    if (window.innerWidth > 768) {
        const profileIcon = event.currentTarget;
        const profileMenu = profileIcon.querySelector('.profile-menu');
        if (!profileMenu.classList.contains('hidden')){
            profileMenu.classList.add('hidden');
        }
    }

}

function closeModalView(event){
    document.body.classList.remove('no-scroll');
    event.currentTarget.closest('.modal-view').classList.add('hidden');
}


function openCurrencyView(event){
    event.preventDefault();
    document.body.classList.add('no-scroll');
    const currencyView = document.querySelector('.modal-view[data-ref="currency-choose"]');
    currencyView.style.top = window.scrollY +'px';
    currencyView.classList.remove('hidden');
}

function openErrorModalView(){
    document.body.classList.add('no-scroll');
    const errorView = document.querySelector('.modal-view.error_view');
    errorView.style.top = window.scrollY +'px';
    errorView.classList.remove('hidden');
    return;
}

function clickOnInput(event){
    inputContainer = event.currentTarget.parentNode.parentNode;
    inputContainer.classList.add('clicked');
}

function clickOffInput(event){
    inputContainer = event.currentTarget.parentNode.parentNode;
    inputContainer.classList.remove('clicked');
}

function overButton(event){
    event.currentTarget.classList.add('button-hover')
}

function leaveButton(event){
    event.currentTarget.classList.remove('button-hover')
}

function onResponse(response){
    return response.json();
}

function onError(error){
    console.log('Errore: ' + error);
}


window.addEventListener('scroll', onScroll);
window.addEventListener('load', closeSectionsFooter);

const scrollMenuItem = document.querySelectorAll('.themes-menu-item');
for (let item of scrollMenuItem){
    item.addEventListener('click',menuClick);
}

const footerSections = document.querySelectorAll('.title-footer-section');
for (let section of footerSections){

    section.addEventListener('click', footerSectionClick);
}

const profileIcon = document.querySelector('.menu_item[data-menu-link="Profile"]');
profileIcon.addEventListener('mouseenter', profileIconOn)
profileIcon.addEventListener('mouseleave', profileIconLeave)

const currencyButtonMenu = document.querySelector(".profile-menu .pr-link[data-id=currency]")
currencyButtonMenu.addEventListener('click', openCurrencyView);

const closeIcons = document.querySelectorAll('.modal-view .icon-menu');
for(let closeIcon of closeIcons){
    closeIcon.addEventListener('click', closeModalView);
}


function searchEvent(event){
    event.preventDefault();
}


const headerTextResearch = document.querySelector('#search_input');
headerTextResearch.addEventListener('submit', searchEvent);


function msToSec(millisecondi){
    var minuti = Math.floor(millisecondi / 60000);
    var secondi = ((millisecondi % 60000) / 1000).toFixed(0);
    secondi = (secondi < 10 ? '0' : '') + secondi;
    return minuti + ":" + secondi;
}


function onJson_playlist(json){
    const spotifyView = document.querySelector('.spotifyView-container');
    const headerPlaylist = spotifyView.querySelector('.header-playlist');

    const playlistImg = headerPlaylist.querySelector('.playlist-img');
    playlistImg.src = json.images[0].url;

    const playlistDescription = headerPlaylist.querySelector('.playlist-title-des')

    const title = playlistDescription.querySelector('.playlist-title');
    title.textContent = json.name;

    const description = playlistDescription.querySelector('.playlist-des');
    description.textContent = json.description;

    const playlistInfo = playlistDescription.querySelector('.playlist-info');
    const infoText = playlistInfo.querySelector('.playlist-info-text');
    const numFollowers = json.followers.total;
    const numSongs = json.tracks.total;
    infoText.textContent= numFollowers + ' followers, ' + numSongs + ' tracce';
    let i=1;
    const songsContainerBig = spotifyView.querySelector('.background-songs');
    const songsContainerMedium = songsContainerBig.querySelector('.song-container');

    for (let song of json.tracks.items){
        const songContainer = document.createElement('div');
        songContainer.classList.add('spec-song-container');

        const songNum = document.createElement('span');
        songNum.classList.add('song_num');
        songNum.textContent = i;
        i++;

        const songDescription = document.createElement('div');
        songDescription.classList.add('spec-song-des');

        const albumImg = document.createElement('img');
        albumImg.classList.add('album-img');
        albumImg.src = song.track.album.images[0].url;

        const songTitleArtistContainer = document.createElement('div');
        songTitleArtistContainer.classList.add('spec-song-title-artist');

        const songTitle = document.createElement('span');
        songTitle.classList.add('spec-song-title');
        songTitle.textContent = song.track.name;

        const songArtist = document.createElement('span');
        songArtist.classList.add('spec-song-artist');
        for (let artist of song.track.artists){
            nameString = artist.name + (artist === song.track.artists[song.track.artists.length - 1] ? '':', ');
            songArtist.textContent += nameString; 
        }

        const albumSong = document.createElement('span');
        albumSong.classList.add('spec-song-album');
        albumSong.textContent = song.track.album.name;

        const songDuration = document.createElement('span');
        songDuration.classList.add('spec-song-duration');
        const duration = msToSec(song.track.duration_ms);
        songDuration.textContent = duration;

        songsContainerMedium.appendChild(songContainer);
        songContainer.appendChild(songNum);
        songContainer.appendChild(songDescription);
        songDescription.appendChild(albumImg);
        songDescription.appendChild(songTitleArtistContainer);
        songTitleArtistContainer.appendChild(songTitle);
        songTitleArtistContainer.appendChild(songArtist);
        songContainer.appendChild(albumSong);
        songContainer.appendChild(songDuration);


    }

    spotifyView.classList.remove('hidden');
}


function searchSongs(event){
    event.preventDefault();
    playlist_id = '37i9dQZF1DX4MNIYb0mgSO';
    fetch('APIS/spotifyApi.php?playlist_id='+playlist_id).then(onResponse,onError).then(onJson_playlist);
    
}

const songResearch = document.querySelector('.search-button-cs');
songResearch.addEventListener('mouseenter', overButton);
songResearch.addEventListener('mouseleave', leaveButton);
songResearch.addEventListener('click', searchSongs);

function closeViewSpotify(){
    const spotifyView = document.querySelector(".spotifyView-container");
    spotifyView.classList.add('hidden');
    return
}

const spotifyClose = document.querySelector(".close-spotify");
spotifyClose.addEventListener('click', closeViewSpotify);
spotifyClose.addEventListener('mouseenter', overButton);
spotifyClose.addEventListener('mouseleave', leaveButton);



function loadCurrencies(json){
    const currencyContainer = document.forms['currency_form'];
    for (let item of json){
        const option = document.createElement('button')
        option.type = 'submit';
        option.name = 'currency_selected'
        option.value = item.id;
        option.textContent = item.name +" "+"("+item.symbol+")";
        option.classList.add('currency_option');
        option.addEventListener('click', currencySelection);
        currencyContainer.appendChild(option);

    }
}

function addCurrencyOption(){
    fetch('getDbInfo.php?getCurrencies=1').then(onResponse, onError).then(loadCurrencies);
}


document.addEventListener('DOMContentLoaded', addCurrencyOption);


function openActivity(event){

    window.location.href = event.currentTarget.dataset.activityLink;

}

function visualizeActivities(json){
    const big_container = document.querySelector('.activities_container');
    big_container.innerHTML = "";
    console.log('Caricamento per sezione contenuti iniziato');
    if (json.length > 0){
        for (let item of json){
            const container = document.createElement('div');
            container.className = 'activity_container';
            container.dataset.activityLink = 'activity.php?activity_id='+item.id;
            container.dataset.activityId = item.id;
            container.addEventListener('click', openActivity);

            // Aggiungi l'immagine dell'attività
            const img = document.createElement('img');
            img.classList.add('activity_img');
            img.src = item.img_url;
            container.appendChild(img);

            // Crea il contenitore del like
            const likeContainer = document.createElement('div');
            likeContainer.classList.add('like_container');
            const likeIcon = document.createElement('img');
            likeIcon.src = (item.liked?'like_pressed.svg':'like_void.svg');
            likeIcon.classList.add('like-icon');
            if(item.liked){
                likeIcon.classList.add('liked');
            }
            likeIcon.addEventListener('click', likeClick);
            
            likeContainer.appendChild(likeIcon);
            container.appendChild(likeContainer);

            const orangeSep = document.createElement('div');
            orangeSep.classList.add('orange_sep')
            container.appendChild(orangeSep);

            const description = document.createElement('div');
            description.classList.add('activity_description');
            
            const activityType = document.createElement('span');
            activityType.classList.add('activity_type');
            activityType.textContent = item.activity_type;
            description.appendChild(activityType);

            const activityTitle = document.createElement('span');
            activityTitle.classList.add('activity_title');
            activityTitle.textContent = item.title;
            description.appendChild(activityTitle);

            const activityDuration = document.createElement('span');
            activityDuration.classList.add('activity_duration');
            activityDuration.textContent = item.duration;
            description.appendChild(activityDuration);

            const reviewContainer = document.createElement('div');
            reviewContainer.classList.add('activity_review');
            
            for (let i = 0; i < 5; i++) {
                const star = document.createElement('img');
                star.src = 'MEDIA/ICONS/star_review.svg';
                reviewContainer.appendChild(star);
            }

            const reviewScore = document.createElement('span');
            reviewScore.classList.add('act_review_score');
            reviewScore.textContent = item.avg_rating + "/5";
            reviewContainer.appendChild(reviewScore);

            description.appendChild(reviewContainer);



            const priceElement = document.createElement('span');
            priceElement.classList.add('activity_price');
            if(item.discount){
                const oldPriceText = document.createElement('span');
                oldPriceText.classList.add('price');
                oldPriceText.classList.add('old');
                oldPriceText.textContent = item.price + " ";
                priceElement.appendChild(oldPriceText);
                const newPriceText = document.createElement('span');
                newPriceText.classList.add('price');
                newPriceText.classList.add('new');
                newPriceText.textContent = item.discount + item.currency_symbol;
                priceElement.appendChild(newPriceText);
            }else{
                const priceText = document.createElement('span');
                priceText.classList.add('price');
                priceText.textContent = item.price + item.currency_symbol;
                priceElement.appendChild(priceText);
            }
            const textPrice = document.createElement('span');
            textPrice.textContent = " a persona.";
            priceElement.appendChild(textPrice);

            description.appendChild(priceElement);

            container.appendChild(description);
            big_container.appendChild(container);
        }
    }else{
        const voidSearch = document.createElement('span');
        voidSearch.textContent = "Nessun elemento corrispondente alla ricerca";
        big_container.appendChild(voidSearch);
    }
    console.log('Caricamento per sezione contenuti finito');
}
console.log(urlFields);


function loadSectionActivities(event){
    const button = event.target;
    urlFields['section'] = 1;
    if ('searchText'in urlFields){
        delete urlFields.searchText;
    }
    if(button != document){
        urlFields['section'] = button.dataset.menuCategory;
        console.log(urlFields);
        const title = document.querySelector('.bch-title[data-section="main"]');
        title.textContent = "Esperienze straordinarie nella sezione "+button.textContent;
    }
    // TODO
    const url = "getActivitiesForCustomers.php?section=" + encodeURIComponent(!('section' in urlFields)?1:urlFields.section);
    fetch(url, {method:'get'}).then(onResponse, onError).then(visualizeActivities);
    
}






document.addEventListener('DOMContentLoaded', loadSectionActivities);





let sessionId = 0;


function getSessionId() {
    const sessionInput = document.querySelector('#session_id');
    if (sessionInput) {
        sessionId = sessionInput.value;
        console.log('Session ID:', sessionId); // Stampa l'ID della sessione nella console
    } else {
        console.log('Utente non loggato');
    }
}

window.onload = getSessionId;



function handleSearchForm(event){
    event.preventDefault();
    console.log('Eseguendo la ricerca...')
    urlFields['searchText'] = event.currentTarget.searchText.value;
    if ('section'in urlFields){
        delete urlFields.section;
    }
    if (urlFields.searchText.length < 30){
        console.log(encodeURIComponent(urlFields.searchText))
        const url = 'getActivitiesForCustomers.php?searchText=' + encodeURIComponent(urlFields.searchText);
        document.querySelector('.bch-title[data-section="main"]').textContent = "Risultati della ricerca";
        fetch(url, {method:'get'}).then(onResponse, onError).then(visualizeActivities);
    }
}


const searchForm = document.forms['search_bar_form'];
searchForm.addEventListener('submit', handleSearchForm);


function checkLogin(event){
    event.preventDefault();
    if (sessionId < 1){
        openErrorModalView();
        return;
    }else{
        window.location.href = event.currentTarget.href;
    }
}




const menuCategories = document.querySelectorAll('.menu_item[data-access="loginOnly"]');
for (let menuItem of menuCategories){
    menuItem.addEventListener('click', checkLogin);
}
