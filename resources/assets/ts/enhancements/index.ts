export interface Enhancement<E extends HTMLElement> {
    selector: string;
    onattach?: (e: Enhanced<E>) => any;
    ondettach?: (e: Enhanced<E>) => any;
}

type Enhanced<E extends HTMLElement> = E & {
    enhancements: Set<Enhancement<E>>;
};

export const enhancements = new Set<Enhancement<any>>();

const addEnhancement =
    <E extends HTMLElement>(enhancement: Enhancement<E>) =>
    (e: Enhanced<E>) => {
        e.enhancements ??= new Set<Enhancement<any>>();

        if (e.enhancements.has(enhancement)) return;

        e.enhancements.add(enhancement);

        enhancement.onattach?.(e);
    };

const mutationObserver = new MutationObserver((records) => {
    for (const record of records) {
        if (record.type != "childList") continue;

        for (const enhancement of enhancements) {
            const elements = document.querySelectorAll(enhancement.selector);
            elements.forEach(addEnhancement(enhancement));
        }
    }
});
mutationObserver.observe(document, {
    childList: true,
    subtree: true,
});

export const registerEnhancement = <E extends HTMLElement>(
    enhancement: Enhancement<E>
) => {
    enhancements.add(enhancement);

    const elements = document.querySelectorAll<Enhanced<E>>(
        enhancement.selector
    );

    elements.forEach(addEnhancement(enhancement));
};
