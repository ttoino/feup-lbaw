import { apiFetch } from ".";
import { TaskComment } from "../types/task_comment";

export const getTaskComment = (taskCommentId: string) =>
    apiFetch<TaskComment>(`/api/task-comment/${taskCommentId}`);

export const newTaskComment = (taskComment: TaskComment) =>
    apiFetch<TaskComment>("/api/task-comment/new", "POST", taskComment);

export const editTaskComment = (taskComment: TaskComment) =>
    apiFetch<TaskComment>(
        `/api/task-comment/${taskComment.id}`,
        "PUT",
        taskComment
    );

export const deleteTaskComment = (taskCommentId: string) =>
    apiFetch<TaskComment>(`/api/task-comment/${taskCommentId}`, "DELETE");
