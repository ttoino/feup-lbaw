/**
 * First, we will load all of this project's Javascript utilities and other
 * dependencies. Then, we will be ready to develop a robust and powerful
 * application frontend using useful Laravel and JavaScript libraries.
 */

import "bootstrap";

import "./task";
import "./forms";
import "./tooltips";
import "./project";
import "./user";

if (window.location.pathname.match(/project\/\d+\/(board|task)/))
    import("./pages/board");

import "./components/imageinput";
