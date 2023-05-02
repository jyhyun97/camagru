<div>
    <h2>회원가입</h2>
    <form class="signform">
        <div class="signform-line">
            <label>이메일</label>
            <input type="email" id="signup-email">
        </div>
        <div class="signform-line">
            <label>닉네임</label>
            <input type="text" id="signup-username">
        </div>
        <div class="signform-line">
            <label>비밀번호</label>
            <input type="password" id="signup-password">
        </div>
        <button>회원가입</button><!-- 폼 내용 취합해 객체로 만들어서 요청 보내기 이 부분은 컨트롤러 사용 -->
        <button>취소</button><!-- 클릭 시 모달 닫기-->
    </form>
    <!-- 추후 유효성검사메시지 추가 -->
</div>