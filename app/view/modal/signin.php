<div class="signform" id="signin-form">
    <h2>로그인</h2>
    <div class="signform-line">
        <label>이메일</label>
        <input type="email" id="signin-email">
    </div>
    <div class="signform-line">
        <label>비밀번호</label>
        <input type="password" id="signin-password">
    </div>
    <div class="signform-line" id="signin-auth-line" hidden>
        <label>인증번호</label>
        <input type="text" id="signin-auth-input">
    </div>
    <div class="signform-button">
        <button id="signin-submit" class="btn btn-primary">로그인</button>
        <button id="signin-auth-submit" class="btn btn-primary hidden">인증 완료 및 로그인</button>
        <button class="modal-cancel btn btn-default" id="signin-cancel">취소</button>
    </div>
</div>