export interface Datetime {
    iso: string;
    long_diff: string;
    diff: string;
    datetime: string;
    date: string;
    time: string;
}

export interface Markdown {
    raw: string;
    formatted: string;
}

export interface Paginator<T> {
    data: Array<T>;
    next_cursor?: string;
}
