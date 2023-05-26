patchData('username');
patchData('email');

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
