import { activeModal } from './modal/modal.js';
const signinButton = document.getElementById('signin-button');
signinButton.addEventListener('click', () => activeModal('signin-form'));

const signupButton = document.getElementById('signup-button');
signupButton.addEventListener('click', () => activeModal('signup-form'));