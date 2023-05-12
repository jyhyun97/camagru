//video : 직접 웹캠 데이터 가져올 비디오
//canvas : 비디오 캡쳐해서 그려줄 용도
//img : 그려진 이미지..... 생성한 이미지 목록에 추가......

/**
 * 1. 초기값 세팅
 * 2. element 가져오기
 * 3. getUserMedia로 비디오 사용
 * 4. 캔버스 이용해서 캡처하고 img태그 추가하는 기능
 */

/**
 * 웹캠 대신 이미지 업로드 기능 이용해서 이미지를 가져오기
 */
const video = document.getElementById('video');
const canvas = document.getElementById('canvas');
const startButton = document.getElementById('start-button');

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

startButton.addEventListener('click', () => {takePicture()})

function takePicture() {
    const context = canvas.getContext("2d");
    context.drawImage(video, 0, 0, 200, 180);
    
    const data = canvas.toDataURL("image/png");
    const capturedList = document.getElementById('captured-list');
    const newNode = document.createElement('img');
    newNode.src = data;
    capturedList.appendChild(newNode);
}