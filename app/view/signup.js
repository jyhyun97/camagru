import { activeModal } from './modal.js';
const cancelButton = document.getElementById('signup-cancel');
cancelButton.addEventListener('click', () => activeModal());

const submitButton = document.getElementById('signup-submit');
submitButton.addEventListener('click', () => submitSignup());

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
        console.log(httpRequest.response);
    }
    httpRequest.send(JSON.stringify(signupData));
}