import { activeModal } from './modal.js';
const cancelButton = document.getElementById('signin-cancel');
cancelButton.addEventListener('click', () => activeModal());

const submitButton = document.getElementById('signin-submit');
submitButton.addEventListener('click', () => submitSignin());

function submitSignin() {
    const signinEmail = document.getElementById('signin-email');
    const signinPassword = document.getElementById('signin-password');
    const signinData = {
        email : signinEmail.value,
        password : signinPassword.value
    };

    const httpRequest = new XMLHttpRequest();
    httpRequest.open('POST', '/signin');
    httpRequest.setRequestHeader('Content-Type', 'application/json');
    httpRequest.onload = () => {
        console.log(httpRequest.response);
        if (httpRequest.response === '로그인 성공')
            location.reload();
    }
    httpRequest.send(JSON.stringify(signinData));
}