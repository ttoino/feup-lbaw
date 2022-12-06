import { tryRequest } from "./api";
import { deleteProject } from "./api/project";

const attachDeletionHandler = (project: HTMLLIElement) => {
    const projectId = project.dataset.projectId;

    if (!projectId) return;

    const projectDeletionButton: HTMLButtonElement | null =
        project.querySelector("button.btn-outline-danger");

    projectDeletionButton?.addEventListener("click", async () => {
        if (
            await tryRequest(
                deleteProject,
                "Could not delete user",
                undefined,
                projectId
            )
        )
            window.location.reload();
    });
};

const projects = document.querySelectorAll<HTMLLIElement>(
    "li[data-project-id]"
);

projects.forEach(attachDeletionHandler);
