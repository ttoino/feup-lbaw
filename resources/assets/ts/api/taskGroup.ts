import { apiFetch } from ".";

export const repositionTaskGroup = (
    taskGroupId: string,
    position: string | null
) =>
    apiFetch(`/api/task-group/${taskGroupId}/reposition`, "POST", {
        position,
    });
