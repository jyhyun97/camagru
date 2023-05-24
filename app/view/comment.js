const commentInput = document.getElementById('comment-input');
const commentSubmitButton = document.getElementById('comment-submit-button');

commentSubmitButton.addEventListener('click', () => submitComment());

function submitComment() 
{
    const data = {
        postId : window.location.pathname.split('/')[2],
        username : document.getElementById('login-label').innerText,
        comment : commentInput.value
    }
    const httpRequest = new XMLHttpRequest();
    httpRequest.open('POST', '/comment');
    httpRequest.setRequestHeader('Conetent-Type', 'application/json')
    httpRequest.onload = () => {
        console.log(httpRequest.response);
    };
    httpRequest.send(JSON.stringify(data));
}