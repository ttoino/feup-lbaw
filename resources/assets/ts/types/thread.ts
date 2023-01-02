import { Datetime, Markdown } from "./misc";
import { ThreadComment } from "./thread_comment";
import { User } from "./user";

export interface Thread {
    id: number;
    title: string;
    content: Markdown;
    creation_date: Datetime;
    edit_date: Datetime;
    author_id: number;
    project_id: number;

    author?: User;
    comments?: Array<ThreadComment>;
}
