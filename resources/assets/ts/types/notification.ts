interface NotificationTypeMap {
    "App\\Notifications\\ProjectInvite": {
        url: string;
    };
}

export interface Notification<T extends keyof NotificationTypeMap> {
    id: number;
    type: T;
    json: string;
    creation_date: Date;
    read_date: Date;
    notifiable_id: number;

    data?: NotificationTypeMap[T];
}
