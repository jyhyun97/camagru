import { activeModal } from '/app/view/modal/modal.js'

const signupSubmitButton = document.getElementById('signup-submit')
signupSubmitButton.addEventListener('click', () => submitSignup())

const signupButton = document.getElementById('signup-button')
signupButton.addEventListener('click', () => activeModal('signup-form'))

const signupEmail = document.getElementById('signup-email')
const signupUsername = document.getElementById('signup-username')
const signupPassword = document.getElementById('signup-password')

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
signupPassword.addEventListener('keyup', (e) => {
  if (e.keyCode === 13) submitSignup()
})

function submitSignup() {
  const signupData = {
    email: signupEmail.value,
    username: signupUsername.value,
    password: signupPassword.value
  }

  const httpRequest = new XMLHttpRequest()
  httpRequest.open('POST', '/signup')
  httpRequest.setRequestHeader('Content-Type', 'application/json')
  httpRequest.onload = () => {
    if (httpRequest.status === 201) {
      alert('메일인증을 통해 가입을 완료하세요')
      location.reload()
    } else if (httpRequest.status === 400) {
      const response = JSON.parse(httpRequest.response)
      alert(response.message)
    } else if (httpRequest.status === 409) {
      const response = JSON.parse(httpRequest.response)
      alert(response.message)
    }
  }
  httpRequest.send(JSON.stringify(signupData))
}