import { apiFetch } from ".";

export const completeTask = (taskId: string) =>
    apiFetch(`/api/task/${taskId}/complete`, "PUT");

export const repositionTask = (
    taskId: string,
    task_group: string | null,
    position: string | null
) =>
    apiFetch(`/api/task/${taskId}/reposition`, "POST", {
        task_group,
        position,
    });
