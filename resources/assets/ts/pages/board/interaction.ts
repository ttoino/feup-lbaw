import { deleteTaskComment } from "../../api/task_comment";
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
        deleteGroupButton?.addEventListener("click", () => {
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

registerEnhancement<HTMLElement>({
    selector: "#task",
    onattach: (task) => {
        const taskId = () => task.dataset.taskId ?? "";

        const completeTaskButton = task.querySelector<HTMLButtonElement>(
            "#complete-task-button"
        );
        completeTaskButton?.addEventListener("click", async () => {
            const result = await tryRequest(completeTask, undefined, taskId());

            if (result) {
                renderTask?.(result);
                renderTaskCard(result);
            }
        });

        const incompleteTaskButton = task.querySelector<HTMLButtonElement>(
            "#incomplete-task-button"
        );
        incompleteTaskButton?.addEventListener("click", async () => {
            const result = await tryRequest(
                incompleteTask,
                undefined,
                taskId()
            );

            if (result) {
                renderTask?.(result);
                renderTaskCard(result);
            }
        });

        const deleteTaskButton = task.querySelector<HTMLButtonElement>(
            "#delete-task-button"
        );
        deleteTaskButton?.addEventListener("click", async () => {
            const result = await tryRequest(deleteTask, undefined, taskId());

            if (result) {
                showBoard();
                document
                    .querySelector(`.task[data-task-id="${taskId()}"]`)
                    ?.remove();
            }
        });

        const editTaskButton =
            task.querySelector<HTMLButtonElement>("#edit-task-button");
        editTaskButton?.addEventListener("click", () =>
            task.classList.add("editing")
        );
    },
});

registerEnhancement<HTMLElement>({
    selector: ".task-comment",
    onattach: (taskComment) => {
        const taskCommentId = () => taskComment.dataset.taskCommentId ?? "";

        const deleteTaskCommentButton =
            taskComment.querySelector<HTMLButtonElement>(
                ".delete-task-comment-button"
            );
        deleteTaskCommentButton?.addEventListener("click", async () => {
            const result = await tryRequest(
                deleteTaskComment,
                undefined,
                taskCommentId()
            );

            if (result) {
                document
                    .querySelector(
                        `.task-comment[data-task-comment-id="${taskCommentId()}"]`
                    )
                    ?.remove();
            }
        });

        const editTaskCommentButton =
            taskComment.querySelector<HTMLButtonElement>(
                ".edit-task-comment-button"
            );
        editTaskCommentButton?.addEventListener("click", () =>
            taskComment.classList.add("editing")
        );
    },
});
