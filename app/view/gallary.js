const pageData = { currentPage: 1, size: 6, firstPage : true, lastPage : true };

const gallaryLeftButton = document.getElementById('gallary-left-button');
gallaryLeftButton.addEventListener('click', () => {pagination(pageData.currentPage - 1)});
const gallaryRightButton = document.getElementById('gallary-right-button');
gallaryRightButton.addEventListener('click', () => {pagination(pageData.currentPage + 1)});

function pagination(movePage)
{
    if (pageData.currentPage === 1 && movePage === 2)
        pageData.firstPage = false;
    else if (movePage === 1 && pageData.currentPage === 2)
        pageData.firstPage = true;
    pageData.currentPage = movePage;
    postGallary();
}

function buttonRerender()
{
    if (pageData.firstPage === true)
    gallaryLeftButton.disabled = true;
    else
    gallaryLeftButton.disabled = false;
    if (pageData.lastPage === true)
    gallaryRightButton.disabled = true;
    else
    gallaryRightButton.disabled = false;
}

function postGallary() {
    const httpRequest = new XMLHttpRequest();
    httpRequest.open('POST', '/gallary');
    httpRequest.setRequestHeader('Content-Type', 'application/json');
    httpRequest.onload = () => {
        const responseData = JSON.parse(httpRequest.response);
        const gallaryCurrentPage = document.getElementById('gallary-current-page');
        gallaryCurrentPage.innerText = pageData.currentPage;
        const gallaryPosts = document.getElementById('gallary-posts');
        gallaryPosts.replaceChildren();
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
            const newImgNode = document.createElement('img');
            newImgNode.src = ele.image;
            newImgNode.style.width = '200px';
            newImgNode.style.height = '200px';

            const newANode = document.createElement('a');
            newANode.href = "/post/" + ele.postId;
            newANode.appendChild(newImgNode);
            
            const newLabelNode = document.createElement('label');
            if (ele.likes === null)
                ele.likes = '';
            newLabelNode.innerText = ele.likes + "❤️";

            const newDivNode = document.createElement('div');
            newDivNode.className = 'gallary-post';
            newDivNode.appendChild(newANode);
            newDivNode.appendChild(newLabelNode);

            gallaryPosts.appendChild(newDivNode);
            
        });
        pageData.lastPage = responseData.lastPage;
        buttonRerender();
    }
    httpRequest.send(JSON.stringify(pageData));
}

postGallary();