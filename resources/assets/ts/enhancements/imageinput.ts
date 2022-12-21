import { registerEnhancement } from ".";

function cancelEvent(e: Event) {
    e.stopPropagation();
    e.preventDefault();
}

registerEnhancement<HTMLLabelElement>({
    selector: "label.image-input",
    onattach: (imageInput) => {
        const input = imageInput.querySelector<HTMLInputElement>(
            'input[type="file"][accept^="image/"]'
        );
        const image = imageInput.querySelector<HTMLImageElement>("img");

        if (!input || !image) return;

        const updatePreview = () => {
            const reader = new FileReader();

            reader.addEventListener(
                "load",
                () => (image.src = reader.result?.toString() ?? "")
            );

            if (input.files && input.files[0].type.startsWith("image/"))
                reader.readAsDataURL(input.files[0]);
        };

        imageInput.addEventListener("dragenter", cancelEvent);
        imageInput.addEventListener("dragover", cancelEvent);
        imageInput.addEventListener("drop", (e) => {
            cancelEvent(e);

            const files = e.dataTransfer?.files;
            if (files?.length === 1 && files[0].type.startsWith("image/")) {
                if (input) input.files = files;
                updatePreview();
            }
        });

        input.addEventListener("change", updatePreview);
    },
    // TODO: ondettach
});
