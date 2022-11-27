import { showToast } from "./toast";

export const completeTest = (taskId: string) =>
    fetch(`/api/task/${taskId}/complete`, {
        method: "PUT",
    });

export const repositionTask = (
    taskId: string,
    task_group: string | null,
    position: string | null
) =>
    fetch(`/api/task/${taskId}/reposition`, {
        method: "POST",
        body: JSON.stringify({
            task_group,
            position,
        }),
        headers: {
            "Content-Type": "application/json",
        },
    });

const attachCompletionHandler = (task: HTMLLIElement) => {
    const taskId = task.dataset.taskId;

    if (!taskId) return;

    const taskCompletionButton =
        task.querySelector<HTMLButtonElement>("button");

    taskCompletionButton?.addEventListener("click", async () => {
        const res = await completeTest(taskId);

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
