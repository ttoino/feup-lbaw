import { showToast } from "../toast";

const token =
    document.querySelector<HTMLMetaElement>('meta[name="csrf-token"]')
        ?.content ?? "";

interface EnhancedResponse<T> extends Response {
    json(): Promise<T>;
}

export const apiFetch = <T>(
    url: RequestInfo,
    method: RequestInit["method"] = "GET",
    body?: any,
    options?: RequestInit
): Promise<EnhancedResponse<T>> =>
    fetch(url, {
        ...options,
        method,
        body: JSON.stringify(body),
        headers: {
            ...options?.headers,
            "X-CSRF-TOKEN": token,
            "Content-Type": "application/json",
            Accept: "application/json",
        },
    });

export const tryRequest = async <K, Params extends Array<any>>(
    fn: (...params: Params) => ReturnType<typeof apiFetch<K>>,
    notOk: string,
    error: string = "Request failed, are you online?",
    ...params: Params
): Promise<K | null> => {
    try {
        const response = await fn(...params);

        if (!response.ok) {
            showToast(notOk);
            return null;
        }

        return await response.json();
    } catch {
        showToast(error);
        return null;
    }
};
