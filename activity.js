function onResponse(response){
    return response.json();
}

function onError(error){
    console.log('Errore: ' + error);
}

function clickLike(event){
    event.preventDefault();
    event.stopPropagation();
    if (userType == "C"){
        const like = event.currentTarget;
        const text_like = document.querySelector('.des_like');
        if (like.classList.contains('liked')){
            text_like.textContent = 'Aggiungi ai preferiti';
            like.classList.remove('liked');
            like.src = 'like_void.svg';
            url = "handleLike.php?action="+encodeURIComponent('Unlike')+"&activity="+encodeURIComponent(activityId);
        }
        else {
            like.classList.add('liked');
            text_like.textContent = 'Rimuovi dai preferiti';
            like.src = 'like_pressed.svg';
            url = "handleLike.php?action="+encodeURIComponent('Like')+"&activity="+encodeURIComponent(activityId);
        }
        fetch(url, {method:'get'});
    }else{
        openErrorModalView();
        return;
    }

}

function addReview(event){
    event.preventDefault();
    if (userType == 'C'){
        const form = event.currentTarget;
        let valid = true;
        const inputScore = form.querySelector('#scoreReview').value;
        const inputText = form.querySelector('#reviewText').value;

        if (inputScore >5 || inputScore < 0 || inputScore == ''){
            form.querySelector('.error_message[data-input="scoreReview"]').classList.remove('hidden');
            valid = false;
        }
        if (inputText.length == 0 || inputText.length > 990){
            form.querySelector('.error_message[data-input="textReview"]').classList.remove('hidden');
            valid = false;
        }
        if(valid){
            form.submit();
        }
    }
    else{
        openErrorModalView();
        return;
    }


}

function deleteReview(event){
    event.currentTarget.classList.add('hidden');
    const review = document.querySelector('.reviewContainer[personalReview="yes"]');
    review.classList.add('hidden');

    const container = document.querySelector('#activityContainer');
    const reviewFormSection = document.createElement('div');
    reviewFormSection.classList.add('subSection');

    const reviewFormTitle = document.createElement('h2');
    reviewFormTitle.classList.add('title_sec');
    reviewFormTitle.textContent = 'Lascia una recensione';

    const reviewForm = document.createElement('form');
    reviewForm.id = 'reviewForm';
    reviewForm.name = 'reviewForm';
    reviewForm.action = 'addReview.php';
    reviewForm.method = 'post';
    reviewForm.addEventListener('submit', addReview);

    const inputHidden = document.createElement('input');
    inputHidden.type = 'hidden';
    inputHidden.name = 'activity_id';
    inputHidden.value = activityId;

    const labelScore = document.createElement('label');
    labelScore.htmlFor = 'scoreReview';
    labelScore.textContent = 'Valutazione da 0 a 5';

    const inputScore = document.createElement('input');
    inputScore.type = 'number';
    inputScore.name = 'score';
    inputScore.min = '0';
    inputScore.max = '5';
    inputScore.step = '0.1';
    inputScore.id = 'scoreReview';
    inputScore.placeholder = '4.5';

    const error_msg1 = document.createElement('span');
    error_msg1.textContent = "Inserire un valore compreso tra 0 e 5";
    error_msg1.classList.add('error_message', 'hidden');
    error_msg1.setAttribute('data-input', 'scoreReview');

    const labelReviewText = document.createElement('label');
    labelReviewText.htmlFor = 'reviewText';
    labelReviewText.textContent = 'Recensione';

    const inputReviewText = document.createElement('input');
    inputReviewText.type = 'text';
    inputReviewText.name = 'reviewText';
    inputReviewText.maxLength = '990';
    inputReviewText.id = 'reviewText';
    inputReviewText.placeholder = 'Un\'esperienza fantastica';

    const error_msg2 = document.createElement('span');
    error_msg2.textContent = "Compilare il campo con meno di 990 caratteri";
    error_msg2.classList.add('error_message', 'hidden');
    error_msg2.setAttribute('data-input', 'textReview');


    const submitButton = document.createElement('input');
    submitButton.type = 'submit';
    submitButton.name = 'submitForm';
    submitButton.value = 'Aggiungi la tua recensione';

    reviewForm.appendChild(inputHidden);
    reviewForm.appendChild(labelScore);
    reviewForm.appendChild(inputScore);
    reviewForm.appendChild(error_msg1);
    reviewForm.appendChild(labelReviewText);
    reviewForm.appendChild(inputReviewText);
    reviewForm.appendChild(error_msg2);
    reviewForm.appendChild(submitButton);

    reviewFormSection.appendChild(reviewFormTitle);
    reviewFormSection.appendChild(reviewForm);


    container.appendChild(reviewFormSection);


    fetch('delete.php?review='+activityId);
}
function openErrorModalView(){
    document.body.classList.add('no-scroll');
    const errorView = document.querySelector('.modal-view.error_view');
    errorView.style.top = window.scrollY +'px';
    errorView.classList.remove('hidden');
    return;
}

