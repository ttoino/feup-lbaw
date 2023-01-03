import { TaskGroup } from "../../types/task_group";
import {
    appendListItem,
    renderList,
    renderMultiple,
    renderSingleton,
    renderTemplate,
} from "../../render";
import { Task } from "../../types/task";
import { TaskComment } from "../../types/task_comment";

export const renderTask = renderMultiple(
    renderSingleton<Task>("#task"),
    renderSingleton<Task>("#new-comment-form")
);

export const renderTaskCard = (task: Task) =>
    renderSingleton(`.task[data-task-id="${task.id}"]`)?.(task);

export const renderTaskGroup = renderTemplate<TaskGroup>(
    "#task-group-template"
);

const board = document.querySelector<HTMLElement>(".project-board");
const newTaskGroupForm = board?.querySelector<HTMLElement>(
    ".task-group:last-of-type"
);

export const appendTaskGroup = (group: TaskGroup) => {
    const newGroup = renderTaskGroup?.(group);

    console.log(newGroup, board, newTaskGroupForm);

    if (newGroup && newTaskGroupForm)
        board?.insertBefore(newGroup, newTaskGroupForm);
};

export const appendTaskCard = (listSelector: string) =>
    appendListItem<Task>("#task-template", listSelector);

export const renderTaskComments = renderList<TaskComment>(
    "#task-comment-template",
    "#task-comments"
);

export const appendTaskComment = appendListItem(
    "#task-comment-template",
    "#task-comments"
);

export const renderTaskComment = (taskComment: TaskComment) =>
    renderSingleton(
        `.task-comment[data-task-comment-id="${taskComment.id}"]`
    )?.(taskComment);
