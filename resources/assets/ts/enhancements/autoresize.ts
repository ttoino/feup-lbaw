import { registerEnhancement } from ".";

function oninput(this: HTMLTextAreaElement) {
    const styles = getComputedStyle(this);
    this.style.height = "0";
    this.style.height = `calc(${this.scrollHeight}px + ${styles.borderBlockStartWidth} + ${styles.borderBlockEndWidth}`;
}

registerEnhancement<HTMLTextAreaElement>({
    selector: "textarea.auto-resize",
    onattach: (e) => {
        e.addEventListener("input", oninput);
        e.addEventListener("reset", oninput);
        oninput.call(e);
    },
    ondettach: (e) => {
        e.removeEventListener("input", oninput);
        e.removeEventListener("reset", oninput);
    },
});
