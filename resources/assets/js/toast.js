'use strict';

const toastDiv = document.querySelector('#liveToast');
const toast = new window.bootstrap.Toast(toastDiv);

const showToast = (text) => {
    toastDiv.querySelector('.toast-body').textContent = text;

    toast.show(); 
}

module.exports = {
    showToast,
}