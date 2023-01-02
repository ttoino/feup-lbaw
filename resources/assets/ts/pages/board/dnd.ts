import Sortable from "sortablejs";

import { registerEnhancement } from "../../enhancements";
import { tryRequest } from "../../api";
import { repositionTask } from "../../api/task";
import { repositionTaskGroup } from "../../api/task_group";

registerEnhancement({
    selector: ".project-board",
    onattach: (taskGroupsContainer) => {
        new Sortable(taskGroupsContainer, {
            group: "taskGroups",
            handle: ".task-group > header > .grip",
            animation: 150,
            easing: "ease-in-out",
            draggable: ".task-group",

            onEnd: async (e) => {
                const taskGroupId = e.item.dataset.taskGroupId;

                if (!taskGroupId) return;

                const newPosition = ((e.newIndex ?? 0) + 1).toString();

                console.log(
                    "task group",
                    taskGroupId,
                    "position",
                    newPosition,
                    e
                );

                await tryRequest(
                    repositionTaskGroup,
                    undefined,
                    taskGroupId,
                    newPosition
                );
            },
        });
    },
});

const onTaskMove = async (e: Sortable.SortableEvent) => {
    const taskId = e.item.dataset.taskId;
    if (!taskId) return;

    const taskGroup = e.to.parentElement?.dataset.taskGroupId;
    if (!taskGroup) return;

    const newPosition = ((e.newIndex ?? 0) + 1).toString();

    console.log(
        "task",
        taskId,
        "task-group",
        taskGroup,
        "position",
        newPosition
    );

    await tryRequest(repositionTask, undefined, taskId, taskGroup, newPosition);
};

registerEnhancement({
    selector: ".task-group > ul",
    onattach: (group) => {
        new Sortable(group, {
            group: "tasks",
            handle: ".grip",
            animation: 150,
            easing: "cubic-bezier(1, 0, 0, 1)",
            onEnd: onTaskMove,
        });
    },
});
