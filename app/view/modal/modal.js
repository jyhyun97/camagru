export function activeModal(modalName) {
    const modalFrame = document.getElementById('modal');
    const modal = document.getElementById(modalName);
    
    modalFrame.style.visibility = 'visible';
    modal.style.display = 'block';
    
}
export function inactiveModal() {
    const modalFrame = document.getElementById('modal');
    const signform = document.getElementsByClassName('signform');

    modalFrame.style.visibility = 'hidden';
    Array.from(signform).forEach(ele => {
        ele.style.display = 'none';
    });
}

const modalBackground = document.getElementById('modal-background');
const modalCancel = document.getElementsByClassName('modal-cancel');

modalBackground.addEventListener('click', () => inactiveModal());
Array.from(modalCancel).forEach(ele => {
    const id = ele.id.replace('cancel', 'form');
    ele.addEventListener('click', () => inactiveModal());
});