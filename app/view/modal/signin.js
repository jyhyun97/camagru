import { activeModal } from '/app/view/modal/modal.js'

const signinSubmitButton = document.getElementById('signin-submit')
signinSubmitButton.addEventListener('click', () => submitSignin())

const signinPassword = document.getElementById('signin-password')
signinPassword.addEventListener('keyup', (e) => {
  if (e.keyCode === 13) submitSignin()
})

const signinButton = document.getElementById('signin-button')
signinButton.addEventListener('click', () => activeModal('signin-form'))

const signinAuthLine = document.getElementById('signin-auth-line')
const signinAuthSubmit = document.getElementById('signin-auth-submit')
signinAuthSubmit.addEventListener('click', () => submitSigninAuth())
const signinAuthInput = document.getElementById('signin-auth-input')
signinAuthInput.addEventListener('keyup', (e) => {
  if (e.keyCode === 13) submitSigninAuth()
})

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
      const response = JSON.parse(httpRequest.response)
      location.reload()
    } else if (httpRequest.status === 202) {
      const response = JSON.parse(httpRequest.response)
      alert(response.message)
      signinAuthLine.hidden = false
      signinAuthSubmit.className = 'btn btn-primary'
      signinSubmitButton.className = 'btn btn-primary hidden'
      //인증란 활성화, 하단 로그인 버튼 비활성화, 인증 버튼 활성화
    } else if (httpRequest.status === 400 || httpRequest.status === 401)
      alert('이메일과 비밀번호를 확인해주세요.')
  }
  httpRequest.send(JSON.stringify(signinData))
}

function submitSigninAuth() {
  const signinEmail = document.getElementById('signin-email')
  const signinPassword = document.getElementById('signin-password')
  const signinAuthInput = document.getElementById('signin-auth-input')
  const signinData = {
    email: signinEmail.value,
    password: signinPassword.value,
    authCode: signinAuthInput.value,
  }
  const httpRequest = new XMLHttpRequest()
  httpRequest.open('POST', '/signin-auth')
  httpRequest.setRequestHeader('Content-Type', 'application/json')
  httpRequest.onload = () => {
    if (httpRequest.status === 200) {
      const response = JSON.parse(httpRequest.response)
      location.reload()
    } else if (httpRequest.status === 400)
      alert('이메일과 비밀번호를 확인해주세요.')
    else if (httpRequest.status === 401) {
      const response = JSON.parse(httpRequest.response)
      alert(response.message)
    }
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
