import {
    archiveProject,
    deleteProject,
    leaveProject,
    unarchiveProject,
} from "../../api/project";
import { tryRequest } from "../../api";
import { registerEnhancement } from "../../enhancements";
import { projectId } from "../project";

registerEnhancement<HTMLElement>({
    selector: ".project-info",
    onattach: (el) => {
        const editProjectButton = el.querySelector<HTMLButtonElement>(
            "#edit-project-button"
        );
        editProjectButton?.addEventListener("click", () =>
            el.classList.add("editing")
        );

        const archiveProjectButton = el.querySelector<HTMLButtonElement>(
            "#archive-project-button"
        );
        archiveProjectButton?.addEventListener("click", async () => {
            const result = await tryRequest(
                archiveProject,
                undefined,
                projectId
            );

            if (result !== null) window.location.reload();
        });

        const unarchiveProjectButton = el.querySelector<HTMLButtonElement>(
            "#unarchive-project-button"
        );
        unarchiveProjectButton?.addEventListener("click", async () => {
            const result = await tryRequest(
                unarchiveProject,
                undefined,
                projectId
            );

            if (result !== null) window.location.reload();
        });

        const deleteProjectButton = el.querySelector<HTMLButtonElement>(
            "#delete-project-button"
        );
        deleteProjectButton?.addEventListener("click", async () => {
            const result = await tryRequest(
                deleteProject,
                undefined,
                projectId
            );

            if (result !== null) window.location.pathname = "/project";
        });

        const leaveProjectButton = el.querySelector<HTMLButtonElement>(
            "#leave-project-button"
        );
        leaveProjectButton?.addEventListener("click", async () => {
            const result = await tryRequest(leaveProject, undefined, projectId);

            if (result !== null) window.location.pathname = "/project";
        });
    },
});
