import { activeModal } from '/app/view/modal/modal.js'

const signinSubmitButton = document.getElementById('signin-submit')
signinSubmitButton.addEventListener('click', () => submitSignin())

const signinPassword = document.getElementById('signin-password')
signinPassword.addEventListener('keyup', (e) => {
  if (e.keyCode === 13) submitSignin()
})

const signinButton = document.getElementById('signin-button')
signinButton.addEventListener('click', () => activeModal('signin-form'))

const pwRecoveryButton = document.getElementById('pw-recovery-button')
pwRecoveryButton.addEventListener('click', () => pwRecoveryActive())

const pwRecoverySubmit = document.getElementById('pw-recovery-submit')
pwRecoverySubmit.addEventListener('click', () => pwRecoveryRequest())
const pwRecoveryInput = document.getElementById('pw-recovery-input')
pwRecoveryInput.addEventListener('keyup', (e) => {
  if (e.keyCode === 13) pwRecoveryRequest()
})

function submitSignin() {
  const signinEmail = document.getElementById('signin-email')
  const signinPassword = document.getElementById('signin-password')
  const signinData = {
    email: signinEmail.value,
    password: signinPassword.value,
  }

  const httpRequest = new XMLHttpRequest()
  httpRequest.open('POST', '/signin')
  httpRequest.setRequestHeader('Content-Type', 'application/json')
  httpRequest.onload = () => {
    if (httpRequest.status === 200) {
      location.reload()
    } else if (httpRequest.status === 202) {
      const response = JSON.parse(httpRequest.response)
      alert(response.message)
      location.reload()
    } else if (httpRequest.status === 400 || httpRequest.status === 401)
      alert('이메일과 비밀번호를 확인해주세요.')
  }
  httpRequest.send(JSON.stringify(signinData))
}

function pwRecoveryActive() {
  const pwRecoveryLine = document.getElementById('pw-recovery-line')
  pwRecoveryLine.hidden = false
}

function pwRecoveryRequest() {
  const pwRecoveryInput = document.getElementById('pw-recovery-input')
  const data = { email: pwRecoveryInput.value }

  //요청 보내고 200이면 alert
  const httpRequest = new XMLHttpRequest()
  httpRequest.open('POST', '/password-recovery')
  httpRequest.setRequestHeader('Content-Type', 'application/json')
  httpRequest.onload = () => {
    if (httpRequest.status === 200) {
      alert('임시 비밀번호가 발급되었습니다.')
    }
  }
  httpRequest.send(JSON.stringify(data))
}
