export function activeModal(modalName) {
    const modalFrame = document.getElementById('modal');
    const modal = document.getElementById(modalName);
    if (modalFrame.style.visibility == 'visible')
    {
        modalFrame.style.visibility = 'hidden';
        modal.style.display = 'none';
    }
    else
    {
        modalFrame.style.visibility = 'visible';
        modal.style.display = 'block';
    }
}