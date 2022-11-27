import { Sortable } from "@shopify/draggable";

import { repositionTask } from "./task";
import { repositionTaskGroup } from "./taskGroup";
import { showToast } from "./toast";

const setupTaskGroupDnD = () => {
    const taskGroupsContainer =
        document.querySelectorAll<HTMLElement>("section");
    const sortableTaskGroups = new Sortable(taskGroupsContainer, {
        draggable: "section > div",
        handle: "div > .grip",
        mirror: {
            appendTo: "body",
            constrainDimensions: true,
        },
    });

    sortableTaskGroups.on("mirror:created", (e) => {
        e.mirror.style.zIndex = "100";
    });

    sortableTaskGroups.on("sortable:stop", async (e) => {
        const taskGroupId = e.dragEvent.source.dataset.taskGroupId;

        if (!taskGroupId) return;

        const newPosition = (e.newIndex + 1).toString();

        console.log("task group", taskGroupId, "position", newPosition, e);

        const res = await repositionTaskGroup(taskGroupId, newPosition);

        if (!res.ok) {
            showToast(
                `Cannot reposition task group with id ${taskGroupId} to position ${newPosition}`
            );
        }
    });
};

const setupTaskDnD = () => {
    const taskGroups = document.querySelectorAll<HTMLUListElement>(
        "ul[data-task-group-id]"
    );

    const sortableTasks = new Sortable(taskGroups, {
        draggable: "li[data-task-id]",
        handle: ".grip",
        mirror: {
            appendTo: "body",
            constrainDimensions: true,
        },
    });

    sortableTasks.on("mirror:created", (e) => {
        e.mirror.style.zIndex = "100";
    });

    sortableTasks.on("sortable:stop", async (e) => {
        const taskId = e.dragEvent.source.dataset.taskId;

        if (!taskId) return;

        const taskGroup = e.newContainer.dataset.taskGroupId ?? null;
        const newPosition = (e.newIndex + 1).toString();

        console.log(
            "task",
            taskId,
            "task-group",
            taskGroup,
            "position",
            newPosition
        );

        const res = await repositionTask(taskId, taskGroup, newPosition);

        if (!res.ok) {
            showToast(
                `Cannot reposition task with id ${taskId} to position ${newPosition} on group ${taskGroup}`
            );
        }
    });
};

setupTaskGroupDnD();
setupTaskDnD();
