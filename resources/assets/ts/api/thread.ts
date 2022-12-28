import { Thread } from "../types/thread";
import { apiFetch } from ".";

export const getThread = (threadId: string) =>
    apiFetch<Thread>(`/api/thread/${threadId}`);

export const newThread = (thread: Thread) =>
    apiFetch<Thread>("/api/thread/new", "POST", thread);
