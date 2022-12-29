import {
    deleteTaskGroup,
    editTaskGroup,
    newTaskGroup,
} from "../../api/task_group";
import { ajaxForm } from "../../forms";
import { registerEnhancement } from "../../enhancements";
import { projectId } from "../project";
import { appendTaskCard, appendTaskGroup } from "./render";
import { newTask } from "../../api/task";
import { render } from "../../render";

registerEnhancement<HTMLFormElement>({
    selector: "form#new-task-group-form",
    onattach: (form) => {
        console.log(form);
        ajaxForm(
            newTaskGroup,
            form,
            { project_id: parseInt(projectId) },
            (group) => {
                console.log(group);
                appendTaskGroup(group);
            },
            (error) => {}
        );
    },
});

// NEW TASK
registerEnhancement<HTMLElement>({
    selector: ".task-group[data-task-group-id]",
    onattach: (el) => {
        const taskGroupId = parseInt(el.dataset.taskGroupId!);
        console.log(taskGroupId);

        const appendTask = appendTaskCard(
            `.task-group[data-task-group-id="${taskGroupId}"] > ul`
        );
        const createTaskForm =
            el.querySelector<HTMLFormElement>("form.new-task-form");
        createTaskForm &&
            ajaxForm(
                newTask,
                createTaskForm,
                { task_group_id: taskGroupId },
                (task) => {
                    appendTask?.(task);
                },
                (error) => {}
            );

        const editGroupForm = el.querySelector<HTMLFormElement>(
            "form.edit-task-group-form"
        );
        editGroupForm &&
            ajaxForm(
                editTaskGroup,
                editGroupForm,
                { id: taskGroupId },
                (group) => {
                    render(editGroupForm, group);
                },
                (error) => {}
            );

        const deleteGroupForm = el.querySelector<HTMLFormElement>(
            "form.delete-task-group-form"
        );
        deleteGroupForm &&
            ajaxForm(
                deleteTaskGroup,
                deleteGroupForm,
                taskGroupId.toString(),
                (task) => {
                    el.remove();
                },
                (error) => {}
            );

        const taskList = el.querySelector(":scope > ul");
        taskList &&
            new MutationObserver(() => {
                console.log(taskList.children.length);
                deleteGroupForm?.classList.toggle(
                    "d-none",
                    taskList?.children.length !== 0
                );
            }).observe(taskList, {
                childList: true,
            });
    },
});
