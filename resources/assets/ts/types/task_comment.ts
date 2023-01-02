import { Datetime, Markdown } from "./misc";
import { Task } from "./task";
import { User } from "./user";

export interface TaskComment {
    id: number;
    content: Markdown;
    creation_date: Datetime;
    edit_date: Datetime;

    author_id: number;
    author?: User;

    task_id: number;
    task?: Task;
}
