import { ajaxForm } from "../../forms";
import { registerEnhancement } from "../../enhancements";
import { editThread, newThread } from "../../api/thread";
import { projectId } from "../project";
import { Route } from "../../navigation";
import {
    appendThreadListItem,
    appendThreadComment,
    renderThread,
    renderThreadListItem,
    renderThreadComment,
} from "./render";
import { showThreadOffcanvas } from "./navigation";
import { editThreadComment, newThreadComment } from "../../api/thread_comment";

registerEnhancement<HTMLFormElement>({
    selector: "#new-thread-offcanvas > form",
    onattach: (form) => {
        ajaxForm(
            newThread,
            form,
            { project_id: parseInt(projectId) },
            (thread) => {
                const state: Route<any> = {
                    name: "project.thread",
                    state: "ok",
                    data: thread,
                };

                history.pushState(
                    state,
                    "",
                    `/project/${projectId}/thread/${thread.id}`
                );

                showThreadOffcanvas();
                renderThread?.(thread);
                appendThreadListItem?.(thread);
            },
            (error) => {}
        );
    },
});

registerEnhancement<HTMLFormElement>({
    selector: "form#new-comment-form",
    onattach: (form) => {
        ajaxForm(
            newThreadComment,
            form,
            {},
            (threadComment) => {
                appendThreadComment?.(threadComment);
            },
            (error) => {}
        );
    },
});

// EDIT THREAD
registerEnhancement<HTMLFormElement>({
    selector: "form#edit-thread-form",
    onattach: (form) =>
        ajaxForm(
            editThread,
            form,
            {},
            (thread) => {
                renderThread?.(thread);
                renderThreadListItem(thread);
                document.querySelector("#thread")?.classList.remove("editing");
            },
            (error) => {}
        ),
});

// EDIT THREAD COMMENT
registerEnhancement<HTMLFormElement>({
    selector: "form.edit-thread-comment-form",
    onattach: (form) =>
        ajaxForm(
            editThreadComment,
            form,
            {},
            (threadComment) => {
                renderThreadComment?.(threadComment);
                document
                    .querySelector(
                        `.thread-comment[data-thread-comment-id="${threadComment.id}"]`
                    )
                    ?.classList.remove("editing");
            },
            (error) => {}
        ),
});
