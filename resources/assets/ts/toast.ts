import { Toast } from "bootstrap";

const toastDiv = document.querySelector("#liveToast");
const toast = toastDiv ? new Toast(toastDiv) : null;

export const showToast = (text: string) => {
    const body = toastDiv?.querySelector(".toast-body");
    if (body) body.textContent = text;

    toast?.show();
};
