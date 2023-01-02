import { ajaxForm } from "../../forms";
import { registerEnhancement } from "../../enhancements";
import { newThread } from "../../api/thread";
import { projectId } from "../project";
import { Route } from "../../navigation";
import {
    appendThreadListItem,
    appendThreadComment,
    renderThread,
} from "./render";
import { showThreadOffcanvas } from "./navigation";
import { newThreadComment } from "../../api/thread_comment";

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
