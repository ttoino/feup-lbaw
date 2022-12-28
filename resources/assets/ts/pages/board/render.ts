import { TaskGroup } from "../../types/task_group";
import { renderSingleton, renderTemplate } from "../../render";
import { Task } from "../../types/task";

export const renderTask = renderSingleton<Task>("#task-offcanvas");

export const renderTaskGroup = renderTemplate<TaskGroup>(
    "#task-group-template"
);

export const renderTaskCard = renderTemplate<Task>("#task-template");
