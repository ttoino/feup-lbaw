import { Thread } from "../../types/thread";
import {
    appendListItem,
    appendListItems,
    renderList,
    renderMultiple,
    renderSingleton,
} from "../../render";
import { ThreadComment } from "../../types/thread_comment";
import { Paginator } from "../../types/misc";

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

export const renderThreadComment = (threadComment: ThreadComment) =>
    renderSingleton(
        `.thread-comment[data-thread-comment-id="${threadComment.id}"]`
    )?.(threadComment);

const renderThreadCommentsList = renderList<ThreadComment>(
    "#thread-comment-template",
    "#thread-comments"
);

const appendThreadCommentsList = appendListItems<ThreadComment>(
    "#thread-comment-template",
    "#thread-comments"
);

export const renderThreadComments = renderMultiple<Paginator<ThreadComment>>(
    renderSingleton("#load-comments-button"),
    (p) => renderThreadCommentsList?.(p.data)
);

export const appendThreadComments = renderMultiple<Paginator<ThreadComment>>(
    renderSingleton("#load-comments-button"),
    (p) => appendThreadCommentsList?.(p.data)
);

export const appendThreadComment = appendListItem<ThreadComment>(
    "#thread-comment-template",
    "#thread-comments"
);
