/**
 * First, we will load all of this project's Javascript utilities and other
 * dependencies. Then, we will be ready to develop a robust and powerful
 * application frontend using useful Laravel and JavaScript libraries.
 */

import "bootstrap";

const globalFetch = window.fetch;
const token =
    document.querySelector<HTMLMetaElement>('meta[name="csrf-token"]')
        ?.content ?? "";
window.fetch = (input, init) =>
    globalFetch(input, {
        ...init,
        headers: {
            ...init?.headers,
            "X-CSRF-TOKEN": token,
        },
    });

import "./task";
import "./forms";
import "./project";
import "./user";
import "./board";
