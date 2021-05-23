'use strict';

function Route(name, htmlName, jsName, defaultRoute) {
    if (!name || !htmlName) {
        console.error('error: name and htmlName params are mandatories');
    }
    this.constructor(name, htmlName, jsName, defaultRoute);

}

Route.prototype = {
    name: undefined,
    htmlName: undefined,
    default: undefined,
    constructor: function (name, htmlName, jsName, defaultRoute) {
        this.name = name;
        this.htmlName = htmlName;
        this.jsName = jsName;
        this.default = defaultRoute;
    },
    isActiveRoute: function (hashedPath) {
        return hashedPath.replace('#', '') === this.name;
    }
}