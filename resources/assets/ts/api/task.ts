import { Task } from "../types/task";
import { apiFetch } from ".";

export const completeTask = (taskId: string) =>
    apiFetch<Task>(`/api/task/${taskId}/complete`, "PUT");

export const incompleteTask = (taskId: string) =>
    apiFetch<Task>(`/api/task/${taskId}/complete`, "DELETE");

export const repositionTask = (
    taskId: string,
    task_group_id: string,
    position: string
) =>
    apiFetch<Task>(`/api/task/${taskId}/reposition`, "POST", {
        task_group_id,
        position,
    });

export const getTask = (taskId: string) =>
    apiFetch<Task>(`/api/task/${taskId}`);

export const newTask = (task: Task) =>
    apiFetch<Task>(`/api/task/new`, "POST", task);

export const editTask = (task: Task) =>
    apiFetch<Task>(`/api/task/${task.id}`, "PUT", task);

export const deleteTask = (taskId: string) =>
    apiFetch<Task>(`/api/task/${taskId}`, "DELETE");
