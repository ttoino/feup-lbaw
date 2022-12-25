import { Tooltip } from "bootstrap";
import { registerEnhancement } from ".";

registerEnhancement<HTMLElement>({
    selector: '[data-bs-toggle="tooltip"]',
    onattach: (e) => {
        new Tooltip(e);
    },
});
