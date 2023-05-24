const likesButton = document.getElementById("likes-button");
likesButton.addEventListener("click", () => {
    const data = { postId: window.location.pathname.split('/')[2], username: 'jeonhyun' };
    const httpRequest = new XMLHttpRequest();
    httpRequest.open('POST', '/likes');
    httpRequest.setRequestHeader('Content-Type', 'application/json');
    httpRequest.onload = () => {
        location.reload();
    };
    httpRequest.send(JSON.stringify(data));
});