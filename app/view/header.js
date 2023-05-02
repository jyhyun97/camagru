import { activeModal } from './modal.js';
const signinButton = document.getElementById('signin-button');
signinButton.addEventListener('click', () => submitSignin('app/view/signin.php'));

const signupButton = document.getElementById('signup-button');
signupButton.addEventListener('click', () => submitSignin('app/view/signup.php'));

// signin이나 signup에 대한 요청을 보내고 해당하는 php파일 가져오는 함수
function submitSignin(path) {
    const httpRequest = new XMLHttpRequest();

    httpRequest.onreadystatechange = alertContents;
    httpRequest.open('GET', path);
    httpRequest.send();
    activeModal();

    function alertContents() {
        if (httpRequest.readyState === XMLHttpRequest.DONE) {
            if (httpRequest.status === 200) {
                const modalNode = document.getElementById('modal-content');
                modalNode.innerHTML = httpRequest.responseText;
            } else {
                alert('request 실패');
            }
        }
    }
}