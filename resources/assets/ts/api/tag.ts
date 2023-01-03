import { Tag } from "../types/tag";
import { apiFetch } from ".";

export const getTag = (tagId: string) => apiFetch<Tag>(`/api/tag/${tagId}`);

export const newTag = (tag: Tag) => apiFetch<Tag>(`/api/tag/new`, "POST", tag);

export const editTag = (tag: Tag) =>
    apiFetch<Tag>(`/api/tag/${tag.id}`, "PUT", tag);

export const deleteTag = (tagId: string) =>
    apiFetch<Tag>(`/api/tag/${tagId}`, "DELETE");
