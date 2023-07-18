const commentInput = document.getElementById('comment-input')
const commentSubmitButton = document.getElementById('comment-submit-button')

commentSubmitButton.addEventListener('click', () => submitComment())

function submitComment() {
  const data = {
    postId: window.location.pathname.split('/')[2],
    username: sessionStorage.getItem('username'),
    comment: commentInput.value,
  }
  const httpRequest = new XMLHttpRequest()
  httpRequest.open('POST', '/comment')
  httpRequest.setRequestHeader('Conetent-Type', 'application/json')
  httpRequest.onload = () => {
    if (httpRequest.status === 201) location.reload()
    else if (httpRequest.status === 401) alert('올바르지 않은 인증 정보입니다.')
  }
  httpRequest.send(JSON.stringify(data))
}
