import { renderToast } from "../toast";

const token =
    document.querySelector<HTMLMetaElement>('meta[name="csrf-token"]')
        ?.content ?? "";

export interface APIError {
    message: string;
}

interface ErrorResponse extends Response {
    ok: false;
    json(): Promise<APIError>;
}

interface SuccessfulResponse<T> extends Response {
    ok: true;
    json(): Promise<T>;
}

export type EnhancedResponse<T> = ErrorResponse | SuccessfulResponse<T>;

export type APIMethod = "GET" | "POST" | "PUT" | "DELETE";

export const apiFetch = <T>(
    url: RequestInfo,
    method: APIMethod = "GET",
    body?: any,
    options?: RequestInit
): Promise<EnhancedResponse<T>> => {
    console.log(
        `Making ${method} request to ${url} with options ${options} and body ${JSON.stringify(
            body
        )}`
    );

    if (method === "GET") {
        url += "?" + new URLSearchParams(body);
        body = undefined;
    }

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
};

export const tryRequest = async <K, Params extends Array<any>>(
    fn: (...params: Params) => ReturnType<typeof apiFetch<K>>,
    error: string = "Request failed, are you online?",
    ...params: Params
): Promise<K | null> => {
    try {
        const response = await fn(...params);

        if (!response.ok) {
            const message = (await response.json()).message;

            renderToast?.({ text: message });
            return null;
        }

        return await response.json();
    } catch (e) {
        console.error(e);
        renderToast?.({ text: error });
        return null;
    }
};
