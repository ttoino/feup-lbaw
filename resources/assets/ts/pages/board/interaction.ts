import { tryRequest } from "../../api";
import { completeTask, deleteTask, incompleteTask } from "../../api/task";
import { deleteTaskGroup } from "../../api/task_group";
import { registerEnhancement } from "../../enhancements";
import { showBoard } from "./navigation";
import { renderTask, renderTaskCard } from "./render";

// DELETE TASK GROUP
registerEnhancement<HTMLElement>({
    selector: ".task-group[data-task-group-id]",
    onattach: (el) => {
        const taskGroupId = parseInt(el.dataset.taskGroupId!);
        console.log(taskGroupId);

        const deleteGroupButton = el.querySelector<HTMLButtonElement>(
            "button.delete-task-group"
        );
        deleteGroupButton?.addEventListener("click", (e) => {
            if (
                tryRequest(
                    deleteTaskGroup,
                    undefined,
                    taskGroupId.toString()
                ) !== null
            )
                el.remove();
        });

        const taskList = el.querySelector(":scope > ul");
        taskList &&
            new MutationObserver(() => {
                console.log(taskList.children.length);
                deleteGroupButton?.classList.toggle(
                    "d-none",
                    taskList?.children.length !== 0
                );
            }).observe(taskList, {
                childList: true,
            });
    },
});

// COMPLETE TASK
registerEnhancement<HTMLElement>({
    selector: "#complete-task-button",
    onattach: (el) =>
        el.addEventListener("click", async (e) => {
            const taskId = el.parentElement?.parentElement?.dataset.taskId;

            if (!taskId) return;

            const result = await tryRequest(completeTask, undefined, taskId);

            if (result) {
                renderTask?.(result);
                renderTaskCard(result);
            }
        }),
});

// INCOMPLETE TASK
registerEnhancement<HTMLElement>({
    selector: "#incomplete-task-button",
    onattach: (el) =>
        el.addEventListener("click", async (e) => {
            const taskId = el.parentElement?.parentElement?.dataset.taskId;

            if (!taskId) return;

            const result = await tryRequest(incompleteTask, undefined, taskId);

            if (result) {
                renderTask?.(result);
                renderTaskCard(result);
            }
        }),
});

// DELETE TASK
registerEnhancement<HTMLElement>({
    selector: "#delete-task-button",
    onattach: (el) =>
        el.addEventListener("click", async (e) => {
            const taskId = el.parentElement?.parentElement?.dataset.taskId;

            if (!taskId) return;

            const result = await tryRequest(deleteTask, undefined, taskId);

            if (result) {
                showBoard();
                document
                    .querySelector(`.task[data-task-id="${taskId}"]`)
                    ?.remove();
            }
        }),
});
