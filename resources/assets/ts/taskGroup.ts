export const repositionTaskGroup = (
    taskGroupId: string,
    position: string | null
) =>
    fetch(`/api/task-group/${taskGroupId}/reposition`, {
        method: "POST",
        body: JSON.stringify({
            position,
        }),
        headers: {
            "Content-Type": "application/json",
        },
    });
