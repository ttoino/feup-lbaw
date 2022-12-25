import { ThreadComment } from "./thread_comment";
import { User } from "./user";

export interface Thread {
    id: number;
    title: string;
    content: string;
    creation_date: Date;
    edit_date: Date;
    state: string;
    author_id: number;
    project_id: number;

    author?: User;
    comments?: Array<ThreadComment>;
}
