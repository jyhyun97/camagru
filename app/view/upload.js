const video = document.getElementById('video')
const canvas = document.getElementById('canvas')
const captureButton = document.getElementById('capture-button')
const uploadButton = document.getElementById('upload-button')
const postButton = document.getElementById('post-button')
const capturedList = document.getElementById('captured-list')
const stickyList = document.getElementById('sticky-list')
var selectedImage = null
var selectedStickys = new Set()

captureButton.addEventListener('click', () => {
  takePicture()
})
uploadButton.addEventListener('change', (e) => {
  uploadImage(e)
})
postButton.addEventListener('click', () => {
  postImage()
})

navigator.mediaDevices
  .getUserMedia({ video: true, audio: false })
  .then((stream) => {
    video.srcObject = stream
    video.play()
  })
  .catch((err) => {
    video.hidden = true
  })

function deleteImage(e) {
  if (
    confirm(
      '정말로 이미지를 삭제하시겠습니까? 이미지에 연결된 게시물도 삭제됩니다.'
    )
  ) {
    const data = {
      imageId: e.target.dataset.imageId,
      username: sessionStorage.getItem('username'),
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
}

function postImage() {
  const data = {
    imageId: selectedImage.id,
    username: sessionStorage.getItem('username'),
  }

  const httpRequest = new XMLHttpRequest()
  httpRequest.open('POST', '/image')
  httpRequest.setRequestHeader('Content-Type', 'application/json')
  httpRequest.onload = () => {
    const responseData = JSON.parse(httpRequest.response)
    if (httpRequest.status === 201) {
      alert('게시물이 등록되었습니다.')
      location.href = '/post/' + responseData.postId
    } else if (httpRequest.status === 401) {
      alert('올바르지 않은 인증 정보입니다.')
    } else if (httpRequest.status === 409) {
      alert(responseData.message)
    }
  }
  httpRequest.send(JSON.stringify(data))
}

function selectImage(e) {
  const capturedImages = document.getElementsByClassName('captured-image')
  postButton.disabled = false
  Array.from(capturedImages).forEach((ele) => {
    ele.style.border = 'none'
  })
  selectedImage = e.target
  e.target.style.border = 'solid 3px green'
}

function selectSticky(e) {
  if (selectedStickys.has(e.target)) {
    e.target.style.border = 'none'
    selectedStickys.delete(e.target)
  } else {
    selectedStickys.add(e.target)
    e.target.style.border = 'solid 3px green'
  }
  addStikcyToCanvas()
}

function addStikcyToCanvas() {
  const stickyCanvas = document.getElementById('sticky-canvas')
  const context = stickyCanvas.getContext('2d')
  context.clearRect(0, 0, stickyCanvas.width, stickyCanvas.height)
  selectedStickys.forEach((ele) => {
    const newSticky = new Image()
    newSticky.src = ele.src
    newSticky.onload = () => {
      context.drawImage(
        newSticky,
        0,
        0,
        stickyCanvas.width,
        stickyCanvas.height
      )
    }
  })
}

function uploadImage(e) {
  uploadFile = e.target.files[0]
  video.hidden = true
  canvas.hidden = false

  if (uploadFile) {
    const context = canvas.getContext('2d')
    const newImage = new Image()
    newImage.src = URL.createObjectURL(uploadFile)
    newImage.onload = () => {
      context.drawImage(newImage, 0, 0, canvas.width, canvas.height)
    }
  } else alert('이미지를 선택해 주세요.')
}

function takePicture() {
  const context = canvas.getContext('2d')
  if (video.hidden === false)
    context.drawImage(video, 0, 0, canvas.width, canvas.height)

  const data = canvas.toDataURL('image/png')

  const httpRequest = new XMLHttpRequest()
  const stickyList = Array.from(selectedStickys).map((ele) => {
    return ele.id
  })
  const capturedData = {
    username: sessionStorage.getItem('username'),
    baseImage: data,
    stickyImages: stickyList,
  }
  httpRequest.open('POST', '/capture')
  httpRequest.setRequestHeader('Content-Type', 'text/plain')
  httpRequest.onload = () => {
    if (httpRequest.status === 201) {
      const responseData = JSON.parse(httpRequest.response).data
      const capturedImages = document.getElementsByClassName('captured-image')
      const deleteButtons = document.getElementsByClassName(
        'capture-delete-button'
      )
      Array.from(capturedImages).forEach((ele) => {
        ele.remove()
      })
      Array.from(deleteButtons).forEach((ele) => {
        ele.remove()
      })
      responseData.forEach((ele) => {
        const newImgNode = document.createElement('img')
        newImgNode.src = ele.image
        newImgNode.className = 'captured-image'
        newImgNode.id = 'captured-image-' + ele.imageId
        newImgNode.onclick = (event) => selectImage(event)

        const newButtonNode = document.createElement('button')
        newButtonNode.dataset.imageId = ele.imageId
        newButtonNode.className = 'capture-delete-button'
        newButtonNode.innerText = 'X'
        newButtonNode.onclick = (event) => deleteImage(event)

        const newDivNode = document.createElement('div')
        newDivNode.className = 'capture'
        newDivNode.appendChild(newImgNode)
        newDivNode.appendChild(newButtonNode)
        capturedList.appendChild(newDivNode)
      })
    } else if (httpRequest.status === 401)
      alert('올바르지 않은 인증 정보입니다.')
  }
  httpRequest.send(JSON.stringify(capturedData))
}
