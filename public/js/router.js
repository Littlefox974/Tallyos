'use strict';

function Router(routes) {
    if (!routes) {
        console.error('error: routes param is mandatory');
    }
    this.constructor(routes);
    this.init();
}

Router.prototype = {
    routes: undefined,
    rootElem: undefined,
    constructor: function (routes) {
        this.routes = routes;
        this.rootElem = document.getElementById('app');
    },
    init: function () {
        const r = this.routes;
        (function (scope, r) {
            window.addEventListener('hashchange', function (e) {
                scope.hasChanged(scope, r);
            });
        })(this, r);
        this.hasChanged(this, r);
    },
    hasChanged: function (scope, r) {
        if (window.location.hash.length > 0) {
            for (let i = 0, length = r.length; i < length; i++) {
                const route = r[i];
                if (route.isActiveRoute(window.location.hash.substr(1))) {
                    scope.goToRoute(route.htmlName, route.jsName);
                }
            }
        } else {
            for (let i = 0, length = r.length; i < length; i++) {
                const route = r[i];
                if (route.default) {
                    scope.goToRoute(route.htmlName, route.jsName);
                }
            }
        }
    },
    goToRoute: function (htmlName, jsName) {
        (function (scope) {
            fetch('views/' + htmlName)
                .then(response => response.text())
                .then(data => {
                    scope.rootElem.innerHTML = data;

                    // Activate the current tab, and disable others
                    for (let tabEl of document.querySelectorAll(`#navbar-tabs a[href]`)) {
                        if (window.location.hash.substr(1).startsWith(tabEl.href.slice(tabEl.href.indexOf('#') + 1))) {
                            tabEl.classList.add('active');
                        } else {
                            tabEl.classList.remove('active');
                        }
                    }

                    // Load and execute the view JS if provided
                    if (jsName) {
                        $.getScript('views/' + jsName)
                    }
                })
        })(this);
    }
};