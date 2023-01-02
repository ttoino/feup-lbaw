import { tryRequest } from "./api";
import { deleteUser, removeUser } from "./api/user";

const deleteListener = (userId: string) => async () => {
    if (await tryRequest(deleteUser, undefined, userId))
        window.location.reload(); // this needs to be a reload because of the use of pagination
};

const attachListItemDeletionHandler = (user: HTMLLIElement) => {
    const userId = user.dataset.userId;

    if (!userId) return;

    const userDeletionButton =
        user.querySelector<HTMLButtonElement>("button.user-delete");

    userDeletionButton?.addEventListener("click", deleteListener(userId));
};

const attachModalDeletionHandler = (modal: HTMLDivElement) => {
    const userId = modal.dataset.userId;

    if (!userId) return;

    const userDeletionButton =
        modal.querySelector<HTMLButtonElement>("button.btn-danger");

    userDeletionButton?.addEventListener("click", deleteListener(userId));
};

const removeListener = (userId: string, projectId: string) => async () => {
    if (await tryRequest(removeUser, undefined, userId, projectId))
        window.location.reload();
};

const attachListItemRemoveHandler = (user: HTMLLIElement) => {
    const userId = user.dataset.userId;
    const projectId = user.dataset.projectId;

    if (!userId) return;
    if (!projectId) return;

    const userRemoveButton =
        user.querySelector<HTMLButtonElement>("button.user-remove");

    userRemoveButton?.addEventListener(
        "click",
        removeListener(userId, projectId)
    );
};

const users = document.querySelectorAll<HTMLLIElement>("li[data-user-id]");

users.forEach(attachListItemDeletionHandler);
users.forEach(attachListItemRemoveHandler);

const deleteModal = document.querySelector<HTMLDivElement>("#delete-modal");

if (deleteModal) attachModalDeletionHandler(deleteModal);
