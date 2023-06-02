const video = document.getElementById('video');
const canvas = document.getElementById('canvas');
const captureButton = document.getElementById('capture-button');
const uploadButton = document.getElementById('upload-button');
const postButton = document.getElementById('post-button');
const capturedList = document.getElementById('captured-list');
var selectedImage = null;

captureButton.addEventListener('click', () => {takePicture()});
uploadButton.addEventListener('change', (e) => {uploadImage(e)});
postButton.addEventListener('click', () => {postImage()});

navigator.mediaDevices.getUserMedia({video : true, audio : false})
.then((stream) => {
    video.srcObject = stream;
    video.play();
})
.catch((err) => {
    console.log(err);
    video.hidden = true;
})

function deleteImage(e) {
    
    if(confirm('정말로 이미지를 삭제하시겠습니까? 이미지에 연결된 게시물도 삭제됩니다.'))
    {
        const data = {imageId : e.target.dataset.imageId}
        const httpRequest = new XMLHttpRequest();
        httpRequest.open('DELETE', '/image');
        httpRequest.setRequestHeader('Content-Type', 'application/json');
        httpRequest.onload = () => {
            location.reload();
        }
        httpRequest.send(JSON.stringify(data));
    }
}

function postImage() {
    if (selectedImage === null)
        return alert('이미지를 선택해주세요');
    const data = {imageId : selectedImage.id};

    const httpRequest = new XMLHttpRequest();
    httpRequest.open('POST', '/image');
    httpRequest.setRequestHeader('Content-Type', 'application/json');
    httpRequest.onload = () => {
        console.log(httpRequest.response);
    }
    httpRequest.send(JSON.stringify(data));
}
function selectImage(e) {
    const capturedImages = document.getElementsByClassName('captured-image');
        Array.from(capturedImages).forEach((ele) => {
            ele.style.border = 'none';
        });
    selectedImage = e.target;
    e.target.style.border = 'solid 3px green';
}

function uploadImage(e) {
    uploadFile = e.target.files[0];
    video.hidden = true;
    canvas.hidden = false;
    
    const context = canvas.getContext("2d");
    const newImage = new Image();
    newImage.src = URL.createObjectURL(uploadFile);
    newImage.onload = () => {
        context.drawImage(newImage, 0, 0, canvas.width, canvas.height);
    }
}

function takePicture() {
    const context = canvas.getContext("2d");
    if (video.hidden === false)
        context.drawImage(video, 0, 0, canvas.width, canvas.height);
    
    const data = canvas.toDataURL("image/png");

    const httpRequest = new XMLHttpRequest();
    const capturedData = {
        username : sessionStorage.getItem("username"),
        baseImage : data,
        stickyImages : []
    };
    httpRequest.open('POST', '/capture');
    httpRequest.setRequestHeader(
        'Content-Type', 'text/plain'
    );
    httpRequest.onload = () => {
        const responseData = JSON.parse(httpRequest.response);
        const capturedImages = document.getElementsByClassName('captured-image');
        Array.from(capturedImages).forEach((ele) => {
            ele.remove();
        });
        responseData.forEach((ele) => {
            const newNode = document.createElement('img');
            newNode.src = ele.image;
            newNode.className = 'captured-image';
            newNode.id = "captured-image-" + ele.imageId;
            newNode.onclick = () => selectImage(event);
            capturedList.appendChild(newNode);
        })
    }
    httpRequest.send(JSON.stringify(capturedData));
}
/**
 * <div class="capture">
 *  <img>
 *  <button 삭제>
 * </div>
 */