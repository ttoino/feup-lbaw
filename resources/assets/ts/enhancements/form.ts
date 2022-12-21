import { registerEnhancement } from ".";

function onsubmit(this: HTMLFormElement, e: SubmitEvent) {
    if (!this.checkValidity()) {
        e.preventDefault();
        e.stopPropagation();
    }

    this.classList.add("was-validated");
}

registerEnhancement<HTMLFormElement>({
    selector: "form.needs-validation",
    onattach: (e) => {
        e.addEventListener("submit", onsubmit);
    },
    ondettach: (e) => {
        e.removeEventListener("submit", onsubmit);
    },
});
