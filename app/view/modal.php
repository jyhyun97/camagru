<div id="modal">
    <div id="modal-content"></div>
    <div id="modal-background"></div>
</div>
<script type="module">
    import { activeModal } from './app/view/modal.js';
    const modalBackground = document.getElementById('modal-background');

    modalBackground.addEventListener('click', () => activeModal());
</script>