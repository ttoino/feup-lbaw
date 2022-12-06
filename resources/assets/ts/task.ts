import { tryRequest } from "./api";
import { completeTask } from "./api/task";

const attachCompletionHandler = (task: HTMLLIElement) => {
    const taskId = task.dataset.taskId;

    if (!taskId) return;

    const taskCompletionButton =
        task.querySelector<HTMLButtonElement>("button");

    taskCompletionButton?.addEventListener("click", async () => {
        const { state } = await tryRequest(
            completeTask,
            "Could not complete task!",
            undefined,
            taskId
        );

        if (state === "completed") {
            const icon = taskCompletionButton.querySelector("i");

            icon?.classList.replace("bi-check-circle", "bi-check-circle-fill");
        }
    });
};

const tasks = document.querySelectorAll<HTMLLIElement>("li[data-task-id]");

tasks.forEach(attachCompletionHandler);
