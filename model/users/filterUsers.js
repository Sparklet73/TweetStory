/**
 * This is an example on how to use sigma filters plugin on a real-world graph.
 */
var filter;

/**
 * DOM utility functions
 */
var _ = {
    $: function (id) {
        return document.getElementById(id);
    },
    all: function (selectors) {
        return document.querySelectorAll(selectors);
    },
    removeClass: function (selectors, cssClass) {
        var nodes = document.querySelectorAll(selectors);
        var l = nodes.length;
        for (i = 0; i < l; i++) {
            var el = nodes[i];
            // Bootstrap compatibility
            el.className = el.className.replace(cssClass, '');
        }
    },
    addClass: function (selectors, cssClass) {
        var nodes = document.querySelectorAll(selectors);
        var l = nodes.length;
        for (i = 0; i < l; i++) {
            var el = nodes[i];
            // Bootstrap compatibility
            if (-1 == el.className.indexOf(cssClass)) {
                el.className += ' ' + cssClass;
            }
        }
    },
    show: function (selectors) {
        this.removeClass(selectors, 'hidden');
    },
    hide: function (selectors) {
        this.addClass(selectors, 'hidden');
    },
    toggle: function (selectors, cssClass) {
        var cssClass = cssClass || "hidden";
        var nodes = document.querySelectorAll(selectors);
        var l = nodes.length;
        for (i = 0; i < l; i++) {
            var el = nodes[i];
            //el.style.display = (el.style.display != 'none' ? 'none' : '' );
            // Bootstrap compatibility
            if (-1 !== el.className.indexOf(cssClass)) {
                el.className = el.className.replace(cssClass, '');
            } else {
                el.className += ' ' + cssClass;
            }
        }
    }
};


function updatePane(graph, filter) {
    // get max degree
    var maxDegree = 0,
            categories = {};

    // read nodes
    graph.nodes().forEach(function (n) {
        maxDegree = Math.max(maxDegree, graph.degree(n.id));
        categories[n.attributes.acategory] = true;
    })

    // min degree
    _.$('min-degree').max = maxDegree;
    _.$('max-degree-value').textContent = maxDegree;

}


