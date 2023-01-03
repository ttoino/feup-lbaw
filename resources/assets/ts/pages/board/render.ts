import { TaskGroup } from "../../types/task_group";
import {
    appendListItem,
    appendListItems,
    renderList,
    renderMultiple,
    renderSingleton,
    renderTemplate,
} from "../../render";
import { Task } from "../../types/task";
import { TaskComment } from "../../types/task_comment";
import { Paginator } from "../../types/misc";

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

const renderTaskCommentsList = renderList<TaskComment>(
    "#task-comment-template",
    "#task-comments"
);

const appendTaskCommentsList = appendListItems<TaskComment>(
    "#task-comment-template",
    "#task-comments"
);

export const renderTaskComments = renderMultiple<Paginator<TaskComment>>(
    renderSingleton("#load-comments-button"),
    (p) => renderTaskCommentsList?.(p.data)
);

export const appendTaskComments = renderMultiple<Paginator<TaskComment>>(
    renderSingleton("#load-comments-button"),
    (p) => appendTaskCommentsList?.(p.data)
);

export const appendTaskComment = appendListItem(
    "#task-comment-template",
    "#task-comments"
);

export const renderTaskComment = (taskComment: TaskComment) =>
    renderSingleton(
        `.task-comment[data-task-comment-id="${taskComment.id}"]`
    )?.(taskComment);
