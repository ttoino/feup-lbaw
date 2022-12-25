export interface Project {
    id: number;
    name: string;
    description: string;
    creation_date: Date;
    last_modification_date: Date;
    archived: boolean;
    coordinator_id: number;
}
