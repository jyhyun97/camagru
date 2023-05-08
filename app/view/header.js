import { activeModal } from './modal.js';
const signinButton = document.getElementById('signin-button');
signinButton.addEventListener('click', () => submitSignRequest('signin-form'));

const signupButton = document.getElementById('signup-button');
signupButton.addEventListener('click', () => submitSignRequest('signup-form'));

function submitSignRequest(path) {
    activeModal(path);
}