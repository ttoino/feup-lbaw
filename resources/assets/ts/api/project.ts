import { Project } from "../types/project";
import { apiFetch } from ".";
import { Paginator } from "../types/misc";
import { User } from "../types/user";

export const toggleFavorite = (projectId: string) =>
    apiFetch<{ isFavorite: boolean }>(
        `/api/project/${projectId}/favorite/toggle`,
        "POST"
    );

export const archiveProject = (projectId: string) =>
    apiFetch<Project>(`/api/project/${projectId}/archive`, "PUT");

export const unarchiveProject = (projectId: string) =>
    apiFetch<Project>(`/api/project/${projectId}/archive`, "DELETE");

export const leaveProject = (projectId: string) =>
    apiFetch<Project>(`/api/project/${projectId}/leave`, "POST");

export const getProjectMembers = (projectId: string, cursor?: string) =>
    apiFetch<Paginator<User>>(`/api/project/${projectId}/members`, "GET", {
        cursor,
    });

export const removeProjectMember = (projectId: string, userId: string) =>
    apiFetch<Project>(
        `/api/project/${projectId}/members/remove/${userId}`,
        "DELETE"
    );

export const inviteUser = ({
    projectId,
    email,
}: {
    projectId: string;
    email: string;
}) => apiFetch<{}>(`/api/project/${projectId}/invite`, "POST", { email });

export const getProject = (projectId: string) =>
    apiFetch<Project>(`/api/project/${projectId}`);

export const newProject = (project: Project) =>
    apiFetch<Project>(`/api/project/new`, "POST", project);

export const editProject = (project: Project) =>
    apiFetch<Project>(`/api/project/${project.id}`, "PUT", project);

export const deleteProject = (projectId: string) =>
    apiFetch<Project>(`/api/project/${projectId}`, "DELETE");
