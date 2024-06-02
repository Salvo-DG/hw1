
function emailCheck(){
    email = inputEmail.value;
    const emailStandard = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    const message = document.querySelector('.error_message[data-input="email"]')
    if(!emailStandard.test(email)){
        message.classList.remove('hidden');
        inputEmail.classList.remove('checked');
    }else{
        message.classList.add('hidden');
        inputEmail.classList.add('checked');
    }
}

function checkForm(event){
    let flag = false;
    event.preventDefault();
    let message = document.querySelector('.error_message[data-input="password"]');
    if (form.password.value.length === 0){
        flag = false;
        message.classList.remove('hidden');
    }else{
        flag = true;
        message.classList.add('hidden');
    }

    message = document.querySelector('.error_message[data-input="email"]');
    if (form.email.value.length === 0){
        message.classList.remove('hidden');
    }else{
        message.classList.add('hidden');
    }

    if (flag && inputEmail.classList.contains('checked')){
        form.submit();
    }

}


const form = document.forms['partnerLogin'];
form.addEventListener('submit', checkForm);

const inputEmail = document.querySelector('#email');
inputEmail.addEventListener('input', emailCheck);