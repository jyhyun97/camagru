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
    <div class="signform-line" id="pw-recovery-line" hidden>
        <div class="alert alert-info" role="alert">
            하단에 이메일 주소를 작송한 후 발급 버튼을 누르면 메일을 통해 임시 비밀번호가 발급됩니다.<br>
            임시 비밀번호로 로그인 한 후, 비밀번호를 변경해야 서비스를 정상적으로 이용할 수 있습니다.
        </div>
        <label>이메일</label>
        <input type='email' id="pw-recovery-input"></input>
        <button id="pw-recovery-submit">임시 비밀번호 발급</button>
    </div>
    <div class="signform-button">
        <button id="signin-submit" class="btn btn-primary">로그인</button>
        <button id="pw-recovery-button" class="btn btn-default">비밀번호를 분실하셨나요?</button>
        <button id="signin-auth-submit" class="btn btn-primary hidden">인증 완료 및 로그인</button>
        <button class="modal-cancel btn btn-default" id="signin-cancel">취소</button>
    </div>
</div>