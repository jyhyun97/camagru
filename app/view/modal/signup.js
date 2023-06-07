import { activeModal } from '/app/view/modal/modal.js';

const signupSubmitButton = document.getElementById('signup-submit');
signupSubmitButton.addEventListener('click', () => submitSignup());

const signupButton = document.getElementById('signup-button');
signupButton.addEventListener('click', () => activeModal('signup-form'));

function submitSignup() {
    const signupEmail = document.getElementById('signup-email');
    const signupUsername = document.getElementById('signup-username');
    const signupPassword = document.getElementById('signup-password');
    const signupData = {
        email : signupEmail.value,
        username : signupUsername.value,
        password : signupPassword.value
    };

    const httpRequest = new XMLHttpRequest();
    httpRequest.open('POST', '/signup');
    httpRequest.setRequestHeader('Content-Type', 'application/json');
    httpRequest.onload = () => {
        const response = JSON.parse(httpRequest.response);
        if (httpRequest.status === 201)
        {
            alert("가입이 완료되었습니다.");
            location.reload();
        }
        else if (httpRequest.status === 400)
            alert(response.message);
        else if (httpRequest.status === 409)
            alert(response.message);
    }
    httpRequest.send(JSON.stringify(signupData));
}