import { apiFetch } from ".";

export const deleteProject = (projectId: string) =>
    apiFetch(`/api/project/${projectId}`, "DELETE");
