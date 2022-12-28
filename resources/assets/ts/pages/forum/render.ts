import { Thread } from "../../types/thread";
import { appendListItem, renderList, renderSingleton } from "../../render";
import { ThreadComment } from "../../types/thread_comment";

export const renderThread = renderSingleton<Thread>("#thread");

export const appendThread = appendListItem<Thread>(
    "#thread-template",
    ".forum-threads > ul",
    true
);

export const renderThreadComments = renderList<ThreadComment>(
    "#thread-comment-template",
    "#thread-comments"
);
