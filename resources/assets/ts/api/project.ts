import { Project } from "../types/project";
import { apiFetch } from ".";

export const getProject = (projectId: string) =>
    apiFetch<Project>(`/api/project/${projectId}`);

export const deleteProject = (projectId: string) =>
    apiFetch<Project>(`/api/project/${projectId}`, "DELETE");

export const toggleFavorite = (projectId: string) =>
    apiFetch<{ isFavorite: boolean }>(
        `/api/project/${projectId}/favorite/toggle`,
        "POST"
    );
