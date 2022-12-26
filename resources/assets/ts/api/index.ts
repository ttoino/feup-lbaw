import { showToast } from "../toast";

const token =
    document.querySelector<HTMLMetaElement>('meta[name="csrf-token"]')
        ?.content ?? "";

type ResponseContentWithErrorMessage<T> = T & { message?: string };

interface EnhancedResponse<T> extends Response {
    json(): Promise<ResponseContentWithErrorMessage<T>>;
}

export const apiFetch = <T>(
    url: RequestInfo,
    method: RequestInit["method"] = "GET",
    body?: any,
    options?: RequestInit
): Promise<EnhancedResponse<T>> => {

    console.log(`Making ${method} request to ${url} with options ${options} and body ${JSON.stringify(body)}`);
    return fetch(url, {
        ...options,
        method,
        body: JSON.stringify(body),
        headers: {
            ...options?.headers,
            "X-CSRF-TOKEN": token,
            "Content-Type": "application/json",
            "Accept": "application/json",
        },
    });
}

export const tryRequest = async <K, Params extends Array<any>>(
    fn: (...params: Params) => ReturnType<typeof apiFetch<K>>,
    notOk: string,
    error: string = "Request failed, are you online?",
    ...params: Params
): Promise<K | null> => {
    try {
        const response = await fn(...params);

        if (!response.ok) {

            let message;
            try {
                const responseBody = await response.json();

                message = responseBody.message ?? "";
            } catch {
                message = notOk;
            }

            showToast(message);
            return null;
        }

        return await response.json();
    } catch {
        showToast(error);
        return null;
    }
};
