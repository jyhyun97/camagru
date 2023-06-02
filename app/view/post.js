import {changeHiddenStatus} from '/app/view/common.js'

const likesButton = document.getElementById('likes-button');
likesButton.addEventListener('click', () => {
    const data = {
        postId: window.location.pathname.split('/')[2],
        username: sessionStorage.getItem('username')
    };
    const httpRequest = new XMLHttpRequest();
    httpRequest.open('POST', '/likes');
    httpRequest.setRequestHeader('Content-Type', 'application/json');
    httpRequest.onload = () => {
        location.reload();
    };
    httpRequest.send(JSON.stringify(data));
});

const postDeleteButton = document.getElementById('post-delete-button');
if (postDeleteButton)
{
    postDeleteButton.addEventListener('click', () => {
        if (confirm("정말로 게시물을 삭제하시겠습니까?"))
        {
            const data = {postId: window.location.pathname.split('/')[2],};
            const httpRequest = new XMLHttpRequest();
            httpRequest.open('DELETE', '/post');
            httpRequest.setRequestHeader('Content-Type', 'application/json');
            httpRequest.onload = () => {
                alert('삭제되었습니다');
                window.location.href = '/';
            };
            httpRequest.send(JSON.stringify(data));
        }
    })
}
    
    
const commentDeleteButtons = document.getElementsByClassName('comment-delete-button');
const commentPatchButtons = document.getElementsByClassName('comment-patch-button');
const commentPatchInputs = document.getElementsByClassName('comment-patch-input');
const commentPatchCancels = document.getElementsByClassName('comment-patch-cancel');
const commentPatchSubmits = document.getElementsByClassName('comment-patch-submit');

//변경, 취소 버튼 hidden 상태 변경
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
//댓글 수정
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
// 댓글 삭제
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

function findElementByCommentId (elementArray, commentId) {
    const element = Array.from(elementArray).find((ele) => {
        if (ele.dataset.commentId === commentId)
            return ele;
    })
    return element;
}