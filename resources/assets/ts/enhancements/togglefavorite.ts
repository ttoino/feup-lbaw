import { tryRequest } from "../api";
import { registerEnhancement } from ".";
import { toggleFavorite } from "../api/project";

registerEnhancement<HTMLButtonElement>({
    selector: "[data-project-id] > button.favorite-toggle",
    onattach: (el) => {
        const projectId = el.parentElement?.dataset.projectId!;
        const icon = el.querySelector("i");

        el.addEventListener("click", async () => {
            const result = await tryRequest(
                toggleFavorite,
                undefined,
                projectId
            );

            console.log(result);

            if (result) {
                if (result.isFavorite) {
                    icon?.classList.add("bi-heart-fill");
                    icon?.classList.remove("bi-heart");
                } else {
                    icon?.classList.remove("bi-heart-fill");
                    icon?.classList.add("bi-heart");
                }
            }
        });
    },
});
