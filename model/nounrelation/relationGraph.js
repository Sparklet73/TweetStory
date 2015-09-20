/**
 * Created by CYa on 2015/5/11.
 */
$(document).ready(function () {
    var g = {
        nodes: [],
        edges: []
    };

    s = new sigma({
        graph: g,
        container: 'relationGraph',
        renderer: {
            container: document.getElementById('relationGraph'),
            type: 'canvas'
        }
    });

//    sigma.parsers.json('rt10noun.json',
//            s,
//            function () {
//                s.settings({
//                    defaultLabelSize: 18,
//                    font: "微軟正黑體",
//                    labelHoverBGColor: 'node',
//                    defaultLabelHoverColor: '#000',
//                    labelHoverShadow: 'node',
//                    labelThreshold: 3
//                });
//                s.refresh();
//            }
//    );


    db = new sigma.plugins.neighborhoods();

    db.load('model/nounrelation/rt10noun.json', function () {
        // Out function to initialize sigma on a new neighborhood:
        function refreshGraph(centerNodeId) {
            s.camera.goTo({
                x: 0,
                y: 0,
                angle: 0,
                ratio: 1
            });
            s.settings({
                labelThreshold: 1
            });
            s.graph.clear();

            s.graph.read(db.neighborhood(centerNodeId));

            s.refresh();

        }

        var NowNodes;
        // Let's now bind this new function to the "clickNode" event:
        s.bind('clickNode', function (event) {
            var nodeId = event.data.node.id;
            refreshGraph(nodeId);
//            var nodeLabel = event.data.node.label;
            NowNodes = db.neighborhood(nodeId).nodes;
            NounShowNumOfTweets(NowNodes);
        });

        $(".btn-add-tags-noun").click(function () {
            for (var nn in NowNodes) {
                $('#TagsArea').multiSelect('addOption', {value: "Nouns|" + NowNodes[nn]['label'], text: NowNodes[nn]['label'], index: 0, nested: 'Nouns'});
//                $('#TagsArea').append($("<option></option>").attr("value", "option" + NowNodes[nn]['label']).text(NowNodes[nn]['label']));
//                $('#TagsArea').multiSelect('refresh');
            }
            var found = [];
            $("#TagsArea option").each(function () {
                if ($.inArray(this.value, found) !== -1)
                    $(this).remove();
                found.push(this.value);
            });
//            $('#TagsArea').multiSelect('refresh');
        });

    });

    $(".btn-restart-camera").click(function () {
//    $("button[name='restart-camera']").click(function () {
        s.camera.goTo({
            x: 0,
            y: 0,
            angle: 0,
            ratio: 1
        });
    });

    $(".btn-reset-graph").click(function () {
        sigma.parsers.json('model/nounrelation/rt10noun.json',
                s,
                function () {
                    s.settings({
                        defaultLabelSize: 18,
                        font: "Arial Unicode MS",
                        labelHoverBGColor: 'node',
                        defaultLabelHoverColor: '#000',
                        labelHoverShadow: 'node',
                        labelThreshold: 3
                    });
                    s.camera.goTo({
                        x: 0,
                        y: 0,
                        angle: 0,
                        ratio: 1
                    });
                    s.refresh();
                }
        );
    });

    $('ul.nav a').on('shown.bs.tab', function (e) {
        sigma.parsers.json('model/nounrelation/rt10noun.json',
                s,
                function () {
                    s.settings({
                        defaultLabelSize: 18,
                        font: "Arial Unicode MS",
                        labelHoverBGColor: 'node',
                        defaultLabelHoverColor: '#000',
                        labelHoverShadow: 'node',
                        labelThreshold: 3
                    });
                    s.camera.goTo({
                        x: 0,
                        y: 0,
                        angle: 0,
                        ratio: 1
                    });
                    s.refresh();
                }
        );
    });

    /*var listener = sigma.layouts.fruchtermanReingold.configure(s);
     // Bind all events:
     listener.bind('start stop interpolate', function(event) {
     console.log(event.type);
     });
     var isRunning = false;
     document.getElementById('toggle-layout').addEventListener('click', function () {
     if (isRunning) {
     isRunning = false;
     s.stopForceAtlas2();
     document.getElementById('toggle-layout').childNodes[0].nodeValue = 'Start Layout';
     } else {
     isRunning = true;
     s.startForceAtlas2();
     //sigma.layouts.fruchtermanReingold.start(s);
     document.getElementById('toggle-layout').childNodes[0].nodeValue = 'Stop Layout';
     }
     }, true);*/
});