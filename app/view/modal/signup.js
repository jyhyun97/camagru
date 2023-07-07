import { activeModal } from '/app/view/modal/modal.js'

const signupSubmitButton = document.getElementById('signup-submit')
signupSubmitButton.addEventListener('click', () => submitSignup())

const signupButton = document.getElementById('signup-button')
signupButton.addEventListener('click', () => activeModal('signup-form'))

const authSubmitButton = document.getElementById('auth-submit')
authSubmitButton.addEventListener('click', (event) => authSignup(event))

const signupEmail = document.getElementById('signup-email')
const signupUsername = document.getElementById('signup-username')
const signupPassword = document.getElementById('signup-password')
const signupAuthCode = document.getElementById('signup-auth-code');

signupEmail.addEventListener('focusin', () => {
  const signupEmailInfo = document.getElementById('signup-email-info')
  signupEmailInfo.hidden = false
})
signupEmail.addEventListener('focusout', () => {
  const signupEmailInfo = document.getElementById('signup-email-info')
  signupEmailInfo.hidden = true
})

signupUsername.addEventListener('focusin', () => {
  const signupUsernameInfo = document.getElementById('signup-username-info')
  signupUsernameInfo.hidden = false
})
signupUsername.addEventListener('focusout', () => {
  const signupUsernameInfo = document.getElementById('signup-username-info')
  signupUsernameInfo.hidden = true
})

signupPassword.addEventListener('focusin', () => {
  const signupPasswordInfo = document.getElementById('signup-password-info')
  signupPasswordInfo.hidden = false
})
signupPassword.addEventListener('focusout', () => {
  const signupPasswordInfo = document.getElementById('signup-password-info')
  signupPasswordInfo.hidden = true
})

function submitSignup() {
  const signupData = {
    email: signupEmail.value,
    username: signupUsername.value,
    password: signupPassword.value,
    authCode : signupAuthCode.value
    //인증번호도 추가하세요!!!
  }

  const httpRequest = new XMLHttpRequest()
  httpRequest.open('POST', '/signup')
  httpRequest.setRequestHeader('Content-Type', 'application/json')
  httpRequest.onload = () => {
    const response = JSON.parse(httpRequest.response)
    if (httpRequest.status === 201) {
      alert('가입이 완료되었습니다.')
      location.reload()
    } else if (httpRequest.status === 400) alert(response.message)
    else if (httpRequest.status === 409) alert(response.message)
  }
  httpRequest.send(JSON.stringify(signupData))
}

function authSignup(e) {
  const authData = {
    email: signupEmail.value,
    username: signupUsername.value,
    password: signupPassword.value,
  }
  e.target.disabled = true
  signupSubmitButton.disabled = false
  const httpRequest = new XMLHttpRequest()
  httpRequest.open('POST', '/signup-auth')
  httpRequest.onload = () => {
    const response = JSON.parse(httpRequest.response)
    if (httpRequest.status === 200) {
      alert('메일이 발송되었습니다. 인증을 완료해주세요.')
    } else if (httpRequest.status === 400) alert(response.message)
    else if (httpRequest.status === 409) alert(response.message)
  }
  httpRequest.send(JSON.stringify(authData))
}
