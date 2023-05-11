const pageData = { currentPage: 1, size: 6, firstPage : true, lastPage : true };

const gallaryLeftButton = document.getElementById('gallary-left-button');
gallaryLeftButton.addEventListener('click', () => {});
const gallaryRightButton = document.getElementById('gallary-right-button');
gallaryRightButton.addEventListener('click', () => {});


function pagination(movePage)
{
    //옮겨질 페이지 번호에 따라 요청 다르게 보내기
}
function buttonRerender()
{
    if (pageData.firstPage === true)
        gallaryLeftButton.disabled = true;
    else
        gallaryLeftButton.disabled = false;
    if (pageData.lastPage === true)
        gallaryRightButton.disabled = ture;
    else
        gallaryRightButton.disabled = false;
}

function postGallary() {
    const httpRequest = new XMLHttpRequest();
    httpRequest.open('POST', '/gallary');
    httpRequest.setRequestHeader('Content-Type', 'application/json');
    httpRequest.onload = () => {
        const responseData = JSON.parse(httpRequest.response);
        const gallaryPosts = document.getElementById('gallary-posts');
        console.log(responseData);
        responseData.data.forEach((ele) => {
            /** 이런 element를 만드세요
            * <div class="gallary-post">
            * <a href="/post/포스트id">
            *   <img src="이미지 path">
            * </a>
            * <label>ele.likes</label>
            * <button>좋아요</button>
            * </div>
            */
            const newNode = document.createElement('img');
            newNode.src = ele.image;
            newNode.style.width = '200px';
            newNode.style.height = '200px';
            gallaryPosts.appendChild(newNode);
        });
        pageData.lastPage = responseData.lastPage;
        buttonRerender();
    }
    httpRequest.send(JSON.stringify(pageData));
}
postGallary();