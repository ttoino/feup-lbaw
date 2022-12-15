import { apiFetch } from ".";

export const deleteUser = (userId: string) =>
    apiFetch(`/api/user/${userId}`, "DELETE");


export const removeUser = (userId: string, projectId: string) =>
    apiFetch(`/api/project/${projectId}/remove/${userId}`, "POST");    
