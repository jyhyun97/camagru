<div id="modal">
    <div id="modal-background"></div>
    <div id="modal-content">
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
            <div class="signform-button">
                <button id="signin-submit" class="btn btn-primary">로그인</button>
                <button class="modal-cancel btn btn-default" id="signin-cancel">취소</button>
            </div>
        </div>
        <div class="signform" id="signup-form">
            <h2>회원가입</h2>
            <div class="signform-line">
                <label>이메일</label>
                <input type="email" id="signup-email" name="email">
            </div>
            <div class="signform-line">
                <label>닉네임</label>
                <input type="text" id="signup-username" name="username">
            </div>
            <div class="signform-line">
                <label>비밀번호</label>
                <input type="password" id="signup-password" name="password">
            </div>
            <div class="signform-button">
                <button id="signup-submit" class="btn btn-primary">회원가입</button>
                <button class="modal-cancel btn btn-default" id="signup-cancel">취소</button>
            </div>
        </div>
    </div>
</div>
<script src="/app/view/modal/modal.js" type="module"></script>