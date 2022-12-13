import { tryRequest } from "./api";
import { deleteUser } from "./api/user";

const deleteListener = (userId: string) => async () => {
    if (
        await tryRequest(deleteUser, "Could not delete user", undefined, userId)
    )
        window.location.reload();
};

const attachListItemDeletionHandler = (user: HTMLLIElement) => {
    const userId = user.dataset.userId;

    if (!userId) return;

    const userDeletionButton = user.querySelector<HTMLButtonElement>(
        "button.user-delete"
    );

    userDeletionButton?.addEventListener("click", deleteListener(userId));
};

const attachModalDeletionHandler = (model: HTMLDivElement) => {
    const userId = model.dataset.userId;

    if (!userId) return;

    const userDeletionButton =
        model.querySelector<HTMLButtonElement>("button.btn-danger");

    userDeletionButton?.addEventListener("click", deleteListener(userId));
};

const users = document.querySelectorAll<HTMLLIElement>("li[data-user-id]");

users.forEach(attachListItemDeletionHandler);

const deleteModal = document.querySelector<HTMLDivElement>("#delete-modal");

if (deleteModal) attachModalDeletionHandler(deleteModal);
