import { newTaskGroup } from "../../api/task_group";
import { ajaxForm } from "../../forms";
import { registerEnhancement } from "../../enhancements";
import { projectId } from "../project";
import { appendTaskCard, appendTaskGroup } from "./render";
import { newTask } from "../../api/task";

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

registerEnhancement<HTMLFormElement>({
    selector: "form.new-task-form",
    onattach: (form) => {
        const taskGroupId = form.parentElement?.dataset.taskGroupId;

        if (!taskGroupId) return;

        const appendTask = appendTaskCard(
            `.task-group[data-task-group-id="${taskGroupId}"] > ul`
        );

        if (!appendTask) return;

        ajaxForm(
            newTask,
            form,
            { task_group_id: parseInt(taskGroupId) },
            (task) => {
                console.log(task);
                appendTask(task);
            },
            (error) => {}
        );
    },
});
