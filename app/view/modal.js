export function activeModal() {
    const modal = document.getElementById('modal');
    if (modal.style.visibility == 'visible')
        modal.style.visibility = 'hidden'
    else
        modal.style.visibility = 'visible';
}