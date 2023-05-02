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
    //const 컨트롤러;
    //const signupRes = 컨트롤러.컨트롤러_무슨 함수()
    //signupRes 메시지 띄우기 가입 완료, 중복입니다, 유효성검사 지켜주세요 등등등..
    
}