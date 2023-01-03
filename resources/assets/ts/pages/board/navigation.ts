import { getTask } from "../../api/task";
import { Offcanvas } from "bootstrap";
import { Task } from "types/task";
import { ajaxNavigation, navigation } from "../../navigation";
import { registerEnhancement } from "../../enhancements";
import { renderTask, renderTaskComments } from "./render";
import { projectId } from "../project";

const taskOffcanvasEl = document.querySelector("#task-offcanvas");
const taskOffcanvas =
    taskOffcanvasEl && Offcanvas.getOrCreateInstance(taskOffcanvasEl);

export const showBoard = navigation(
    "project.board",
    `/project/${projectId}/board`,
    () => taskOffcanvas?.hide()
);

taskOffcanvasEl?.addEventListener("hide.bs.offcanvas", (e) => {
    if (history.state?.name != "project.board") showBoard();
});

const showTask = ajaxNavigation(
    "project.task",
    getTask,
    (task: Task) => {
        taskOffcanvas?.show();

        renderTask?.(task);
        task.comments && renderTaskComments(task.comments);

        taskOffcanvasEl?.classList.remove("loading");
    },
    (e) => {
        taskOffcanvas?.show();
        taskOffcanvasEl?.classList.remove("loading");
    },
    () => {
        taskOffcanvas?.show();
        taskOffcanvasEl?.classList.add("loading");
    }
);

registerEnhancement({
    selector: ".task",
    onattach: (task) => {
        const taskId = task.dataset.taskId;
        if (!taskId) return;

        const a = task.querySelector<HTMLAnchorElement>("a.stretched-link");

        a?.addEventListener("click", (e) => {
            e.preventDefault();

            showTask(`/project/${projectId}/task/${taskId}`, taskId ?? "");
        });
    },
});
