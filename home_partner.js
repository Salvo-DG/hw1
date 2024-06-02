
function showAccountMenu(){
    document.querySelector('.dropdw_big_container').classList.remove('hidden');
    return
}

function hideAccountMenu(){
    document.querySelector('.dropdw_big_container').classList.add('hidden');
    return
}


function clickOnExternalLink(event){
    const link = event.currentTarget.dataset.link;
    window.location.href=link;

}


const profileItem = document.querySelector('.nav_item[data-menu="account"]')
profileItem.addEventListener('click', showAccountMenu);
profileItem.addEventListener('mouseover', showAccountMenu);
profileItem.addEventListener('mouseout', hideAccountMenu);


const addActivityButton = document.querySelector('.nav_item[data-link="add_activity.php"]');
addActivityButton.addEventListener('click', clickOnExternalLink);


function onResponse(response){
    return response.json();
}

function onError(error){
    console.log('Errore: ' + error);
}

function openActivity(event){
    window.location.href = event.currentTarget.dataset.activityLink;
}


function jsonLoadActivities(json){
    const bigContainer = document.querySelector('.activities_container');
    if(json[0]){
        console.log(json);
        for (let activity of json){
            // Crea un elemento div per rappresentare un'attività
            const activityElement = document.createElement('div');
            activityElement.classList.add('activity_container');
            activityElement.dataset.id = activity['id'];

            // Crea un elemento div per la descrizione dell'attività
            const desElement = document.createElement('div');
            desElement.dataset.activityLink = 'activity.php?activity_id='+activity.id;
            desElement.classList.add('activity_des');
            desElement.addEventListener('click', openActivity);

            // Crea l'elemento img per l'immagine dell'attività
            const imgElement = document.createElement('img');
            imgElement.classList.add('img_activity');
            imgElement.src = activity.img_url;
            desElement.appendChild(imgElement);

            // Crea un elemento div per la linea arancione
            const orangeSepElement = document.createElement('div');
            orangeSepElement.classList.add('orange_sep');
            desElement.appendChild(orangeSepElement);

            // Crea un elemento div per il testo della descrizione dell'attività
            const textDesElement = document.createElement('div');
            textDesElement.classList.add('activity_text_des');

            // Aggiungi il tipo di attività
            const activityTypeElement = document.createElement('span');
            activityTypeElement.classList.add('activity_type');
            activityTypeElement.innerText = activity.activity_type;
            textDesElement.appendChild(activityTypeElement);

            // Aggiungi il titolo dell'attività
            const titleElement = document.createElement('span');
            titleElement.classList.add('activity_title');
            titleElement.innerText = activity.title;
            textDesElement.appendChild(titleElement);

            // Aggiungi la durata dell'attività
            const durationElement = document.createElement('span');
            durationElement.classList.add('activity_duration');
            durationElement.innerText = activity.duration;
            textDesElement.appendChild(durationElement);

            // Aggiungi il prezzo dell'attività
            const priceElement = document.createElement('span');
            priceElement.classList.add('activity_price');
            if(activity.discount){
                console.log('ciao');
                const oldPriceText = document.createElement('span');
                oldPriceText.classList.add('price');
                oldPriceText.classList.add('old');
                oldPriceText.textContent = activity.price + " ";
                priceElement.appendChild(oldPriceText);
                const newPriceText = document.createElement('span');
                newPriceText.classList.add('price');
                newPriceText.classList.add('new');
                newPriceText.textContent = activity.discount + currency;
                priceElement.appendChild(newPriceText);
            }else{
                priceElement.textContent = activity.price + currency;
            }
            const textPrice = document.createElement('span');
            textPrice.textContent = " a persona.";
            priceElement.appendChild(textPrice);
            //priceElement.textContent += " a persona";
            textDesElement.appendChild(priceElement);

            // Aggiungi il testo della descrizione all'elemento della descrizione
            desElement.appendChild(textDesElement);

            // Aggiungi l'elemento della descrizione all'elemento dell'attività
            activityElement.appendChild(desElement);



            const formsContainer = document.createElement('div');
            formsContainer.classList.add('forms_container');

            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'activity_id';
            hiddenInput.value = activity['id'];

            const discountApplyForm = document.createElement('form');
            discountApplyForm.name = 'discount_apply';
            discountApplyForm.method = 'get';
            discountApplyForm.action = '';
            discountApplyForm.appendChild(hiddenInput);

            const discountApplyContainer = document.createElement('div');
            discountApplyContainer.classList.add('input_container');
            //discountApplyContainer.appendChild(hiddenInput);
            const discountApplyLabel = document.createElement('label');
            discountApplyLabel.for = 'discount';
            discountApplyLabel.innerText = 'Applica o modifica sconto';

            const discountApplyInput = document.createElement('input');
            discountApplyInput.type = 'number';
            discountApplyInput.classList.add('discount', 'number_input');
            discountApplyInput.name = 'discount';
            discountApplyInput.min = '0.00';
            discountApplyInput.max = activity.price;
            discountApplyInput.step = '0.01';
            discountApplyInput.placeholder = Math.floor((activity.discount?activity.discount:activity.price)- 0.2*(activity.discount?activity.discount:activity.price));

            const discountApplyError = document.createElement('span');
            discountApplyError.classList.add('price_error', 'hidden');
            discountApplyError.innerText = 'Il prezzo deve essere inferiore a quello impostato inizialmente';

            const submitDiscountApply = document.createElement('input');
            submitDiscountApply.type = 'submit';
            submitDiscountApply.classList.add('submit_discount');
            submitDiscountApply.name = 'Applica';
            submitDiscountApply.value = 'Applica';

            discountApplyContainer.appendChild(discountApplyLabel);
            discountApplyContainer.appendChild(discountApplyInput);
            discountApplyContainer.appendChild(discountApplyError);

            discountApplyForm.appendChild(discountApplyContainer);
            discountApplyForm.appendChild(submitDiscountApply);

            formsContainer.appendChild(discountApplyForm);
            // Crea il form per l'eliminazione dell'attività
            const deleteActivityForm = document.createElement('form');
            deleteActivityForm.name = 'del_activity';
            deleteActivityForm.method = 'get';
            deleteActivityForm.action = 'delete.php';

            const deleteActivityLabel = document.createElement('label');
            deleteActivityLabel.for = 'delete_activity';
            deleteActivityLabel.innerText = 'Elimina attività';

            const deleteActivityButton = document.createElement('input');
            deleteActivityButton.type = 'submit';
            deleteActivityButton.classList.add('delete_activity');
            deleteActivityButton.name = 'Elimina';
            deleteActivityButton.value = 'Elimina';

            const hiddenInput2 = document.createElement('input');
            hiddenInput2.type = 'hidden';
            hiddenInput2.name = 'activity_id';
            hiddenInput2.value = activity['id'];


            deleteActivityForm.appendChild(deleteActivityLabel);
            deleteActivityForm.appendChild(deleteActivityButton);
            deleteActivityForm.appendChild(hiddenInput2);

            formsContainer.appendChild(deleteActivityForm);
            // Aggiungi l'elemento dell'attività al documento
            activityElement.appendChild(formsContainer);
            bigContainer.appendChild(activityElement);
        }
    }else{
        document.querySelector('.no_activity').classList.remove('hidden');
    }
}


function loadActivities(){

    fetch("getActivitiesByCompany.php").then(onResponse, onError).then(jsonLoadActivities);
}





const currency = document.querySelector('#currency_input').value;


document.addEventListener('DOMContentLoaded', loadActivities);

function checkDiscountForm(event){
    event.preventDefault();
    form = event.currentTarget;
    discount = form.querySelector('.discount');
    if (discount.value < discount.max){
        form.submit();
    }
}

if (document.forms['discount_apply']){
    const discountApplies = document.forms['discount_apply'];
    if (Array.isArray(discountApplies)){
        for (let form_discount of discountApplies){
            form_discount.addEventListener('submit', checkDiscountForm);
        }
    }
    else{
        discountApplies.addEventListener('submit', checkDiscountForm);
    }
}




const addButton = document.querySelector('.add_button');
addButton.addEventListener('click', clickOnExternalLink);



