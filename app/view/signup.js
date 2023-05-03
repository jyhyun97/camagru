import { activeModal } from './modal.js';

const cancelButton = document.getElementById('signup-cancel');
cancelButton.addEventListener('click', () => activeModal());

// const submitButton = document.getElementById('signup-submit');
// submitButton.addEventListener('click', () => submitSignup());

// function submitSignup() {
//     const signupEmail = document.getElementById('signup-email');
//     const signupUsername = document.getElementById('signup-username');
//     const signupPassword = document.getElementById('signup-password');
//     const signupData = {
//         email : signupEmail,
//         username : signupUsername,
//         password : signupPassword
//     };

//     const httpRequest = new XMLHttpRequest();
//     httpRequest.open('POST', '/signup');
//     httpRequest.setRequestHeader('Content-Type', 'application/json'); 
//     httpRequest.send(JSON.stringify(signupData));
//     httpRequest.onreadystatechange = () => {
//         if (httpRequest.readyState === XMLHttpRequest.DONE) {
//             if (httpRequest.status === 200) {
//                 alert(httpRequest.responseText);
//             } else {
//                 alert('request 실패');
//             }
//         }
//     };
//     httpRequest.onload = () => {
//         console.log(this.responseText);
//     }
// }