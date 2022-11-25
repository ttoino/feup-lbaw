'use strict';

const { showToast } = require('./toast');


const attachDeletionHandler = 
    (
        /** @type {HTMLLIElement} */ user
    ) => {

        const userId = user.dataset.userId;

        /** @type {HTMLButtonElement} */
        const userDeletionButton = user.querySelector("button.btn-outline-danger");

        userDeletionButton?.addEventListener("click", async () => {

            const res = await fetch(`/api/user/${userId}`, {
                method: "DELETE",
                headers: {
                    Accept: "application/json"
                }
            });

            if (!res.ok) {

                showToast(`You are not authorized to delete user with id ${userId}`);
                return;
            }

            window.location.reload();
        });
}

const users = document.querySelectorAll("li[data-user-id]");

users.forEach(attachDeletionHandler);