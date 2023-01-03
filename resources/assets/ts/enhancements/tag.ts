import { tryRequest } from "../api";
import { deleteTag, editTag, newTag } from "../api/tag";
import { registerEnhancement } from ".";
import { ajaxForm } from "../forms";
import { render } from "../render";
import { projectId } from "../pages/project";

registerEnhancement({
    selector: "[data-tag-id]",
    onattach: (el) => {
        const tagId = el.dataset.tagId!;
        const list = el.parentElement;

        const deleteTagButton =
            el.querySelector<HTMLButtonElement>("button.delete-tag");
        deleteTagButton?.addEventListener("click", async () => {
            const result = await tryRequest(deleteTag, undefined, tagId);

            if (result) {
                el.remove();
                if (list?.childElementCount == 0) window.location.reload();
            }
        });

        const editTagButton =
            el.querySelector<HTMLButtonElement>("button.edit-tag");
        editTagButton?.addEventListener("click", async () => {
            el.classList.add("editing");
        });

        const editTagForm =
            el.querySelector<HTMLFormElement>("form.edit-tag-form");
        if (editTagForm)
            ajaxForm(
                editTag,
                editTagForm,
                { id: parseInt(tagId) },
                (tag) => {
                    render(el, tag);
                    el.classList.remove("editing");
                },
                (e) => {}
            );
    },
});

registerEnhancement<HTMLFormElement>({
    selector: "form.new-tag-form",
    onattach: (el) =>
        ajaxForm(
            newTag,
            el,
            { project_id: parseInt(projectId) },
            (tag) => {
                window.location.reload();
            },
            (e) => {}
        ),
});
