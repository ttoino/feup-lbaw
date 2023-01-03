import { registerEnhancement } from ".";

function oninput(this: HTMLTextAreaElement) {
    const styles = getComputedStyle(this);
    this.style.height = "0";
    this.style.height = `calc(${this.scrollHeight}px + ${styles.borderBlockStartWidth} + ${styles.borderBlockEndWidth}`;
}

const onreset = (el: HTMLTextAreaElement) => () => oninput.call(el);

registerEnhancement<HTMLTextAreaElement>({
    selector: "textarea.auto-resize",
    onattach: (e) => {
        e.addEventListener("input", oninput);
        e.form?.addEventListener("reset", onreset(e));
        oninput.call(e);
    },
    ondettach: (e) => {
        e.removeEventListener("input", oninput);
    },
});
