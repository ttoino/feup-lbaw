import Sortable, { SortableEvent } from "sortablejs";

import { registerEnhancement } from "../../enhancements";
import { tryRequest } from "../../api";
import { repositionTask } from "../../api/task";
import { repositionTaskGroup } from "../../api/task_group";

const undo = (e: SortableEvent) => {
    e.item.remove();
    e.from.insertBefore(e.item, e.from.children[e.oldIndex!]);
};

registerEnhancement({
    selector: ".project-board",
    onattach: (taskGroupsContainer) => {
        new Sortable(taskGroupsContainer, {
            group: "taskGroups",
            handle: ".task-group > header > .grip",
            animation: 150,
            easing: "ease-in-out",
            draggable: ".task-group[data-task-group-id]",

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

                const result = await tryRequest(
                    repositionTaskGroup,
                    undefined,
                    taskGroupId,
                    newPosition
                );

                if (result === null) undo(e);
            },
        });
    },
});

const onTaskMove = async (e: SortableEvent) => {
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

    const result = await tryRequest(
        repositionTask,
        undefined,
        taskId,
        taskGroup,
        newPosition
    );

    if (result === null) undo(e);
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
