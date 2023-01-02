import { Datetime, Markdown } from "./misc";

export interface Project {
    id: number;
    name: string;
    description: Markdown;
    creation_date: Datetime;
    last_modification_date: Datetime;
    archived: boolean;
    coordinator_id: number;
}
