import { getTask } from "../../api/task";
import { Offcanvas } from "bootstrap";
import { Task } from "types/task";

const projectId = document.location.pathname.split("/")[2];

const taskOffcanvasEl = document.querySelector("#task-offcanvas");
const taskOffcanvas =
    taskOffcanvasEl && Offcanvas.getOrCreateInstance(taskOffcanvasEl);

taskOffcanvasEl?.addEventListener("hide.bs.offcanvas", (e) => {
    if (history.state)
        history.pushState(undefined, "board", `/project/${projectId}/board`);
});

window.addEventListener("popstate", (e) => {
    if (e.state) showTask(e.state);
    else showBoard();
});

const taskNameEl = document.querySelector<HTMLElement>("#task-name");
const taskDescriptionEl =
    document.querySelector<HTMLElement>("#task-description");

const showTask = (task?: Task) => {
    taskOffcanvas?.show();

    if (task) {
        if (!history.state)
            history.pushState(
                task,
                "task",
                `/project/${projectId}/task/${task.id}`
            );

        if (taskNameEl) taskNameEl.innerText = task.name;
        if (taskDescriptionEl) taskDescriptionEl.innerHTML = task.description;
    }
};

const showBoard = () => {
    taskOffcanvas?.hide();
};

const tasks = document.querySelectorAll<HTMLAnchorElement>("[data-task-id]");

tasks.forEach((task) => {
    const { taskId } = task.dataset;
    const a = task.querySelector("a");

    a?.addEventListener("click", (e) => {
        e.preventDefault();

        const task = getTask(taskId ?? "");
        showTask();
        task.then(async (task) => showTask(await task.json()));
    });
});