const activityId = document.querySelector('#activity_id').value;
const userType = document.querySelector('#user_type').value;


function loadActivity(){
    fetch('getActivity.php?activity_id='+activityId).then(onResponse, onError).then(showActivity);
}

function showActivity(activity){
        // Ottenere il container dell'attività
        console.log(activity);
        let personalReview = 0;
        const container = document.querySelector('#activityContainer');
        container.innerHTML = '';  // Pulire il container
    
        // Creare l'header dell'attività
        const header = document.createElement('div');
        header.classList.add('activity_header');
    
        const place = document.createElement('span');
        place.id = 'place';
        place.textContent = activity.city;
    
        const activityType = document.createElement('span');
        activityType.id = 'activity_type';
        activityType.textContent = activity.type;
    
        const title = document.createElement('h1');
        title.id = 'title';
        title.textContent = activity.title;
    
        header.appendChild(place);
        header.appendChild(activityType);
        header.appendChild(title);
    
        // Creare il div delle descrizioni
        const actHeaderDes = document.createElement('div');
        actHeaderDes.classList.add('act_header_des');
    
        const reviewsAndOperatorContainer = document.createElement('div');
        reviewsAndOperatorContainer.classList.add('reviewsAndOperator_container');
    
        // Aggiungere punteggio recensioni
        const reviewScoreContainer = document.createElement('div');
        reviewScoreContainer.classList.add('reviewScoreContainer');
    
        const scoreReviewStars = document.createElement('div');
        scoreReviewStars.classList.add('scoreReviewStars');
        for (let i = 0; i < 5; i++) {
            const star = document.createElement('img');
            star.src = 'MEDIA/ICONS/star_review.svg';
            scoreReviewStars.appendChild(star);
        }
    
        const scoreNum = document.createElement('span');
        scoreNum.classList.add('scoreNum');
        scoreNum.textContent = activity.reviews.info.avg_score + "/5";
    
        reviewScoreContainer.appendChild(scoreReviewStars);
        reviewScoreContainer.appendChild(scoreNum);
    
        const numReview = document.createElement('span');
        numReview.classList.add('numReview');
        numReview.textContent = activity.reviews.info.num +' recensioni';
    
        const operator = document.createElement('span');
        operator.id = 'Operator';
        operator.textContent = 'Fornitore dell\'attività: ' + activity.operator;
    
        reviewsAndOperatorContainer.appendChild(reviewScoreContainer);
        reviewsAndOperatorContainer.appendChild(numReview);
        reviewsAndOperatorContainer.appendChild(operator);
    
        const likeContainer = document.createElement('div');
        likeContainer.classList.add('like_container');
    
        const iconContainer = document.createElement('div');
        iconContainer.classList.add('icon_container');
    
        const likeIcon = document.createElement('img');
        likeIcon.classList.add('like-icon');
        likeIcon.src = (activity.liked?'like_pressed.svg':'like_void.svg');
        likeIcon.addEventListener('click', clickLike);

        iconContainer.appendChild(likeIcon);
        likeContainer.appendChild(iconContainer);
    
        const desLike = document.createElement('span');
        desLike.classList.add('des_like');
        if(activity.liked){
            likeIcon.classList.add('liked');
            desLike.textContent = 'Rimuovi dai preferiti';
        }else{
            desLike.textContent = 'Aggiungi ai preferiti';
        }
    
        likeContainer.appendChild(desLike);
        actHeaderDes.appendChild(reviewsAndOperatorContainer);
        actHeaderDes.appendChild(likeContainer);
        header.appendChild(actHeaderDes);
        

        // Creare l'immagine dell'attività
        const imgActivity = document.createElement('img');
        imgActivity.classList.add('img_activity');
        imgActivity.src = activity.img;
    
        // Creare la descrizione breve
        const shortDes = document.createElement('span');
        shortDes.classList.add('short_des');
        shortDes.textContent = activity.short_des;
    
        // Creare la sezione del prezzo
        const priceSection = document.createElement('div');
        priceSection.classList.add('subSection');
        priceSection.setAttribute('data-content', 'price');
    
        const priceTitle = document.createElement('h2');
        priceTitle.classList.add('title_sec');
        priceTitle.textContent = 'Prezzo del tour';
    
        const activityPrice = document.createElement('span');
        activityPrice.classList.add('activity_price');
        if(activity.discount){
            const oldPriceText = document.createElement('span');
            oldPriceText.classList.add('price');
            oldPriceText.classList.add('old');
            oldPriceText.textContent = activity.price + " ";
            activityPrice.appendChild(oldPriceText);
            const newPriceText = document.createElement('span');
            newPriceText.classList.add('price');
            newPriceText.classList.add('new');
            newPriceText.textContent = activity.discount + activity.currency_symbol;
            activityPrice.appendChild(newPriceText);
        }else{
            const priceText = document.createElement('span');
            priceText.classList.add('price');
            priceText.textContent = activity.price + activity.currency_symbol;
            activityPrice.appendChild(priceText);
        }
        const textPrice = document.createElement('span');
        textPrice.textContent = " a persona.";
        activityPrice.appendChild(textPrice);
    
        priceSection.appendChild(priceTitle);
        priceSection.appendChild(activityPrice);
    
        // Creare la sezione delle informazioni principali
        const mainInfoSection = document.createElement('div');
        mainInfoSection.classList.add('subSection');
    
        const mainInfoContainer = document.createElement('div');
        mainInfoContainer.classList.add('info_container');
        mainInfoContainer.setAttribute('data-content', 'main-info');
    
        const mainInfoTitle = document.createElement('div');
        mainInfoTitle.classList.add('titleSection');
        mainInfoTitle.textContent = 'Informazioni principali';
    
        const mainInfoTextSection = document.createElement('div');
        mainInfoTextSection.classList.add('textSection');
    
       for(let info of activity.infos){
            const infoSpan = document.createElement('span');
            infoSpan.classList.add('info');
            infoSpan.textContent = info;
            mainInfoTextSection.appendChild(infoSpan);
        }
    
        mainInfoContainer.appendChild(mainInfoTitle);
        mainInfoContainer.appendChild(mainInfoTextSection);
        mainInfoSection.appendChild(mainInfoContainer);
    
        // Creare la sezione della descrizione completa
        const longDesContainer = document.createElement('div');
        longDesContainer.classList.add('info_container');
        longDesContainer.setAttribute('data-content', 'long-des');
    
        const longDesTitle = document.createElement('div');
        longDesTitle.classList.add('titleSection');
        longDesTitle.textContent = 'Descrizione completa';
    
        const longDesTextSection = document.createElement('div');
        longDesTextSection.classList.add('textSection');
    
        const longDesSpan = document.createElement('span');
        longDesSpan.textContent = activity.long_des;
    
        longDesTextSection.appendChild(longDesSpan);
        longDesContainer.appendChild(longDesTitle);
        longDesContainer.appendChild(longDesTextSection);
        mainInfoSection.appendChild(longDesContainer);
    
        // Appendere tutte le sezioni al container principale
        container.appendChild(header);
        container.appendChild(imgActivity);
        container.appendChild(shortDes);
        container.appendChild(priceSection);
        container.appendChild(mainInfoSection);
    
        // Creare la sezione delle recensioni
        const reviewsSection = document.createElement('div');
        reviewsSection.classList.add('subSection');
    
        const reviewsTitle = document.createElement('h2');
        reviewsTitle.classList.add('title_sec');
        reviewsTitle.textContent = 'Recensioni';
        reviewsSection.appendChild(reviewsTitle);
        if(activity.reviews.info.num > 0){
    
            const reviewsContainer = document.createElement('div');
            reviewsContainer.classList.add('Recensioni');
        
            const reviewBigContainer = document.createElement('div');
            reviewBigContainer.classList.add('reviewBigContainer');
        
            for(let review of activity.reviews.reviews){
                const reviewContainer = document.createElement('div');
                reviewContainer.classList.add('reviewContainer');
        
                const headerReview = document.createElement('div');
                headerReview.classList.add('header_review');
        
                const scoreSpan = document.createElement('span');
                scoreSpan.classList.add('score');
                scoreSpan.textContent = review.score;
        
                const userSpan = document.createElement('span');
                userSpan.classList.add('user');
                userSpan.textContent = review.username;
        
                const reviewText = document.createElement('span');
                reviewText.classList.add('reviewText');
                reviewText.textContent = review.text;
        
                if(review.personal){
                    personalReview = review.id;
                    reviewContainer.setAttribute('personalReview', 'yes');
                }

                headerReview.appendChild(scoreSpan);
                headerReview.appendChild(userSpan);
                reviewContainer.appendChild(headerReview);
                reviewContainer.appendChild(reviewText);
                reviewBigContainer.appendChild(reviewContainer);
            }
        
            reviewsContainer.appendChild(reviewBigContainer);
            
            reviewsSection.appendChild(reviewsContainer);
        }else{
            const noReview = document.createElement('span');
                noReview.textContent = 'Nessuna recensione per questa attività';
                reviewsSection.appendChild(noReview);
        }

        container.appendChild(reviewsSection);
    
        // Creare la sezione per lasciare una recensione
        if (!activity.reviews.info.reviewed){
            const reviewFormSection = document.createElement('div');
            reviewFormSection.classList.add('subSection');
        
            const reviewFormTitle = document.createElement('h2');
            reviewFormTitle.classList.add('title_sec');
            reviewFormTitle.textContent = 'Lascia una recensione';
        
            const reviewForm = document.createElement('form');
            reviewForm.id = 'reviewForm';
            reviewForm.name = 'reviewForm';
            reviewForm.action = 'addReview.php';
            reviewForm.method = 'post';
            reviewForm.addEventListener('submit', addReview);
        
            const inputHidden = document.createElement('input');
            inputHidden.type = 'hidden';
            inputHidden.name = 'activity_id';
            inputHidden.value = activity.id;

            const labelScore = document.createElement('label');
            labelScore.htmlFor = 'scoreReview';
            labelScore.textContent = 'Valutazione da 0 a 5';
        
            const inputScore = document.createElement('input');
            inputScore.type = 'number';
            inputScore.name = 'score';
            inputScore.min = '0';
            inputScore.max = '5';
            inputScore.step = '0.1';
            inputScore.id = 'scoreReview';
            inputScore.placeholder = '4.5';

            const error_msg1 = document.createElement('span');
            error_msg1.textContent = "Inserire un valore compreso tra 0 e 5";
            error_msg1.classList.add('error_message', 'hidden');
            error_msg1.setAttribute('data-input', 'scoreReview');
        
            const labelReviewText = document.createElement('label');
            labelReviewText.htmlFor = 'reviewText';
            labelReviewText.textContent = 'Recensione';
        
            const inputReviewText = document.createElement('input');
            inputReviewText.type = 'text';
            inputReviewText.name = 'reviewText';
            inputReviewText.maxLength = '990';
            inputReviewText.id = 'reviewText';
            inputReviewText.placeholder = 'Un\'esperienza fantastica';
        
            const error_msg2 = document.createElement('span');
            error_msg2.textContent = "Compilare il campo con meno di 990 caratteri";
            error_msg2.classList.add('error_message', 'hidden');
            error_msg2.setAttribute('data-input', 'textReview');


            const submitButton = document.createElement('input');
            submitButton.type = 'submit';
            submitButton.name = 'submitForm';
            submitButton.value = 'Aggiungi la tua recensione';
        
            reviewForm.appendChild(inputHidden);
            reviewForm.appendChild(labelScore);
            reviewForm.appendChild(inputScore);
            reviewForm.appendChild(error_msg1);
            reviewForm.appendChild(labelReviewText);
            reviewForm.appendChild(inputReviewText);
            reviewForm.appendChild(error_msg2);
            reviewForm.appendChild(submitButton);
        
            reviewFormSection.appendChild(reviewFormTitle);
            reviewFormSection.appendChild(reviewForm);
            container.appendChild(reviewFormSection);
        }else{
            const deleteReviewButton = document.createElement('div');
            deleteReviewButton.classList.add('deleteReview');
            deleteReviewButton.textContent = 'Elimina la tua recensione';
            deleteReviewButton.addEventListener('click', deleteReview);
            container.appendChild(deleteReviewButton);

        }
    }



window.addEventListener('DOMContentLoaded', loadActivity);




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
    if(event.currentTarget.dataset.menu == 'favorites'){
        if(userType != 'C'){
            openErrorModalView();
            return;
        }
    }

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


window.addEventListener('DOMContentLoaded', addCurrencyOption);


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

const closeIcons = document.querySelectorAll('.modal-view .icon-menu');
for (let closeIcon of closeIcons){
    closeIcon.addEventListener('click', closeModalView);
}




