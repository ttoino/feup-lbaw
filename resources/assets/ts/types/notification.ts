import { Datetime } from "./misc";

interface NotificationTypeMap {
    "App\\Notifications\\ProjectInvite": {
        url: string;
    };
}

export interface Notification<T extends keyof NotificationTypeMap> {
    id: number;
    type: T;
    json: NotificationTypeMap[T];
    creation_date: Datetime;
    read_date: Datetime;
    notifiable_id: number;
}
