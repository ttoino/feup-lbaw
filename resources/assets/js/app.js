/**
 * First, we will load all of this project's Javascript utilities and other
 * dependencies. Then, we will be ready to develop a robust and powerful
 * application frontend using useful Laravel and JavaScript libraries.
 */

// require('./bootstrap');

const globalFetch = global.fetch;
const token = document.querySelector('meta[name="csrf-token"]').content
global.fetch = (path, options) => globalFetch(path, {
    ...options,
    headers: {
        ...options.headers,
        'X-CSRF-TOKEN': token
    }
});

require("./task");
require("./forms");
require("./project");
require("./user");
