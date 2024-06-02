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

const userType = document.querySelector('#user_type').value;


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

function openErrorModalView(){
    document.body.classList.add('no-scroll');
    const errorView = document.querySelector('.modal-view.error_view');
    errorView.style.top = window.scrollY +'px';
    errorView.classList.remove('hidden');
    return;
}
function closeModalView(event){
    document.body.classList.remove('no-scroll');
    event.currentTarget.closest('.modal-view').classList.add('hidden');
}

const closeIcons = document.querySelectorAll('.modal-view .icon-menu');
for (let closeIcon of closeIcons){
    closeIcon.addEventListener('click', closeModalView);
}

// Validazione dei form

function checkNameSurname(event){

        event.preventDefault();

        const nameInput = document.querySelector('#name');
        const name = nameInput.value.trim();
        const surnameInput = document.querySelector('#surname');
        const surname = surnameInput.value.trim()
;
        let isValid = true;
        let error;

        if (name === '') {
            error = nameInput.closest('.small_input_container').querySelector('.error_input');
            error.textContent = 'Inserire nome';
            error.classList.remove('hidden');
            isValid = false;
        }

        if (surname === '') {
            error = surnameInput.closest('.small_input_container').querySelector('.error_input');
            error.textContent = 'Inserire cognome';
            error.classList.remove('hidden');
            isValid = false;
        }
        if (isValid) {
            event.currentTarget.submit();
        }

}
const nameAndSurnameForm = document.forms['nameSurname'];
nameAndSurnameForm.addEventListener('submit', checkNameSurname);



function checkEmail(event) {
    event.preventDefault();

    let error = event.currentTarget.querySelector('.error_input');
    error.classList.add('hidden');

    const emailInput = document.querySelector('#email');
    let email = emailInput.value.trim();

    let isValid = true;
    const emailStandard = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (!emailStandard.test(email)) {
        error.textContent = 'Inserire una mail valida nel formato mariorossi@gmail.com'
        error.classList.remove('hidden');
        isValid = false;
    }

    if (isValid) {
        event.currentTarget.submit();
    }
}

const emailForm = document.forms['email'];
emailForm.addEventListener('submit', checkEmail);


function checkCurrencySelection(event) {
    event.preventDefault();

    let error = event.currentTarget.querySelector('.error_input');
    error.classList.add('hidden');

    const selectedCurrencyInput = event.currentTarget.querySelector('#currency_select');
    const selectedCurrency = selectedCurrencyInput.value;

    let isValid = true;

    if (selectedCurrency === '') {
        error.textContent = 'Selezionare una valuta';
        error.classList.remove('hidden'); 
        isValid = false;
    }

    if (isValid) {
        event.currentTarget.submit();
    }
}

const currencyForm = document.forms['currency'];
currencyForm.addEventListener('submit', checkCurrencySelection);




function loadCurrencies(json){
    const currencyForm = document.forms['currency'];
    const currencySelect = currencyForm.querySelector('#currency_select');
    for (let item of json){
        const option = document.createElement('option')
        option.value = item.id;
        option.textContent = item.name +" "+"("+item.symbol+")";
        option.classList.add('currency_option');
        currencySelect.appendChild(option);
    }
}

function addCurrencyOption(){
    fetch('getDbInfo.php?getCurrencies=1').then(onResponse, onError).then(loadCurrencies);
}


document.addEventListener('DOMContentLoaded', addCurrencyOption);



let validPassword = false;


function passwordCheck(event){
    const container = event.currentTarget.closest('.small_input_container');
    let error = container.querySelector('.error_input');
    error.classList.add('hidden');


    let password = passwordInput.value;

    const len_request = container.querySelector('.pass_check[data-request="length"]');
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
    const ns_request = container.querySelector('.pass_check[data-request="num_special_char"]');
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
    const UL_request = container.querySelector('.pass_check[data-request="UpperLower_char"]');
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
    const noTab_request =  container.querySelector('.pass_check[data-request="no_tab"]');
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

const passwordInput = document.querySelector("#newPwd");
passwordInput.addEventListener('input', passwordCheck);




function checkPwdForm(event){
    event.preventDefault();

    const errors = pwdForm.querySelectorAll('.error_input');
    for (let error of errors){
        error.classList.add('hidden');
    }

    const oldPwdContainer = pwdForm.querySelector('#oldPwd');
    const oldPwd = oldPwdContainer.value;
    let error = oldPwdContainer.closest('.small_input_container').querySelector('.error_input');
    let valid = true;
    if(oldPwd == ''){
        error.textContent = 'Compilare questo campo.'
        error.classList.remove('hidden');
        valid = false;
    }
    const newPwdContainer = pwdForm.querySelector('#newPwd');
    const newPwd = oldPwdContainer.value;
    error = newPwdContainer.closest('.small_input_container').querySelector('.error_input');
    if(!validPassword){
        error.textContent = 'Rispettare tutti i seguenti criteri:';
        error.classList.remove('hidden');
        valid = false;
    }

    if(valid){
        event.currentTarget.submit();
    }


 }



const pwdForm = document.forms['password'];
pwdForm.addEventListener('submit', checkPwdForm);