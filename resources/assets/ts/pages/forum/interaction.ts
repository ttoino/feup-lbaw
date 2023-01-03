import { deleteThread } from "../../api/thread";
import { tryRequest } from "../../api";
import { registerEnhancement } from "../../enhancements";
import { showForum } from "./navigation";
import { appendThreadComments } from "./render";
import {
    deleteThreadComment,
    getThreadComments,
} from "../../api/thread_comment";

registerEnhancement<HTMLElement>({
    selector: "#thread",
    onattach: (thread) => {
        const threadId = () => thread.dataset.threadId ?? "";

        const deleteThreadButton = thread.querySelector<HTMLButtonElement>(
            "#delete-thread-button"
        );
        deleteThreadButton?.addEventListener("click", async () => {
            const result = await tryRequest(
                deleteThread,
                undefined,
                threadId()
            );

            if (result) {
                showForum();
                document
                    .querySelector(`.thread[data-thread-id="${threadId()}"]`)
                    ?.remove();
            }
        });

        const editThreadButton = thread.querySelector<HTMLButtonElement>(
            "#edit-thread-button"
        );
        editThreadButton?.addEventListener("click", () =>
            thread.classList.add("editing")
        );

        const loadCommentsButton = document.querySelector<HTMLButtonElement>(
            "#load-comments-button"
        );
        loadCommentsButton?.addEventListener("click", async () => {
            const cursor = loadCommentsButton.dataset.nextCursor;

            if (!cursor) return;

            const result = await tryRequest(
                getThreadComments,
                undefined,
                threadId(),
                cursor
            );

            if (result) appendThreadComments(result);
        });
    },
});

registerEnhancement<HTMLElement>({
    selector: ".thread-comment",
    onattach: (threadComment) => {
        const threadCommentId = () =>
            threadComment.dataset.threadCommentId ?? "";

        const deleteThreadCommentButton =
            threadComment.querySelector<HTMLButtonElement>(
                ".delete-thread-comment-button"
            );
        deleteThreadCommentButton?.addEventListener("click", async () => {
            const result = await tryRequest(
                deleteThreadComment,
                undefined,
                threadCommentId()
            );

            if (result) {
                document
                    .querySelector(
                        `.thread-comment[data-thread-comment-id="${threadCommentId()}"]`
                    )
                    ?.remove();
            }
        });

        const editThreadCommentButton =
            threadComment.querySelector<HTMLButtonElement>(
                ".edit-thread-comment-button"
            );
        editThreadCommentButton?.addEventListener("click", () =>
            threadComment.classList.add("editing")
        );
    },
});
