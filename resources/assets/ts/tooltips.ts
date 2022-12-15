import { Tooltip } from "bootstrap";

const tooltipTriggerList = document.querySelectorAll(
    '[data-bs-toggle="tooltip"]'
);
Array.prototype.map.call(
    tooltipTriggerList,
    (tooltipTriggerEl) => new Tooltip(tooltipTriggerEl)
);
