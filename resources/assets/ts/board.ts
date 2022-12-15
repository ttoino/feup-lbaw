import Sortable from "sortablejs";
import { tryRequest } from "./api";

import { repositionTask } from "./api/task";
import { repositionTaskGroup } from "./api/taskGroup";

const setupTaskGroupDnD = () => {
    const taskGroupsContainer = document.querySelector<HTMLElement>("section");

    if (!taskGroupsContainer) return;

    const sortableTaskGroups = new Sortable(taskGroupsContainer, {
        group: "taskGroups",
        handle: "header > .grip",
        animation: 150,
        easing: "ease-in-out",
        draggable: "div[data-task-group-id]",

        onEnd: async (e) => {
            const taskGroupId = e.item.dataset.taskGroupId;

            if (!taskGroupId) return;

            const newPosition = ((e.newIndex ?? 0) + 1).toString();

            console.log("task group", taskGroupId, "position", newPosition, e);

            await tryRequest(
                repositionTaskGroup,
                "Could not change task group position!",
                undefined,
                taskGroupId,
                newPosition
            );
        },
    });

    return sortableTaskGroups;
};

const setupTaskDnD = () => {
    const taskGroups = document.querySelectorAll<HTMLUListElement>(
        "ul[data-task-group-id]"
    );

    const onEnd = async (e: Sortable.SortableEvent) => {
        const taskId = e.item.dataset.taskId;

        if (!taskId) return;

        console.log(e);

        const taskGroup = e.to.dataset.taskGroupId ?? null;
        const newPosition = ((e.newIndex ?? 0) + 1).toString();

        console.log(
            "task",
            taskId,
            "task-group",
            taskGroup,
            "position",
            newPosition
        );

        await tryRequest(
            repositionTask,
            "Could not change the task position!",
            undefined,
            taskId,
            taskGroup,
            newPosition
        );
    };

    const sortableTasks = Array.prototype.map.call(
        taskGroups,
        (group) =>
            new Sortable(group, {
                group: "tasks",
                handle: ".grip",
                animation: 150,
                easing: "cubic-bezier(1, 0, 0, 1)",

                onEnd,
            })
    );

    return sortableTasks;
};

setupTaskGroupDnD();
setupTaskDnD();
