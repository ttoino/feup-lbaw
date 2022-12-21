import { showToast } from "../toast";

const token =
    document.querySelector<HTMLMetaElement>('meta[name="csrf-token"]')
        ?.content ?? "";

export const apiFetch = (
    url: RequestInfo,
    method: RequestInit["method"] = "GET",
    body?: any,
    options?: RequestInit
) =>
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

export const tryRequest = async <Params extends Parameters<any>>(
    fn: (...params: Params) => ReturnType<typeof apiFetch>,
    notOk: string,
    error: string = "Request failed, are you online?",
    ...params: Params
) => {
    try {
        const response = await fn(...params);

        if (!response.ok) {
            showToast(notOk);
            return;
        }

        return await response.json();
    } catch {
        showToast(error);
    }
};

export const ajaxNavigation =
    <Params extends Parameters<any>>(
        fn: (...params: Params) => ReturnType<typeof apiFetch>,
        ok: (response: any) => any,
        notOk: (response: any) => any,
        loading: () => any
    ): ((newUrl: string, ...params: Params) => void) =>
    (newUrl: string, ...params) => {
        const r = fn(...params);

        r.then(async (r) => {
            history.replaceState(r, "");
            ok(await r.json());
        });

        r.catch(async (r) => {
            history.replaceState(r, "");
            notOk(await r.json());
        });

        history.pushState(undefined, "", newUrl);
        loading();
    };
