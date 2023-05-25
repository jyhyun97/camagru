import { activeModal } from '/app/view/modal/modal.js';

const signinSubmitButton = document.getElementById('signin-submit');
signinSubmitButton.addEventListener('click', () => submitSignin() );

const signinButton = document.getElementById('signin-button');
signinButton.addEventListener('click', () => activeModal('signin-form'));

function submitSignin() {
    const signinEmail = document.getElementById('signin-email');
    const signinPassword = document.getElementById('signin-password');
    const signinData = {
        email: signinEmail.value,
        password: signinPassword.value
    };

    const httpRequest = new XMLHttpRequest();
    httpRequest.open('POST', '/signin');
    httpRequest.setRequestHeader('Content-Type', 'application/json');
    httpRequest.onload = () => {
        if (httpRequest.response !== '로그인 실패')
        {
            location.reload();
            sessionStorage.setItem("username", httpRequest.response);
        }
    }
    httpRequest.send(JSON.stringify(signinData));
}