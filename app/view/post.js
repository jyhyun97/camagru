import {changeHiddenStatus} from '/app/view/common.js'

const likesButton = document.getElementById("likes-button");
likesButton.addEventListener("click", () => {
    const data = {
        postId: window.location.pathname.split('/')[2],
        username: sessionStorage.getItem("username")
    };
    const httpRequest = new XMLHttpRequest();
    httpRequest.open('POST', '/likes');
    httpRequest.setRequestHeader('Content-Type', 'application/json');
    httpRequest.onload = () => {
        location.reload();
    };
    httpRequest.send(JSON.stringify(data));
});

//comment-delete-button들 onclick 시
//해당하는 댓글 삭제하는 DELETE 요청(commentId)
//새로고침!

const commentDeleteButtons = document.getElementsByClassName('comment-delete-button');

Array.from(commentDeleteButtons).forEach((ele) => {
    ele.addEventListener('click', () => {
        if (confirm('댓글을 삭제하시겠습니까?'))
        {
            const data = {commentId : ele.dataset.commentId};
            
            const httpRequest = new XMLHttpRequest();
            httpRequest.open('DELETE', '/comment');
            httpRequest.onload = () => {
                console.log(httpRequest.response);
                location.reload();
            }
            httpRequest.send(JSON.stringify(data));
        }

    })
});

const commentPatchButtons = document.getElementsByClassName('comment-patch-button');
const commentPatchInputs = document.getElementsByClassName('comment-patch-input');
const commentPatchCancels = document.getElementsByClassName('comment-patch-cancel');
const commentPatchSubmits = document.getElementsByClassName('comment-patch-submit');

Array.from(commentPatchButtons).concat(Array.from(commentPatchCancels)).forEach((ele) => {
    ele.addEventListener('click', () => { 
        const commentId = ele.dataset.commentId;
        const elements = {
            commentPatchInput : findElementByCommentId(commentPatchInputs, commentId),
            commentPatchCancel : findElementByCommentId(commentPatchCancels, commentId),
            commentPatchSubmit : findElementByCommentId(commentPatchSubmits, commentId),
            commentDeleteButton : findElementByCommentId(commentDeleteButtons, commentId),
            commentPatchButton : findElementByCommentId(commentPatchButtons, commentId)
        }
        changeHiddenStatus(elements);
    })
})

Array.from(commentPatchSubmits).forEach((ele) => {
    ele.addEventListener('click', () => {
        const commentId = ele.dataset.commentId;
        const commentPatchInput = findElementByCommentId(commentPatchInputs, commentId);
            
        const httpRequest = new XMLHttpRequest();
        const data = {
            commentId : commentId,
            newComment : commentPatchInput.value
        }
        httpRequest.open('PATCH', '/comment');
        httpRequest.setRequestHeader('Content-Type', 'application/json');
        httpRequest.onload = () => {
            console.log(httpRequest.response);
            location.reload();
        }
        httpRequest.send(JSON.stringify(data));
    })

})

function findElementByCommentId (elementArray, commentId) {
    const element = Array.from(elementArray).find((ele) => {
        if (ele.dataset.commentId === commentId)
            return ele;
    })
    return element;
}