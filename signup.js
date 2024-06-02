let validPassword = false;
let validEmail = false;


function onResponse(response){
    return response.json();
}

function onError(error){
    console.log('Errore: ' + error);
}



function jsonLoadCurrencies(json){
    const currencySelect = document.querySelector('#currency_selection');
    console.log(json);
    for (let item of json){
        const option = document.createElement('option')
        option.value = item.id;
        option.textContent = item.name +" "+"("+item.code+")";
        currencySelect.appendChild(option);
    }
}


function loadCurrencies(){
    fetch("getDbInfo.php?getCurrencies=1").then(onResponse, onError).then(jsonLoadCurrencies)
}

document.addEventListener('DOMContentLoaded', loadCurrencies);


function passwordCheck(){
    const message = document.querySelector('.error_message[data-input="password-input"]');
    message.classList.add('hidden');
    let password = passwordInput.value;
    const len_request = document.querySelector('.pass_check[data-request="length"]');
    validPassword = true;
    if (password.length >= 8 && password.length <= 30) {
        len_request.classList.remove('unvalid');
        len_request.classList.add('valid');
    }
    else{
        len_request.classList.remove('valid');
        len_request.classList.add('unvalid');
        validPassword = false;
    }

    const numSpecialCharPattern = /(?=.*\d)(?=.*[!@#$%^&*(),.?":{}|<>])/;
    const ns_request = document.querySelector('.pass_check[data-request="num_special_char"]');
    if (numSpecialCharPattern.test(password)) {
        ns_request.classList.remove('unvalid');
        ns_request.classList.add('valid');
    }
    else{
        ns_request.classList.add('unvalid');
        ns_request.classList.remove('valid');
        validPassword = false;
    }

    const upperLowerCharPattern = /(?=.*[a-z])(?=.*[A-Z])/;
    const UL_request = document.querySelector('.pass_check[data-request="UpperLower_char"]');
    if (upperLowerCharPattern.test(password)) {
        UL_request.classList.remove('unvalid');
        UL_request.classList.add('valid');
    }
    else{
        UL_request.classList.add('unvalid');
        UL_request.classList.remove('valid'); 
        validPassword = false;
    }

    const tab = ' ';
    const noTab_request =  document.querySelector('.pass_check[data-request="no_tab"]');
    if (password.includes(tab)){
        noTab_request.classList.add('unvalid');
        noTab_request.classList.remove('valid');
        validPassword = false;
    }else{
        noTab_request.classList.remove('unvalid');
        noTab_request.classList.add('valid');
    }

    if (validPassword){
        passwordInput.classList.add('checked');
    }else{
        passwordInput.classList.remove('checked');
    }
}


function emailCheck() {
    // Espressione regolare per la validazione dell'email
    email = emailInput.value;
    const emailStandard = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    message = document.querySelector('.error_message[data-input="email"]');
    validEmail = true;
    if (emailStandard.test(email)){
        message.classList.add('hidden');
        emailInput.classList.add('checked');
    }else{
        message.classList.remove('hidden');
        emailInput.classList.remove('checked');
        validEmail = false;
    }
}

const passwordInput = document.querySelector("#password");
passwordInput.addEventListener('input', passwordCheck);

const emailInput = document.querySelector('#email');
emailInput.addEventListener('input', emailCheck);


function formValidation(event){
    event.preventDefault();
    let flag=true;
    // controllo valuta

    const valueSelect = document.querySelector('#currency_selection');
    const valuta = valueSelect.value;
    message = document.querySelector('.error_message[data-input="currency-selection"]');
    if (!valuta){
        flag=false;
        message.classList.remove('hidden');
    }else{
        message.classList.add('hidden');
    }

    // controllo nome
    const name = document.querySelector('#name').value;
   
    message = document.querySelector('.error_message[data-input="name-input"]');
    if (name.length === 0){
        flag = false;
        message.classList.remove('hidden');
    }else{
        message.classList.add('hidden');
    }

    //controllo cognome
    const surname = document.querySelector('#surname').value;
   
    message = document.querySelector('.error_message[data-input="surname-input"]');
    if (surname.length === 0){
        flag = false;
        message.classList.remove('hidden');
    }else{
        message.classList.add('hidden');
    }

    // controllo email
    message = document.querySelector('.error_message[data-input="email"]');
    if (!validEmail){
        flag = false;
        message.classList.remove('hidden');
    }else{
        message.classList.add('hidden');
    }

    // controllo password
    message = document.querySelector('.error_message[data-input="password-input"]');
    if (!validPassword){
        flag = false;
        message.classList.remove('hidden');
    }else{
        message.classList.add('hidden');
    }

    // controllo checkbox
    const terms = document.querySelector('#terms').checked;
    message = document.querySelector('.error_message[data-input="term"]');
    if(!terms){
        flag = false;
        message.classList.remove('hidden');
    }else{
        message.classList.add('hidden');
    }
    console.log("email: "+validEmail +" pwd: "+ validPassword)
    console.log(flag);
    if (flag){
        form.submit();
    }
}





const form = document.forms['signUp_customer'];
form.addEventListener('submit', formValidation)

