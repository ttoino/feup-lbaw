import { User } from "../types/user";
import { apiFetch } from ".";

export const deleteUser = (userId: string) =>
    apiFetch<User>(`/api/user/${userId}`, "DELETE");

export const blockUser = (userId: string) =>
    apiFetch<User>(`/api/user/${userId}/block`, "POST");

export const unblockUser = (userId: string) =>
    apiFetch<User>(`/api/user/${userId}/unblock`, "POST");
