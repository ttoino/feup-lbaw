import { TaskGroup } from "../types/task_group";
import { apiFetch } from ".";

export const repositionTaskGroup = (
    taskGroupId: string,
    position: string | null
) =>
    apiFetch<TaskGroup>(`/api/task-group/${taskGroupId}/reposition`, "POST", {
        position,
    });
