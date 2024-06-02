// Codice per comportamento header



function onResponse(response){
    return response.json();
}

function onError(error){
    console.log('Errore: ' + error);
}


function showAccountMenu(){
    document.querySelector('.dropdw_big_container').classList.remove('hidden');
    return
}

function hideAccountMenu(){
    document.querySelector('.dropdw_big_container').classList.add('hidden');
    return
}


const profileItem = document.querySelector('.nav_item[data-menu="account"]')
profileItem.addEventListener('click', showAccountMenu);
profileItem.addEventListener('mouseover', showAccountMenu);
profileItem.addEventListener('mouseout', hideAccountMenu);


function clickOnInnerLink(event){
    const link = event.currentTarget.dataset.link;
    window.location.href=link;

}


const menuButtons = document.querySelectorAll('.nav_item');
for (let menuButton of menuButtons){
    menuButton.addEventListener('click', clickOnInnerLink);
}


function loadCurrencies(json){
    const currencyForm = document.forms['currency_form'];
    for (let item of json){
        const option = document.createElement('button')
        option.type = 'submit';
        option.name = 'currency_selected'
        option.value = item.id;
        option.textContent = item.name +" "+"("+item.symbol+")";
        option.classList.add('currency_option');
        option.addEventListener('click', currencySelection);
        currencyForm.appendChild(option);
    }
}

function addCurrencyOption(){
    fetch('getDbInfo.php?getCurrencies=1').then(onResponse, onError).then(loadCurrencies);
}


document.addEventListener('DOMContentLoaded', addCurrencyOption);


function openCurrencyView(event){
    event.preventDefault();
    document.body.classList.add('no-scroll');
    const currencyView = document.querySelector('.modal-view[data-ref="currency-choose"]');
    currencyView.style.top = window.scrollY +'px';
    currencyView.classList.remove('hidden');
}

const currencyButtonMenu = document.querySelector(".dropdown_item_container[data-item=currency]");
currencyButtonMenu.addEventListener('click', openCurrencyView);


function closeModalView(event){
    document.body.classList.remove('no-scroll');
    event.currentTarget.closest('.modal-view').classList.add('hidden');
}

const closeIcon = document.querySelector('.modal-view .icon-menu');
closeIcon.addEventListener('click', closeModalView);




// ------------------------------------------------------------------------------------------------------






function loadFavorites(){
    const url = "getActivitiesForCustomers.php?getFavorites=" + encodeURIComponent('yes');
    fetch(url, {method:'get'}).then(onResponse, onError).then(visualizeActivities);
}

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
        voidSearch.textContent = "Non hai ancora nulla nei tuoi preferiti";
        big_container.appendChild(voidSearch);
    }
    console.log('Caricamento per sezione contenuti finito');
}

function likeClick(event){
    event.preventDefault();
    event.stopPropagation();
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


document.addEventListener('DOMContentLoaded', loadFavorites);



