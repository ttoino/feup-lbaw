import { Sortable } from "@shopify/draggable";

const taskGroupsContainer = document.querySelectorAll<HTMLElement>("section");
const sortableTaskGroups = new Sortable(taskGroupsContainer, {
    draggable: "section > div",
    handle: ".grip",
    mirror: {
        appendTo: "body",
        constrainDimensions: true,
    },
});

sortableTaskGroups.on("mirror:created", (e) => {
    e.mirror.style.zIndex = "100";
});

sortableTaskGroups.on("sortable:stop", (e) => {
    console.log("position", e.newIndex + 1);
});

const taskGroups = document.querySelectorAll<HTMLUListElement>(
    "ul[data-task-group-id]"
);

const sortableTasks = new Sortable(taskGroups, {
    draggable: "li[data-task-id]",
    mirror: {
        appendTo: "body",
        constrainDimensions: true,
    },
});

sortableTasks.on("mirror:created", (e) => {
    e.mirror.style.zIndex = "100";
});

sortableTasks.on("sortable:stop", (e) => {
    console.log(
        "task-group",
        e.newContainer.dataset.taskGroupId,
        "position",
        e.newIndex + 1
    );
});
