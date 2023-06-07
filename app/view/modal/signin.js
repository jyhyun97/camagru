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
        const response = JSON.parse(httpRequest.response);
        if (httpRequest.status === 200)
        {
            location.reload();
            sessionStorage.setItem("username", response.username);
        }
        else if (httpRequest.status === 400 || httpRequest.status === 401)
            alert("이메일과 비밀번호를 확인해주세요.");
    }
    httpRequest.send(JSON.stringify(signinData));
}