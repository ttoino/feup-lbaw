import { tryRequest } from "./api";
import { completeTask } from "./api/task";

const attachCompletionHandler = (task: HTMLLIElement) => {
    const taskId = task.dataset.taskId;

    if (!taskId) return;

    const taskCompletionButton =
        task.querySelector<HTMLButtonElement>("button");

    taskCompletionButton?.addEventListener("click", async () => {
        const response = await tryRequest(completeTask, undefined, taskId);

        if (response === null) return;

        if (response.completed) {
            const icon = taskCompletionButton.querySelector("i");

            icon?.classList.replace("bi-check-circle", "bi-check-circle-fill");
        }
    });
};

const tasks = document.querySelectorAll<HTMLLIElement>("li[data-task-id]");
tasks.forEach(attachCompletionHandler);

const chipSearchForm =
    document.querySelector<HTMLFormElement>(".chip-search-form");
if (chipSearchForm) {
    const query =
        chipSearchForm.querySelector<HTMLInputElement>("input[type=hidden]");

    if (query) {
        const chips = chipSearchForm.querySelectorAll<HTMLLIElement>("li");

        chips.forEach((chip) => {
            chip?.addEventListener("click", () => {
                query.value = chip.textContent?.trim() ?? "";

                chipSearchForm.submit();
            });
        });
    }
}
