<div class="signform" id="signup-form">
    <h2>회원가입</h2>
    <div class="signform-line">
        <label>이메일</label>
        <input type="email" id="signup-email" name="email" placeholder="aa@aa.aa">
        <div id='signup-email-info'class="alert alert-info" hidden>이메일은 30글자 이하로 @문자를 포함해야 합니다.</div>
    </div>
    <div class="signform-line">
        <label>닉네임</label>
        <input type="text" id="signup-username" name="username">
        <div id='signup-username-info' class="alert alert-info" hidden>닉네임은 5~20글자 사이의 영숫자만 허용됩니다.</div>
    </div>
    <div class="signform-line">
        <label>비밀번호</label>
        <input type="password" id="signup-password" name="password">
        <div id='signup-password-info' class="alert alert-info" hidden>비밀번호는 8~20글자 사이의 영숫자, 특수문자만 허용됩니다.</div>
    </div>
    <div class="signform-button">
        <button id="signup-submit" class="btn btn-primary">회원가입</button>
        <button class="modal-cancel btn btn-default" id="signup-cancel">취소</button>
    </div>
</div>