import { TaskGroup } from "../types/task_group";
import { apiFetch } from ".";

export const repositionTaskGroup = (
    taskGroupId: string,
    position: string | null
) =>
    apiFetch<TaskGroup>(`/api/task-group/${taskGroupId}/reposition`, "POST", {
        position,
    });

export const newTaskGroup = (group: TaskGroup) =>
    apiFetch<TaskGroup>(`/api/task-group/new`, "POST", group);

export const editTaskGroup = (group: TaskGroup) =>
    apiFetch<TaskGroup>(`/api/task-group/${group.id}`, "PUT", group);

export const deleteTaskGroup = (taskGroupId: string) =>
    apiFetch<TaskGroup>(`/api/task-group/${taskGroupId}`, "DELETE");
