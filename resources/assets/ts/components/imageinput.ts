export const empowerImageInput = (imageInput: HTMLLabelElement) => {

    const input = imageInput.querySelector<HTMLInputElement>(
        'input[type="file"][accept^="image/"]'
    );
    const image = imageInput.querySelector<HTMLImageElement>("img");

    const updatePreview = () => {
        const reader = new FileReader();

        reader.addEventListener(
            "load",
            () => image && (image.src = reader.result?.toString() ?? "")
        );

        if (input?.files && input.files[0].type.startsWith("image/"))
            reader.readAsDataURL(input.files[0]);
    };

    const cancelEvent = (e: DragEvent) => {
        e.stopPropagation();
        e.preventDefault();
    };
    imageInput.addEventListener("dragenter", cancelEvent);
    imageInput.addEventListener("dragover", cancelEvent);
    imageInput.addEventListener("drop", (e: DragEvent) => {
        cancelEvent(e);

        const files = e.dataTransfer?.files;
        if (files?.length === 1 && files[0].type.startsWith("image/")) {
            if (input) input.files = files;
            updatePreview();
        }
    });

    input?.addEventListener("change", updatePreview);
};

const imageInputs = document.querySelectorAll<HTMLLabelElement>("label.image-input");
imageInputs.forEach(empowerImageInput);
