import { User } from "../types/user";
import { apiFetch } from ".";

export const deleteUser = (userId: string) =>
    apiFetch<User>(`/api/user/${userId}`, "DELETE");

export const removeUser = (userId: string, projectId: string) =>
    apiFetch<User>(`/api/project/${projectId}/remove/${userId}`, "POST");
