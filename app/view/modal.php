<div id="modal">
    <div id="modal-background"></div>
    <div id="modal-content">
        <?php
        include_once 'app/view/signup.php';
        include_once 'app/view/signin.php';
        ?>
    </div>
</div>
<script type="module">
    import { activeModal } from './app/view/modal.js';
    const modalBackground = document.getElementById('modal-background');
    modalBackground.addEventListener('click', () => activeModal());

    const signinForm = document.getElementById('signin-form');
    const signupForm = document.getElementById('signup-form');
    const modalCancel = document.getElementsByClassName('modal-cancel');

    Array.from(modalCancel).forEach(ele => {
        const id = ele.id.replace('cancel', 'form');
        ele.addEventListener('click', () => activeModal(id));
    });
</script>