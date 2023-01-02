import { Thread } from "../../types/thread";
import {
    appendListItem,
    renderList,
    renderMultiple,
    renderSingleton,
} from "../../render";
import { ThreadComment } from "../../types/thread_comment";

export const renderThread = renderMultiple(
    renderSingleton<Thread>("#thread"),
    renderSingleton<Thread>("#new-comment-form")
);

export const appendThreadListItem = appendListItem<Thread>(
    "#thread-template",
    ".forum-threads > ul",
    true
);

export const renderThreadListItem = (thread: Thread) =>
    renderSingleton(`.thread[data-thread-id="${thread.id}"]`)?.(thread);

export const renderThreadComments = renderList<ThreadComment>(
    "#thread-comment-template",
    "#thread-comments"
);

export const appendThreadComment = appendListItem<ThreadComment>(
    "#thread-comment-template",
    "#thread-comments"
);
