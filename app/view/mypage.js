import {
  changeHiddenStatus,
  changeHiddenStatusBootstrap,
} from '/app/view/common.js'

patchData('username')
patchData('email')
patchData('password')

function patchData(type) {
  const elements = {
    submitButton: document.getElementById(type + '-submit-button'),
    cancelButton: document.getElementById(type + '-cancel-button'),
    changeButton: document.getElementById(type + '-change-button'),
  }

  if (type === 'password') {
    elements.passwordOrigin = document.getElementById(type + '-origin')
    elements.passwordOriginInput = document.getElementById(
      type + '-origin-input'
    )
    elements.passwordNew = document.getElementById(type + '-new')
    elements.passwordNewInput = document.getElementById(type + '-new-input')
    elements.passwordCheck = document.getElementById(type + '-check')
    elements.passwordCheckInput = document.getElementById(type + '-check-input')
  } else {
    elements.changeInput = document.getElementById(type + '-change-input')
  }

  elements.changeButton.addEventListener('click', () => {
    changeHiddenStatusBootstrap(elements)
  })
  elements.cancelButton.addEventListener('click', () => {
    changeHiddenStatusBootstrap(elements)
  })

  elements.submitButton.addEventListener('click', () => {
    const data = {}
    if (type === 'username') {
      data.change = elements.changeInput.value
    } else if (type === 'email') {
      data.email = elements.changeInput.value
    } else if (type === 'password') {
      data.originPassword = elements.passwordOriginInput.value
      data.newPassword = elements.passwordNewInput.value
      data.checkPassword = elements.passwordCheckInput.value
    }
    const httpRequest = new XMLHttpRequest()
    const url = '/' + type
    httpRequest.open('PATCH', url)
    httpRequest.setRequestHeader('Conetent-Type', 'application/json')
    httpRequest.onload = () => {
      if (httpRequest.status === 200) {
        location.reload()
      } else if (httpRequest.status === 400) {
        const responseData = JSON.parse(httpRequest.response)
        alert(responseData.message)
      } else if (httpRequest.status === 401)
        alert('올바르지 않은 인증 정보입니다.')
      else if (httpRequest.status === 409) {
        if (type === 'username') alert('이미 등록된 닉네임입니다.')
        else if (type === 'email') alert('이미 등록된 이메일입니다.')
      }
    }
    httpRequest.send(JSON.stringify(data))
  })
}

const postDeleteButtons = document.getElementsByClassName('post-delete-button')

Array.from(postDeleteButtons).forEach((ele) => {
  ele.addEventListener('click', () => {
    if (confirm('정말로 게시물을 삭제하시겠습니까?')) {
      const data = {
        postId: ele.dataset.postId,
      }
      const httpRequest = new XMLHttpRequest()
      httpRequest.open('DELETE', '/post')
      httpRequest.setRequestHeader('Content-Type', 'application/json')
      httpRequest.onload = () => {
        if (httpRequest.status === 200) {
          alert('삭제되었습니다')
          location.reload()
        } else if (httpRequest.status === 401)
          alert('올바르지 않은 인증 정보입니다.')
      }
      httpRequest.send(JSON.stringify(data))
    }
  })
})

const imageDeleteButtons = document.getElementsByClassName(
  'image-delete-button'
)
Array.from(imageDeleteButtons).forEach((ele) => {
  ele.addEventListener('click', () => {
    if (
      confirm(
        '정말로 이미지를 삭제하시겠습니까? 이미지에 연결된 게시물도 삭제됩니다.'
      )
    ) {
      const data = {
        imageId: ele.dataset.imageId,
      }
      const httpRequest = new XMLHttpRequest()
      httpRequest.open('DELETE', '/image')
      httpRequest.setRequestHeader('Content-Type', 'application/json')
      httpRequest.onload = () => {
        if (httpRequest.status === 200) location.reload()
        else if (httpRequest.status === 401)
          alert('올바르지 않은 인증 정보입니다.')
      }
      httpRequest.send(JSON.stringify(data))
    }
  })
})

const patchAuthButtons = document.getElementsByClassName(
  'patch-auth btn btn-default'
)
Array.from(patchAuthButtons).forEach((ele) => {
  ele.addEventListener('click', (event) => patchAuthNotice('auth', event))
})

const patchNoticeButtons = document.getElementsByClassName(
  'patch-notice btn btn-default'
)
Array.from(patchNoticeButtons).forEach((ele) => {
  ele.addEventListener('click', (event) => patchAuthNotice('notice', event))
})

function patchAuthNotice(type, event) {
  const data = {}
  if (type === 'auth') data.auth = event.target.dataset.active
  else if (type === 'notice') data.notice = event.target.dataset.active
  const httpRequest = new XMLHttpRequest()
  httpRequest.open('PATCH', '/user')
  httpRequest.setRequestHeader('Content-Type', 'application/json')
  httpRequest.onload = () => {
    if (httpRequest.status === 200) location.reload()
    else if (httpRequest.status === 401) alert('올바르지 않은 인증 정보입니다.')
  }
  httpRequest.send(JSON.stringify(data))
}
