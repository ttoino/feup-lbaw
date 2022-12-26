import { Thread } from "../../types/thread";
import { renderList, renderSingleton } from "../../render";
import { ThreadComment } from "../../types/thread_comment";

export const renderThread = renderSingleton<Thread>("#thread");

export const renderThreadComments = renderList<ThreadComment>(
    "#thread-comment-template",
    "#thread-comments"
);
