const logoutButton = document.getElementById('logout-button')
logoutButton.addEventListener('click', () => {
  const httpRequest = new XMLHttpRequest()
  httpRequest.open('POST', '/logout')
  httpRequest.onload = () => {
    if (httpRequest.response === '성공') {
      sessionStorage.clear()
      location.replace('/')
    }
  }
  httpRequest.send()
})
