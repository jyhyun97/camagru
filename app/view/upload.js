const video = document.getElementById('video');
const canvas = document.getElementById('canvas');
const captureButton = document.getElementById('capture-button');
const uploadButton = document.getElementById('upload-button');

captureButton.addEventListener('click', () => {takePicture()});
uploadButton.addEventListener('change', (e) => {uploadImage(e)});

navigator.mediaDevices.getUserMedia({video : true, audio : false})
.then((stream) => {
    video.srcObject = stream;
    video.play();
})
.catch((err) => {
    console.log(err);
    video.hidden = true;
})
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
    const capturedList = document.getElementById('captured-list');
    const newNode = document.createElement('img');
    newNode.src = data;
    newNode.className = 'captured-image';
    capturedList.appendChild(newNode);

    const httpRequest = new XMLHttpRequest();
    const capturedData = {
        username : 'jeonhyun',
        baseImage : data,
        stickyImages : []
    };
    httpRequest.open('POST', '/capture');
    httpRequest.setRequestHeader(
        'Content-Type', 'text/plain'
    );
    httpRequest.onload = () => {
        console.log(httpRequest.response);
    }
    httpRequest.send(JSON.stringify(capturedData));
    //post /capture
    //안에 데이터는 이렇게 주기
    //{username : username, baseImage : data, baseImageType : 이미지타입, stickyImages : [선택한 이미지 이름]}
    //
}
/**
 * <div class="capture">
 *  <img>
 *  <button 삭제>
 * </div>
 */