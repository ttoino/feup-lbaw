import { showToast } from "./toast";

const attachDeletionHandler = (project: HTMLLIElement) => {
    const projectId = project.dataset.projectId;

    const projectDeletionButton: HTMLButtonElement | null = project.querySelector(
        "button.btn-outline-danger"
    );

    projectDeletionButton?.addEventListener("click", async () => {
        const res = await fetch(`/api/project/${projectId}`, {
            method: "DELETE",
        });

        if (!res.ok) {
            showToast(
                `You are not authorized to delete project with id ${projectId}`
            );
            return;
        }

        window.location.reload();
    });
};

const projects = document.querySelectorAll<HTMLLIElement>(
    "li[data-project-id]"
);

projects.forEach(attachDeletionHandler);
