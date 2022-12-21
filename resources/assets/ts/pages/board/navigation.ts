import { getTask } from "../../api/task";
import { Offcanvas } from "bootstrap";
import { Task } from "types/task";
import { ajaxNavigation, navigation } from "../../navigation";

const projectId = document.location.pathname.split("/")[2];

const taskOffcanvasEl = document.querySelector("#task-offcanvas");
const taskOffcanvas =
    taskOffcanvasEl && Offcanvas.getOrCreateInstance(taskOffcanvasEl);

const showBoard = navigation(
    "project.board",
    `/project/${projectId}/board`,
    () => taskOffcanvas?.hide()
);

taskOffcanvasEl?.addEventListener("hide.bs.offcanvas", (e) => {
    if (history.state.name != "project.board") showBoard();
});

const taskNameEl = document.querySelector<HTMLElement>("#task-name");
const taskDescriptionEl =
    document.querySelector<HTMLElement>("#task-description");

const showTask = ajaxNavigation(
    "project.task",
    getTask,
    ({ name, description }: Task) => {
        taskOffcanvas?.show();
        taskOffcanvasEl?.classList.remove("loading");

        taskNameEl && (taskNameEl.innerText = name);
        taskDescriptionEl && (taskDescriptionEl.innerHTML = description);
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

const tasks = document.querySelectorAll<HTMLElement>("[data-task-id]");

tasks.forEach((task) => {
    const { taskId } = task.dataset;
    const a = task.querySelector("a");

    a?.addEventListener("click", (e) => {
        e.preventDefault();

        showTask(`/project/${projectId}/task/${taskId}`, taskId ?? "");
    });
});
