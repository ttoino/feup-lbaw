import { apiFetch } from ".";

export const deleteUser = (userId: string) =>
    apiFetch(`/api/user/${userId}`, "DELETE");
