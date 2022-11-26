import { showToast } from "./toast";

const attachCompletionHandler = (task: HTMLLIElement) => {
    const taskId = task.dataset.taskId;

    const taskCompletionButton =
        task.querySelector<HTMLButtonElement>("button");

    taskCompletionButton?.addEventListener("click", async () => {
        const res = await fetch(`/api/task/${taskId}/complete`, {
            method: "PUT",
        });

        if (!res.ok) {
            showToast(
                `You are not authorized to complete task with id ${taskId}`
            );
            return;
        }

        const { state } = await res.json();

        if (state === "completed") {
            const icon = taskCompletionButton.querySelector("i");

            icon?.classList.replace("bi-check-circle", "bi-check-circle-fill");
        }
    });
};

const tasks = document.querySelectorAll<HTMLLIElement>("li[data-task-id]");

tasks.forEach(attachCompletionHandler);
