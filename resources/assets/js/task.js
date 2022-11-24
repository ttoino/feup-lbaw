'use strict';

// TODO: change after prototype
const toastDiv = document.querySelector('#liveToast');
const toast = new window.bootstrap.Toast(toastDiv);

const attachCompletionHandler = 
    (
        /** @type {HTMLLIElement} */ task
    ) => {

        const taskId = task.dataset.taskId;

        /** @type {HTMLButtonElement} */
        const taskCompletionButton = task.querySelector("button");

        taskCompletionButton.addEventListener("click", async () => {

            const res = await fetch(`/api/task/${taskId}/complete`, {
                method: "PUT"
            });

            if (!res.ok) {      

                toastDiv.querySelector('.toast-body').textContent = `You are not authorized to complete task with id ${taskId}`;

                toast.show();       
                return;
            }

            const { state } = await res.json();

            if (state === "completed") {

                const icon = taskCompletionButton.querySelector("i");

                icon.classList.replace("bi-check-circle", "bi-check-circle-fill");
            }
        });
}

const tasks = document.querySelectorAll("li[data-task-id]");

tasks.forEach(attachCompletionHandler);