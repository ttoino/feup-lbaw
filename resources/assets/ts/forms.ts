import { APIError, apiFetch } from "./api";

export const ajaxForm = <K, P>(
    fn: (param: P) => ReturnType<typeof apiFetch<K>>,
    form: HTMLFormElement,
    constantData: Partial<P>,
    ok: (data: K) => unknown,
    notOk: (error?: APIError) => unknown
) => {
    form.addEventListener("submit", async (e) => {
        e.preventDefault();
        const data = Object.fromEntries(new FormData(form)) as P;

        console.log(data);

        try {
            const payload =
                constantData instanceof Object
                    ? { ...constantData, ...data }
                    : constantData;
            const response = await fn(payload);

            if (response.ok) {
                ok(await response.json());
                form.reset();
            } else notOk(await response.json());
        } catch {
            notOk();
        }
    });
};
