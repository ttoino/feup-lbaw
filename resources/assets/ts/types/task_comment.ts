import { Task } from "./task";
import { User } from "./user";

export interface TaskComment {
    id: number;
    content: string;
    creation_date: Date;
    edit_date: Date;

    author_id: number;
    author?: User;

    task_id: number;
    task?: Task;
}
