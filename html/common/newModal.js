async function handleSubmit(event) {
    event.preventDefault();
    
    const result = await callModal();
    if (result) {
        document.getElementById('registerForm').submit();
    }
    return false;
}

async function callModal() {
    const modal = new bootstrap.Modal(document.getElementById("exampleModal"));
    modal.show();

    const buttonId = await waitForButtonPress(['confirm_button', 'cancel_button']);
    return buttonId === 'confirm_button';
}

function waitForButtonPress(buttonIds) {
    return new Promise(resolve => {
        buttonIds.forEach(buttonId => {
            const button = document.getElementById(buttonId);
            button.addEventListener('click', function onClick() {
                buttonIds.forEach(id => document.getElementById(id).removeEventListener('click', onClick));
                resolve(buttonId);
            });
        });
    });
}
