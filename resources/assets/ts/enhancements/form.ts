import { registerEnhancement } from ".";

function onreset(this: HTMLFormElement, e: Event) {
    this.classList.remove("was-validated");
}

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
        e.addEventListener("reset", onreset);
    },
    ondettach: (e) => {
        e.removeEventListener("submit", onsubmit);
        e.removeEventListener("reset", onreset);
    },
});
