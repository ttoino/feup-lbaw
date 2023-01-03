import { projectId } from "../project";
import { registerEnhancement } from "../../enhancements";
import { editProject } from "../../api/project";
import { ajaxForm } from "../../forms";
import { renderProject } from "./render";

// EDIT PROJECT
registerEnhancement<HTMLFormElement>({
    selector: "form#edit-project-form",
    onattach: (form) =>
        ajaxForm(
            editProject,
            form,
            { id: parseInt(projectId) },
            (project) => {
                renderProject?.(project);
                document
                    .querySelector(".project-info .left")
                    ?.classList.remove("editing");
            },
            (error) => {}
        ),
});
