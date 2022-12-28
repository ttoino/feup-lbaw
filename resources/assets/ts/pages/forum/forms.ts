import { ajaxForm } from "../../forms";
import { registerEnhancement } from "../../enhancements";
import { newThread } from "../../api/thread";
import { projectId } from "../project";
import { Route } from "../../navigation";
import { appendThread, renderThread } from "./render";
import { showThreadOffcanvas } from "./navigation";

registerEnhancement<HTMLFormElement>({
    selector: "#new-thread-offcanvas > form",
    onattach: (form) => {
        console.log(form);
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
                appendThread?.(thread);
            },
            (error) => {}
        );
    },
});
