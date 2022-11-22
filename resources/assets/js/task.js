'use strict';

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
                // TODO: handle error
                console.log("ASLDGFASEG0RHI");
                return;
            }

            const { state } = await res.json();

            if (state === "completed") {

                const icon = taskCompletionButton.querySelector("i");

                icon.classList.replace("bi-heart", "bi-heart-fill");
            }
        });
}

const tasks = document.querySelectorAll("li[data-task-id]");

tasks.forEach(attachCompletionHandler);