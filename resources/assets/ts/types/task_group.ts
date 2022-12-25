import { Task } from "./task";

export interface TaskGroup {
    id: number;
    name: string;
    description: string;
    creation_date: Date;
    position: number;
    project_id: number;

    tasks?: Array<Task>;
}
