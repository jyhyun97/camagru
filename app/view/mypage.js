//1. 변경을 누르면 label과 변경 버튼이 숨겨지고 input, 제출, 취소 버튼이 추가된다.
//2-1. 제출을 누르면 input값 다음과 같이 요청 보내기
//  patch /username
//  body {username : "바뀔 유저네임"}
//2-2. 취소를 누르면 1과 반대로 히든 상태 변경
//3-1. 요청이 성공하면, sessionStorage에 저장된 username 변경하기(서버에서도 세션 바꿔주셈)
//     물론 버튼 히든 상태도 원상복구 시켜요
//3-2. 요청이 실패하면 알림 띄우고 히든상태 원상복구 시켜요
patchData('username');
patchData('email');

//what the frak...

function patchData(type)
{
    const changeButton = document.getElementById(type + '-change-button');
    const changeInput = document.getElementById(type + '-change-input');
    const submitButton = document.getElementById(type + '-submit-button');
    const cancelButton = document.getElementById(type + '-cancel-button');

    changeButton.addEventListener('click', () => {
        changeHiddenStatus(changeButton, changeInput, submitButton, cancelButton);
    });
    cancelButton.addEventListener('click', () => {
        changeHiddenStatus(changeButton, changeInput, submitButton, cancelButton);
    });
    submitButton.addEventListener('click', () => {
        const data = {};//좀 더 다형성 살려서 객체 만들 방법은 없을까...
        if (type === 'username')
            data.username = changeInput.value;
        else if (type === 'email')
            data.email = changeInput.value;
        const httpRequest = new XMLHttpRequest();
        httpRequest.open('PATCH', '/' + type+ '/' + sessionStorage.getItem('username'));
        httpRequest.setRequestHeader('Conetent-Type', 'application/json');
        httpRequest.onload = () => {
            if (httpRequest.response === '성공')
            {
                if (type === 'username')
                    sessionStorage.setItem('username', data.username);
                location.reload();
            }
            else
                alert(httpRequest.response);
        };    
        httpRequest.send(JSON.stringify(data));
    });

    function changeHiddenStatus(...element)
    {
        const args = Array.from(element);

        if (args.length < 1)
            console.log('나중에 throw');
        
        args.forEach((ele) => {
            if (ele.hidden === true)
                ele.hidden = false;
            else
                ele.hidden = true;
        });
    }
}

// const usernameChangeButton = document.getElementById('username-change-button');
// const usernameChangeInput = document.getElementById('username-change-input');
// const usernameSubmitButton = document.getElementById('username-submit-button');
// const usernameCancelButton = document.getElementById('username-cancel-button');

// usernameChangeButton.addEventListener('click', () => {
//     changeHiddenStatus(usernameChangeButton, usernameChangeInput, usernameSubmitButton, usernameCancelButton);
// });
// usernameCancelButton.addEventListener('click', () => {
//     changeHiddenStatus(usernameChangeButton, usernameChangeInput, usernameSubmitButton, usernameCancelButton);
// });
// usernameSubmitButton.addEventListener('click', () => {
//     const data = {
//         username : usernameChangeInput.value
//     };
//     const httpRequest = new XMLHttpRequest();
//     httpRequest.open('PATCH', '/username/' + sessionStorage.getItem('username'));
//     httpRequest.setRequestHeader('Conetent-Type', 'application/json');
//     httpRequest.onload = () => {
//         if (httpRequest.response === '성공')
//         {
//             sessionStorage.setItem('username', data.username);
//             location.reload();
//         }
//         else
//             alert(httpRequest.response);
//     };    
//     httpRequest.send(JSON.stringify(data));
// });

