import { Toast } from "bootstrap";
import { registerEnhancement } from "./enhancements";
import { appendListItem } from "./render";

export const renderToast = appendListItem<{ text: string }>(
    "#toast-template",
    ".toast-container"
);

registerEnhancement({
    selector: ".toast",
    onattach: (el) => {
        const toast = Toast.getOrCreateInstance(el).show();
        el.addEventListener("hidden.bs.toast", (e) => el.remove());
    },
});
