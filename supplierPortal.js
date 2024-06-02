
function arrowClick(event){
    const section = event.currentTarget.closest('.ask_answer');
    const description = section.querySelector('p');
    description.classList.toggle('hidden');
    return
}

const arrowsDown = document.querySelectorAll(".arrowDown");
for (let arrow of arrowsDown){
    arrow.addEventListener('click', arrowClick);
}


function buttonHover(event){
    const button = event.currentTarget;
    button.classList.add('bbutton_hover');
    return
}

function buttonLeave(event){
    const button = event.currentTarget;
    button.classList.remove('bbutton_hover');
    return
}

function buttonClick(event){
    const button = event.currentTarget;
    window.location.href = button.dataset.link; 
    return
}

const buttons = document.querySelectorAll('.blue_button');
for (let button of buttons){
    button.addEventListener('mouseenter', buttonHover);
    button.addEventListener('mouseleave', buttonLeave);
    button.addEventListener('click', buttonClick);

}