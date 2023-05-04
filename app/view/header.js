import { activeModal } from './modal.js';
const signinButton = document.getElementById('signin-button');
signinButton.addEventListener('click', () => submitSignin('/signin'));

const signupButton = document.getElementById('signup-button');
signupButton.addEventListener('click', () => submitSignin('/signup'));

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
                const newNode = document.createElement('script')
                newNode.type="module";
                newNode.src="app/view/signup.js";
                modalNode.innerHTML = httpRequest.responseText;
                document.body.appendChild(newNode);
            } else {
                alert('request 실패');
            }
        }
    }
}