import { Datetime, Markdown } from "./misc";
import { Tag } from "./tag";
import { TaskComment } from "./task_comment";
import { User } from "./user";

export interface Task {
    id: number;
    name: string;
    description: Markdown;
    creation_date: Datetime;
    edit_date: Datetime;
    completed: boolean;
    creator_id: number;
    position: number;
    task_group_id: number;

    assignees?: Array<User>;
    comments?: Array<TaskComment>;
    tags?: Array<Tag>;
}
