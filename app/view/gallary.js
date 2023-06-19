const pageData = { currentPage: 1, size: 8, firstPage : true, lastPage : true };

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
        if (httpRequest.status === 200)
        {
            const responseData = JSON.parse(httpRequest.response);
            const gallaryCurrentPage = document.getElementById('gallary-current-page');
            gallaryCurrentPage.innerText = pageData.currentPage;
            const gallaryPosts = document.getElementById('gallary-posts');
            gallaryPosts.replaceChildren();
            
            responseData.data.forEach((ele) => {
                const newImgNode = document.createElement('img');
                newImgNode.src = ele.image;
                
                const newANode = document.createElement('a');
                newANode.href = "/post/" + ele.postId;
                newANode.appendChild(newImgNode);
                
                const newLabelNode = document.createElement('label');
                if (ele.likes_count === null)
                ele.likes_count = '';
                newLabelNode.innerText = ele.likes_count + "❤️";
                
                const newDivNode = document.createElement('div');
                newDivNode.className = 'col-md-3 thumbnail';
                newDivNode.appendChild(newANode);
                newDivNode.appendChild(newLabelNode);
                
                gallaryPosts.appendChild(newDivNode);
            });
            pageData.lastPage = responseData.lastPage;
            buttonRerender();
        }
        else if (httpRequest.status === 204)
        {
            const gallaryPosts = document.getElementById('gallary-posts');
            gallaryPosts.replaceChildren();

            gallaryLeftButton.disabled = true;
            gallaryRightButton.disabled = true;
            const newDivNode = document.createElement('div');
            newDivNode.className = 'gallary-post';
            newDivNode.innerText = '아직 게시물이 없습니다. 새 게시물을 올려보세요';
            gallaryPosts.appendChild(newDivNode);
            
        }
    }
    httpRequest.send(JSON.stringify(pageData));
}

postGallary();