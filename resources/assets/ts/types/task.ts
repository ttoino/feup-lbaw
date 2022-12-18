import { Tag } from "./tag";
import { TaskComment } from "./task_comment";
import { User } from "./user";

export interface Task {
    id: number;
    name: string;
    description: string;
    creation_date: Date;
    edit_date: Date;
    state: string;
    creator_id: number;
    position: number;
    task_group_id: number;

    assignees?: Array<User>;
    comments?: Array<TaskComment>;
    tags?: Array<Tag>;
}
