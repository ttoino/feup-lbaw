import { markNotificationAsRead } from "../../api/notification";
import { tryRequest } from "../../api";
import { registerEnhancement } from "../../enhancements";

registerEnhancement<HTMLButtonElement>({
    selector: "[data-notification-id] > button.read-notification-button",
    onattach: (el) => {
        const notificationId = el.parentElement!.dataset.notificationId!;

        el.addEventListener("click", async () => {
            const result = await tryRequest(
                markNotificationAsRead,
                undefined,
                notificationId
            );

            if (result) {
                const list = el.parentElement?.parentElement;
                el.parentElement?.remove();

                if (list?.childElementCount == 0) window.location.reload();
            }
        });
    },
});
