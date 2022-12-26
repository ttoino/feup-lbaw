export const render = <T extends Record<string, any>>(
    el: HTMLElement,
    data: T
) => {
    const selector = Object.keys(data)
        .map((prop) => `[data-render-prop=${prop}]`)
        .join(", ");
    const places = el.querySelectorAll<HTMLElement>(selector);

    places.forEach((place) => {
        const prop = place.dataset.renderProp!;
        const method = place.dataset.renderMethod ?? "text";

        // TODO: improve this
        switch (method) {
            case "datetime":
                const datetime: Date =
                    data[prop] instanceof Date
                        ? data[prop]
                        : new Date(data[prop]);
                (place as HTMLTimeElement).dateTime = datetime.toISOString();
                place.innerText = datetime.toDateString();
                break;
            case "html":
                place.innerHTML = data[prop];
                break;
            case "text":
            default:
                place.innerText = data[prop];
                break;
        }
    });

    return el;
};

export const renderSingleton = <T extends Record<string, any>>(
    selector: string
) => {
    const el = document.querySelector<HTMLElement>(selector);

    return el && ((data: T) => render(el, data));
};

export const renderTemplate = <T extends Record<string, any>>(
    selector: string
) => {
    const template = document.querySelector<HTMLTemplateElement>(
        `template${selector}`
    )?.content.firstElementChild;

    return (
        template &&
        ((data: T) => {
            const el = template.cloneNode(true) as HTMLElement;
            return render(el, data);
        })
    );
};

export const renderList = <T extends Record<string, any>>(
    templateSelector: string,
    listSelector: string
) => {
    const list = document.querySelector<HTMLElement>(listSelector);

    const renderItem = renderTemplate(templateSelector);

    console.log(list, renderItem);

    return (
        list &&
        renderItem &&
        ((data: Array<T>) => list.replaceChildren(...data.map(renderItem)))
    );
};

export const appendListItem = <T extends Record<string, any>>(
    templateSelector: string,
    listSelector: string
) => {
    const list = document.querySelector<HTMLElement>(listSelector);

    const renderItem = renderTemplate(templateSelector);

    return (
        list && renderItem && ((data: T) => list.appendChild(renderItem(data)))
    );
};

export const appendListItems = <T extends Record<string, any>>(
    templateSelector: string,
    listSelector: string
) => {
    const list = document.querySelector<HTMLElement>(listSelector);

    const renderItem = renderTemplate(templateSelector);

    console.log(list, renderItem);

    return (
        list &&
        renderItem &&
        ((data: Array<T>) => list.append(...data.map(renderItem)))
    );
};
