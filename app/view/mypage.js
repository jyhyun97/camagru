import {changeHiddenStatus} from '/app/view/common.js'

patchData('username');
patchData('email');
patchData('password');

function patchData(type)
{
    const elements = {
        submitButton: document.getElementById(type + '-submit-button'),
        cancelButton: document.getElementById(type + '-cancel-button'),
        changeButton: document.getElementById(type + '-change-button')
    };
      
    if (type === 'password') {
        elements.passwordOrigin = document.getElementById(type + '-origin');
        elements.passwordOriginInput = document.getElementById(type + '-origin-input');
        elements.passwordNew = document.getElementById(type + '-new');
        elements.passwordNewInput = document.getElementById(type + '-new-input');
        elements.passwordCheck = document.getElementById(type + '-check');
        elements.passwordCheckInput = document.getElementById(type + '-check-input');
    } else {
        elements.changeInput = document.getElementById(type + '-change-input');
    }
    
    elements.changeButton.addEventListener('click', () => {
        changeHiddenStatus(elements);
    });
    elements.cancelButton.addEventListener('click', () => {
        changeHiddenStatus(elements);
    });
    elements.submitButton.addEventListener('click', () => {
        const data = {};
        if (type === 'username')
            data.username = elements.changeInput.value;
        else if (type === 'email')
            data.email = elements.changeInput.value;
        else if (type === 'password')
        {
            data.originPassword = elements.passwordOriginInput.value;
            data.newPassword = elements.passwordNewInput.value;
            data.checkPassword = elements.passwordCheckInput.value;
        }
        const httpRequest = new XMLHttpRequest();
        const url = '/' + type+ '/' + sessionStorage.getItem('username');
        httpRequest.open('PATCH', url);
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

    // function changeHiddenStatus(elements)
    // {
    //     const objectArray = Object.keys(elements).map(ele => elements[ele]);
    //     if (objectArray.length < 1)
    //         console.log('나중에 throw');
        
    //     objectArray.forEach((ele) => {
    //         if (ele.hidden === true)
    //             ele.hidden = false;
    //         else
    //             ele.hidden = true;
    //     });
    // }
}
