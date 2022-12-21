import { registerEnhancement } from ".";

registerEnhancement<HTMLElement>({
    selector: ".password-input",
    onattach: (element) => {
        const input = element.querySelector<HTMLInputElement>(
            "input[type=password]"
        );
        const toggle = element.querySelector("button");

        if (!input || !toggle) return;

        toggle.addEventListener("click", (e) => {
            input.type = input.type == "password" ? "text" : "password";
        });
    },
});
