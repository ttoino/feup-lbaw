import { Task } from "../types/task";
import { apiFetch } from ".";

export const completeTask = (taskId: string) =>
    apiFetch<Task>(`/api/task/${taskId}/complete`, "PUT");

export const repositionTask = (
    taskId: string,
    task_group_id: string | null,
    position: string | null
) =>
    apiFetch<Task>(`/api/task/${taskId}/reposition`, "POST", {
        task_group_id,
        position,
    });

export const getTask = (taskId: string) =>
    apiFetch<Task>(`/api/task/${taskId}`);
