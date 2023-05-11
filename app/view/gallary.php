<!-- 렌더링 되면 기본적으로 1페이지, 6개로 요청
이용자가 < >누르면서 값을 다르게 하고.. 이건 javascript -->
<div id="gallary">
    <div id="gallary-posts"></div>
    <div id="gallary-button">
        페이지네이션 영역
        <button id="gallary-left">왼</button>
        <label>현재페이지</label>
        <button id="gallary-right">오</button>
    </div>
</div>

<script>
    const pageData = { currentPage: 1, size: 6 };

    const httpRequest = new XMLHttpRequest();
    httpRequest.open('POST', '/gallary');
    httpRequest.setRequestHeader('Content-Type', 'application/json');
    httpRequest.onload = () => {
        const responseData = JSON.parse(httpRequest.response);
        const gallaryPosts = document.getElementById('gallary-posts');
        responseData.forEach((ele) => {
            const newNode = document.createElement('img');
            newNode.src = ele.image;
            newNode.style.width = '200px';
            newNode.style.height = '200px';
            gallaryPosts.appendChild(newNode);
        });
    }
    httpRequest.send(JSON.stringify(pageData));
</script>