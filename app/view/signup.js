import { activeModal } from './modal.js';

const cancelButton = document.getElementById('signup-cancel');
cancelButton.addEventListener('click', () => activeModal());

const submitButton = document.getElementById('signup-cancel');
submitButton.addEventListener('click', () => submitSignup());

function submitSignup() {
    const signupEmail = document.getElementById('signup-email');
    const signupUsername = document.getElementById('signup-username');
    const signupPassword = document.getElementById('signup-password');
    const signupData = {
        email : signupEmail,
        username : signupUsername,
        password : signupPassword
    };
}