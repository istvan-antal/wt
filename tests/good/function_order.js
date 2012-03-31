var bar;

function foo() {
    "use strict";
    bar();
}

function bar() {
    "use strict";
    foo();
}