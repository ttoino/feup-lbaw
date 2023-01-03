import { editTaskGroup, newTaskGroup } from "../../api/task_group";
import { ajaxForm } from "../../forms";
import { registerEnhancement } from "../../enhancements";
import { projectId } from "../project";
import {
    appendTaskCard,
    appendTaskComment,
    appendTaskGroup,
    renderTask,
    renderTaskCard,
    renderTaskComment,
} from "./render";
import { editTask, newTask } from "../../api/task";
import { render } from "../../render";
import { editTaskComment, newTaskComment } from "../../api/task_comment";

// NEW TASK GROUP
registerEnhancement<HTMLFormElement>({
    selector: "form#new-task-group-form",
    onattach: (form) =>
        ajaxForm(
            newTaskGroup,
            form,
            { project_id: parseInt(projectId) },
            (group) => appendTaskGroup(group),
            (error) => {}
        ),
});

// NEW TASK COMMENT
registerEnhancement<HTMLFormElement>({
    selector: "form#new-comment-form",
    onattach: (form) =>
        ajaxForm(
            newTaskComment,
            form,
            {},
            (taskComment) => appendTaskComment?.(taskComment),
            (error) => {}
        ),
});

// NEW TASK (ADVANCED)
registerEnhancement<HTMLFormElement>({
    selector: "form#new-task-form",
    onattach: (form) =>
        ajaxForm(
            newTask,
            form,
            {},
            (task) =>
                appendTaskCard(
                    `.task-group[data-task-group-id="${task.task_group_id}"] > ul`
                )?.(task),
            (error) => {}
        ),
});

// EDIT TASK
registerEnhancement<HTMLFormElement>({
    selector: "form#edit-task-form",
    onattach: (form) =>
        ajaxForm(
            editTask,
            form,
            {},
            (task) => {
                renderTask?.(task);
                renderTaskCard(task);
                document.querySelector("#task")?.classList.remove("editing");
            },
            (error) => {}
        ),
});

// EDIT TASK COMMENT
registerEnhancement<HTMLFormElement>({
    selector: "form.edit-task-comment-form",
    onattach: (form) =>
        ajaxForm(
            editTaskComment,
            form,
            {},
            (taskComment) => {
                renderTaskComment?.(taskComment);
                document
                    .querySelector(
                        `.task-comment[data-task-comment-id="${taskComment.id}"]`
                    )
                    ?.classList.remove("editing");
            },
            (error) => {}
        ),
});

// NEW TASK, EDIT TASK GROUP
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
    },
});
