import { tryRequest } from "./api";
import { deleteProject, toggleFavorite } from "./api/project";

const attachDeletionHandler = (project: HTMLLIElement) => {
    const projectId = project.dataset.projectId;

    if (!projectId) return;

    const projectDeletionButton: HTMLButtonElement | null =
        project.querySelector("button.project-delete");

    projectDeletionButton?.addEventListener("click", async () => {
        if (await tryRequest(deleteProject, undefined, projectId))
            window.location.reload(); // this needs to be a reload because of the use of pagination
    });
};

const attachFavoriteToggleHandler = (project: HTMLLIElement) => {
    const projectId = project.dataset.projectId;

    if (!projectId) return;

    const projectFavoriteToggleButton: HTMLButtonElement | null =
        project.querySelector("button.btn-outline.favorite-toggle");

    projectFavoriteToggleButton?.addEventListener("click", async () => {
        const response = await tryRequest(toggleFavorite, undefined, projectId);

        if (response === null) return;

        const icon = projectFavoriteToggleButton.querySelector("i");

        if (!icon) return;

        if (response.isFavorite)
            icon.classList.replace("bi-heart", "bi-heart-fill");
        else icon.classList.replace("bi-heart-fill", "bi-heart");
    });
};

const projects = document.querySelectorAll<HTMLLIElement>(
    "li[data-project-id]"
);

projects.forEach((project) => {
    attachDeletionHandler(project);
    attachFavoriteToggleHandler(project);
});
