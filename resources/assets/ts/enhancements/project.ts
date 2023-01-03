import { tryRequest } from "../api";
import { registerEnhancement } from ".";
import { deleteProject, toggleFavorite } from "../api/project";

registerEnhancement<HTMLElement>({
    selector: "[data-project-id]",
    onattach: (el) => {
        const list = el.parentElement;
        const projectId = el.dataset.projectId!;

        const toggleFavoriteButton = el.querySelector<HTMLButtonElement>(
            "button.favorite-toggle"
        );
        toggleFavoriteButton?.addEventListener("click", async () => {
            const result = await tryRequest(
                toggleFavorite,
                undefined,
                projectId
            );

            if (result) {
                const icon = toggleFavoriteButton.querySelector("i");

                if (result.isFavorite) {
                    icon?.classList.add("bi-heart-fill");
                    icon?.classList.remove("bi-heart");
                } else {
                    icon?.classList.remove("bi-heart-fill");
                    icon?.classList.add("bi-heart");
                }
            }
        });

        const deleteProjectButton = el.querySelector<HTMLButtonElement>(
            "button.delete-project"
        );
        deleteProjectButton?.addEventListener("click", async () => {
            const result = await tryRequest(
                deleteProject,
                undefined,
                projectId
            );

            if (result) {
                el.remove();
                if (list?.childElementCount == 0) window.location.reload();
            }
        });
    },
});
