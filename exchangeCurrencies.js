
function onResponse(response){
    return response.json();
}

function onError(error){
    console.log('Errore: ' + error);
}

function changePrices(json){
    const pricesContainer = document.querySelectorAll('.price');
    let prices= {};
    let i = 1;
    for (let price of pricesContainer){
        prices['price'+i]=price;
        i++;
    }
    i = 1;
    for (let priceExchange of json['prices']){
        if(prices['price'+i].classList.contains('old')){
            prices['price'+i].textContent = priceExchange
        }else{
            prices['price'+i].textContent = priceExchange + json.newCurrency;
        }
        i++
    }
    return;
}


function currencySelection(event){
    event.preventDefault();
    const button = event.currentTarget;
    for(let option of document.querySelectorAll('.currency_option')){
        option.classList.remove('selected');
    }
    button.classList.add('selected');
    const closeIcon = document.querySelector('.modal-view .icon-menu');
    closeIcon.click();
    let prices = {};
    const pricesContainer = document.querySelectorAll('.price');
    let i=1;
    for (let price of pricesContainer){
        prices['price'+i]=price.textContent.replace(/[^\d.,]/g, '');
        i++;
    }
    const formData = new FormData();
    formData.append('newCurrency', button.value);
    formData.append('prices', JSON.stringify(prices));

    fetch('APIS/exchangeCurrencies.php',{
        method : 'post',
        body: formData
    }).then(onResponse, onError).then(changePrices);
    return
}