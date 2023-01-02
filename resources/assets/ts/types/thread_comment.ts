import { Datetime, Markdown } from "./misc";
import { User } from "./user";

export interface ThreadComment {
    id: number;
    content: Markdown;
    creation_date: Datetime;
    edit_date: Datetime;
    author_id: number;
    thread_id: number;

    author?: User;
}
