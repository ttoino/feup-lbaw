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
        let data: any = {};
        const formData = new FormData(form);

        for (const key of formData.keys()) {
            data[key.replace("[]", "")] = key.endsWith("[]")
                ? formData.getAll(key)
                : formData.get(key);
        }

        console.log(new FormData(form), data);

        try {
            const payload =
                constantData instanceof Object
                    ? { ...constantData, ...data }
                    : constantData;
            const response = await fn(payload);

            if (response.ok) {
                form.reset();
                ok(await response.json());
            } else notOk(await response.json());
        } catch {
            notOk();
        }
    });
};
