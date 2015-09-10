/**
 * Created by CYa on 2015/5/12.
 */

$(document).ready(function () {
    var g2 = {
        nodes: [],
        edges: []
    };

    s2 = new sigma({
        graph: g2,
        container: 'userGraph',
        renderer: {
            container: document.getElementById('userGraph'),
            type: 'canvas'
        }
    });
//    sigma.parsers.json('mention250_filter_50n.json',
//            s2,
//            function () {
//                s2.settings({
//                    defaultLabelSize: 18,
//                    font: "微軟正黑體",
//                    labelHoverBGColor: 'node',
//                    defaultLabelHoverColor: '#000',
//                    labelHoverShadow: 'node',
//                    labelThreshold: 1
//                });
//                s2.refresh();
//                s2.bind('clickNode', function (event) {
//                    var lb = event.data.node.label;
//                    var web = "http://twitter.com/" + lb;
//                    window.open(web, lb, config = 'height=500,width=600');
//                });
//                // Initialize the Filter API
//                filter = new sigma.plugins.filter(s2);
//
//                updatePane(s2.graph, filter);
//
//                function applyMinDegreeFilter(e) {
//                    var v = e.target.value;
//                    _.$('min-degree-val').textContent = v;
//
//                    filter
//                            .undo('min-degree')
//                            .nodesBy(function (n) {
//                                return this.degree(n.id) >= v;
//                            }, 'min-degree')
//                            .apply();
//                }
//
//                _.$('min-degree').addEventListener("input", applyMinDegreeFilter);  // for Chrome and FF
//                _.$('min-degree').addEventListener("change", applyMinDegreeFilter); // for IE10+, that sucks
//            }
//    );

    $('ul.nav a').on('shown.bs.tab', function (e) {
        sigma.parsers.json('model/users/mention250_filter_50n.json',
                s2,
                function () {
                    s2.settings({
                        defaultLabelSize: 18,
                        font: "微軟正黑體",
                        labelHoverBGColor: 'node',
                        defaultLabelHoverColor: '#000',
                        labelHoverShadow: 'node',
                        labelThreshold: 1
                    });
                    s2.refresh();
                    s2.bind('clickNode', function (event) {
                        var lb = event.data.node.label;
//                        var web = "http://twitter.com/" + lb;
//                        window.open(web, lb, config = 'height=500,width=600');
//                        $('#TagsArea').append($("<option></option>").attr("value", "option" + lb).text(lb));
                        $('#TagsArea').multiSelect('addOption', {value: "Users|" + lb, text: lb, index: 0, nested: 'Users'});
                        var found = [];
                        $("#TagsArea option").each(function () {
                            if ($.inArray(this.value, found) !== -1)
                                $(this).remove();
                            found.push(this.value);
                        });
//                        $('#TagsArea').multiSelect('refresh');
                        UserShowNumOfTweets(lb);
                    });
                    // Initialize the Filter API
                    filter = new sigma.plugins.filter(s2);

                    updatePane(s2.graph, filter);

                    function applyMinDegreeFilter(e) {
                        var v = e.target.value;
                        _.$('min-degree-val').textContent = v;

                        filter
                                .undo('min-degree')
                                .nodesBy(function (n, options) {
                                    return this.graph.degree(n.id) >= options.minDegreeVal;
                                },
                                        {
                                            minDegreeVal: +v
                                        },
                                'min-degree'
                                        )
                                .apply();
                    }

                    _.$('min-degree').addEventListener("input", applyMinDegreeFilter);  // for Chrome and FF
                    _.$('min-degree').addEventListener("change", applyMinDegreeFilter); // for IE10+, that sucks
                    s2.refresh();
                }
        );
    });
});