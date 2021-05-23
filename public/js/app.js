'use strict';
(function () {
    function init() {
        new Router([
            new Route('home', 'home.html', null, true),
            new Route('ruches', 'hives.html', 'hives.js'),
            new Route('info', 'hives-info.html', 'hives-info.js')
        ]);
    }
    init();
}());