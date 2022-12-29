import { registerEnhancement } from ".";

function onInput(this: HTMLTextAreaElement) {
    this.value = this.value.replace(/\n/g, "");
}

function onKeypress(this: HTMLTextAreaElement, e: KeyboardEvent) {
    if (e.key != "Enter") return;

    e.preventDefault();
    this.form?.requestSubmit();
}

registerEnhancement<HTMLTextAreaElement>({
    selector: "textarea.single-line",
    onattach: (e) => {
        e.addEventListener("input", onInput);
        e.addEventListener("keypress", onKeypress);
        onInput.call(e);
    },
    ondettach: (e) => {
        e.removeEventListener("input", onInput);
        e.removeEventListener("keypress", onKeypress);
    },
});
