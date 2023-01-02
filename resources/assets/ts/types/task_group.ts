import { Datetime, Markdown } from "./misc";
import { Task } from "./task";

export interface TaskGroup {
    id: number;
    name: string;
    description: Markdown;
    creation_date: Datetime;
    position: number;
    project_id: number;

    tasks?: Array<Task>;
}
