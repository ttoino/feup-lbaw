import {
    inviteUser,
    removeProjectMember,
    setCoordinator,
} from "../api/project";
import { projectId } from "../pages/project";
import { tryRequest } from "../api";
import { blockUser, deleteUser, unblockUser } from "../api/user";
import { registerEnhancement } from "../enhancements";
import { ajaxForm } from "../forms";
import { renderToast } from "../toast";

registerEnhancement<HTMLElement>({
    selector: "[data-user-id]",
    onattach: (el) => {
        const list = el.parentElement;
        const userId = el.dataset.userId!;

        const removeUserButton =
            el.querySelector<HTMLButtonElement>("button.remove-user");
        removeUserButton?.addEventListener("click", async () => {
            const result = await tryRequest(
                removeProjectMember,
                undefined,
                projectId,
                userId
            );

            if (result) {
                el.remove();
            }
        });

        const setCoordinatorButton = el.querySelector<HTMLButtonElement>(
            "button.set-coordinator"
        );
        setCoordinatorButton?.addEventListener("click", async () => {
            const result = await tryRequest(
                setCoordinator,
                undefined,
                projectId,
                userId
            );

            if (result) {
                window.location.reload();
            }
        });

        const deleteUserButton =
            el.querySelector<HTMLButtonElement>("button.delete-user");
        deleteUserButton?.addEventListener("click", async () => {
            const result = await tryRequest(deleteUser, undefined, userId);

            if (result) {
                el.remove();
                if (list?.childElementCount == 0) window.location.reload();
            }
        });

        const blockUserButton =
            el.querySelector<HTMLButtonElement>("button.block-user");
        blockUserButton?.addEventListener("click", async () => {
            const result = await tryRequest(blockUser, undefined, userId);

            if (result) {
                window.location.reload();
            }
        });

        const unblockUserButton = el.querySelector<HTMLButtonElement>(
            "button.unblock-user"
        );
        unblockUserButton?.addEventListener("click", async () => {
            const result = await tryRequest(unblockUser, undefined, userId);

            if (result) {
                window.location.reload();
            }
        });
    },
});

registerEnhancement<HTMLFormElement>({
    selector: "form.invite-user-form",
    onattach: (el) =>
        ajaxForm(
            inviteUser,
            el,
            { projectId: projectId },
            () => {
                renderToast?.({ text: "Invited user" });
            },
            (e) => { }
        ),
});

// TODO: Move this to user page script
registerEnhancement<HTMLElement>({
    selector: "main",
    onattach: (el) => {
        const userId = /user\/(\d+)/.exec(location.pathname)?.[0][1];

        if (!userId) return;

        const deleteUserButton =
            el.querySelector<HTMLButtonElement>("button.delete-user");

        deleteUserButton?.addEventListener("click", async () => {
            const result = await tryRequest(deleteUser, undefined, userId);

            if (result)
                window.location.reload();
        });
    }
})
