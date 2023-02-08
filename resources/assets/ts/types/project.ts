import { Datetime, Markdown } from "./misc";

export interface Project {
    id: number;
    name: string;
    description: Markdown;
    creation_date: Datetime;
    edit_date: Datetime;
    archived: boolean;
    coordinator_id: number;
}
