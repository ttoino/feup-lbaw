import { showToast } from "./toast";

const attachDeletionHandler = (user: HTMLLIElement) => {
    const userId = user.dataset.userId;

    const userDeletionButton = user.querySelector<HTMLButtonElement>(
        "button.btn-outline-danger"
    );

    userDeletionButton?.addEventListener("click", async () => {
        const res = await fetch(`/api/user/${userId}`, {
            method: "DELETE",
            headers: {
                Accept: "application/json",
            },
        });

        if (!res.ok) {
            showToast(
                `You are not authorized to delete user with id ${userId}`
            );
            return;
        }

        window.location.reload();
    });
};

const users = document.querySelectorAll<HTMLLIElement>("li[data-user-id]");

users.forEach(attachDeletionHandler);
