

function onResponse(response){
    return response.json();
}

function onError(error){
    console.log('Errore: ' + error);
}

function jsonLoadImages(json){
    for (let item of json){
        const option = document.createElement('option')
        option.value = item.id;
        option.textContent = "Immagine di: " + item.img_description;
        imageSelect.appendChild(option);
    }
}

function jsonLoadTypes(json){
    for (let item of json){
        const option = document.createElement('option')
        option.value = item.id;
        option.textContent = item.activity;
        typeSelect.appendChild(option);
    }
}

function jsonLoadSections(json){
    for (let item of json){
        const option = document.createElement('option')
        option.value = item.id;
        option.textContent = item.sectionName;
        sectionSelect.appendChild(option);
    }
}


function optionsHours(){
    for (let i = 0; i <= 16; i++) {
        let option = document.createElement('option');
        option.value = i;
        option.textContent = i + (i === 1 ? " ora" : " ore");
        oreSelect.appendChild(option);
    }
}

function optionsMinutes(){
    for (let i = 0; i <= 55; i+= 5) {
        let option = document.createElement('option');
        option.value = i;
        option.textContent = i + (i === 1 ? " minuto" : " minuti");
        minutiSelect.appendChild(option);
    }
}

function loadImages(){
    fetch('getDbInfo.php?getImages=1').then(onResponse, onError).then(jsonLoadImages);

}

function loadTypes(){
    fetch('getDbInfo.php?getActivityTypes=1').then(onResponse, onError).then(jsonLoadTypes);

}

function loadSections(){
    fetch('getDbInfo.php?getSections=1').then(onResponse, onError).then(jsonLoadSections);
}



const minutiSelect = document.querySelector('#durata_minuti');
const oreSelect = document.querySelector('#durata_ore');
const imageSelect  = document.querySelector('#img_tour');
const typeSelect = document.querySelector('#activity_type');
const sectionSelect = document.querySelector('#activity_section');
document.addEventListener('DOMContentLoaded', optionsHours);
document.addEventListener('DOMContentLoaded', optionsMinutes);
document.addEventListener('DOMContentLoaded', loadTypes);
document.addEventListener('DOMContentLoaded', loadImages);
document.addEventListener('DOMContentLoaded', loadSections);





function removeInputElement(event){
    const container = document.querySelector('.variable_input_cont[data-content="main_info"]');
    const infoBlocks = document.querySelectorAll('.main_info');
    let deleteBlock;
    let i = infoBlocks.length;
    if(i<6){
        const bigContainer = event.currentTarget.closest('.input_container');
        const error = bigContainer.querySelector('.over_error_message');
        error.classList.add('hidden');
    }
    if (i>0){
        for (let infoBlock of infoBlocks){
            if(infoBlock.name === "info" + i){
                deleteBlock = infoBlock;
            }
        }
        container.removeChild(deleteBlock);
    }

}



function addInputElement(event){
    const infoBlocks = document.querySelectorAll('.main_info');
    let i = infoBlocks.length + 1;
    if (i<6){
    const inputEl = document.createElement('input');
    inputEl.classList.add('L_input_text', 'main_info');
    const container = document.querySelector('.variable_input_cont[data-content="main_info"]');
    inputEl.name = "info" + i;  
    inputEl.maxLength = "100";
    inputEl.placeholder = "Aggiungi l'informazione " + i;
    container.appendChild(inputEl);
    }else{
        const bigContainer = event.currentTarget.closest('.input_container');
        const error = bigContainer.querySelector('.over_error_message');
        error.classList.remove('hidden');
    }
}




const removeInput = document.querySelector('.remove_button');
removeInput.addEventListener('click', removeInputElement);


const addInput = document.querySelector('.add_button');
addInput.addEventListener('click', addInputElement);


function checkForm(event){
    event.preventDefault();
    let valid = true;
    let inputTextElements = document.querySelectorAll('input');
    for (let inputTextElement of inputTextElements){
        if (inputTextElement.value.length === 0){
            inputTextElement.classList.add('unvalid');
            valid = false;
        }else{
            inputTextElement.classList.remove('unvalid');
        }
    }

    let selectInputElements = document.querySelectorAll('select');
    for (let selectInputElement of selectInputElements){
        if (!selectInputElement.value){
            selectInputElement.classList.add('unvalid');
            valid = false;
        }else{
            selectInputElement.classList.remove('unvalid');
        }
    }

    let textAreaElements = document.querySelectorAll('textarea');
    for (let textAreaElement of textAreaElements){
        if (textAreaElement.value.length === 0){
            textAreaElement.classList.add('unvalid');
            valid = false;
        }else{
            textAreaElement.classList.remove('unvalid');
        }
    }
    console.log(valid);
    if (!valid){
        const unvalidElements = document.querySelectorAll('.unvalid');
        for (let unvalidElement of unvalidElements){
            const bigContainer = unvalidElement.closest('.input_container');
            const error = bigContainer.querySelector('.gen_error_message');
            error.classList.remove('hidden');
        }
    }else{
        form.submit();
    }
}


function removeErrorMessage(event){
    let inputBox = event.currentTarget;
    const bigContainer = inputBox.closest('.input_container');
    const error = bigContainer.querySelector('.gen_error_message');
    if (inputBox.value !== ""){
        error.classList.add('hidden');
        inputBox.classList.remove('unvalid');
    }
}

const textElements = document.querySelectorAll('input');
for (let textElement of textElements){
    textElement.addEventListener('keydown', removeErrorMessage);
}
const textareaElements = document.querySelectorAll('.big_text_input');
for (let textareaElement of textareaElements){
    textareaElement.addEventListener('keydown', removeErrorMessage);
}

const selectElements = document.querySelectorAll('select');

for (let selectElement of selectElements){

    selectElement.addEventListener('click', removeErrorMessage);
}



const form = document.forms['add_activity'];
form.addEventListener('submit', checkForm);



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