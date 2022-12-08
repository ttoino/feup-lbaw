import { apiFetch } from ".";

export const deleteProject = (projectId: string) =>
    apiFetch(`/api/project/${projectId}`, "DELETE");

export const toggleFavorite = (projectId: string) => 
    apiFetch(`/api/project/${projectId}/favorite/toggle`, "POST");
