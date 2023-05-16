/**
 * 웹캠 대신 이미지 업로드 기능 이용해서 이미지를 가져오기
 */
const video = document.getElementById('video');
const canvas = document.getElementById('canvas');
const captureButton = document.getElementById('capture-button');

navigator.mediaDevices.getUserMedia({video : true, audio : false})
.then((stream) => {
    video.srcObject = stream;
    video.play();
})
.catch((err) => {
    console.log(err);
    //video element 숨기기
    //대신 대체 이미지 태그 활성화 혹은 추가
})

captureButton.addEventListener('click', () => {takePicture()})

function takePicture() {
    const context = canvas.getContext("2d");
    context.drawImage(video, 0, 0, canvas.width, canvas.height);
    
    const data = canvas.toDataURL("image/png");
    const capturedList = document.getElementById('captured-list');
    const newNode = document.createElement('img');
    newNode.src = data;
    newNode.className = 'captured-image';
    capturedList.appendChild(newNode);

    console.log(data);

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