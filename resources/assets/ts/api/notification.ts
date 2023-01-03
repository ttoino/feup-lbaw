import { Notification } from "../types/notification";
import { apiFetch } from ".";

export const getNotification = (notificationId: string) =>
    apiFetch<Notification<any>>(`/api/notifications/${notificationId}`);

export const markNotificationAsRead = (notificationId: string) =>
    apiFetch<Notification<any>>(
        `/api/notifications/${notificationId}/read`,
        "PUT"
    );
