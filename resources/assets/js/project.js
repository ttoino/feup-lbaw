'use strict';

const attachDeletionHandler = 
    (
        /** @type {HTMLLIElement} */ project
    ) => {

        const projectId = project.dataset.projectId;

        /** @type {HTMLButtonElement} */
        const projectDeletionButton = project.querySelector("button.btn-outline-danger");

        projectDeletionButton?.addEventListener("click", async () => {

            const res = await fetch(`/api/project/${projectId}`, {
                method: "DELETE"
            });

            if (!res.ok) {

                console.log('boas');

                return;
            }

            window.location.reload();
        });
}

const projects = document.querySelectorAll("li[data-project-id]");

projects.forEach(attachDeletionHandler);