$(document).ready(function () {
    var margin = 5,
            width = 500,
            height = 445;
//            diameter = 445;

    var color = d3.scale.linear()
            .domain([-1, 5])
            .range(["hsl(152,80%,80%)", "hsl(228,30%,40%)"])
            .interpolate(d3.interpolateHcl);

    var pack = d3.layout.pack()
            .padding(2)
            .size([width - margin, height - margin])
            .value(function (d) {
                return d.size;
            })

    var svg = d3.select("#keywordsGraph").append("svg")
            .attr("width", width)
            .attr("height", height)
            .append("g")
            .attr("transform", "translate(" + width / 2 + "," + height / 2 + ")");

    d3.json("model/keywords/keywordsGraph.json", function (error, root) {
        var focus = root,
                nodes = pack.nodes(root),
                view;

        var circle = svg.selectAll("circle")
                .data(nodes)
                .enter().append("circle")
                .attr("class", function (d) {
                    return d.parent ? d.children ? "wordnode" : "wordnode wordnode--leaf" : "wordnode wordnode--root";
                })
                .style("fill", function (d) {
                    return d.children ? color(d.depth) : null;
                })
                .on("click", function (d) {
                    myNode = d;
                    if (focus !== d) {
                        zoom(d), d3.event.stopPropagation();
                    }
                });

        var text = svg.selectAll("text")
                .data(nodes)
                .enter().append("text")
                .attr("class", "label")
                .style("text-anchor", "middle")
                .style("text-shadow", "0 1px 0 #fff, 1px 0 0 #fff, -1px 0 0 #fff, 0 -1px 0 #fff")
                .style("fill-opacity", function (d) {
                    return d.parent === root ? 1 : 0;
                })
                .style("display", function (d) {
                    return d.parent === root ? null : "none";
                })
                .text(function (d) {
                    return d.name;
                });

        var wordnode = svg.selectAll("circle,text");

        d3.select("#keywordsGraph")
                .style("background", "#fff")
                .on("click", function () {
                    zoom(root);
                });

        zoomTo([root.x, root.y, root.r * 2 + margin]);

        function zoom(d) {
            var focus0 = focus;
            focus = d;

            //將var transition=d3.transition()改成var transition = text.transition()
            var transition = text.transition()
                    .duration(250)
                    .tween("zoom", function (d) {
                        var i = d3.interpolateZoom(view, [focus.x, focus.y, focus.r * 2 + margin]);
                        return function (t) {
                            zoomTo(i(t));
                        };
                    });

            //將transition.selectAll("text")的.selectAll("text")拔掉
            transition.filter(function (d) {
                return d.parent === focus || this.style.display === "inline";
            })
                    .style("fill-opacity", function (d) {
                        return d.parent === focus ? 1 : 0;
                    })
                    .each("start", function (d) {
                        if (d.parent === focus) {
                            this.style.display = "inline";
                        }
                    })
                    .each("end", function (d) {
                        if (d.parent !== focus) {
                            this.style.display = "none";
                        }
                    });

        }
//            執行點選cluster加入tags的動作
        $("button[name='add-tags-topics']").click(function () {
//            $('#TagsArea').append($("<option></option>").attr("value", "option" + myNode['name']).text(myNode['name']));
            $('#TagsArea').multiSelect('addOption', {value: "option" + myNode['name'], text: myNode['name'], index: 0, nested: 'Keywords'});
            for (var ww in myNode['children']) {
//                $('#TagsArea').append($("<option></option>").attr("value", "option" + myNode['children'][ww]['name']).text(myNode['children'][ww]['name']));
                $('#TagsArea').multiSelect('addOption', {value: "option" + myNode['children'][ww]['name'], text: myNode['children'][ww]['name'], index: 0, nested: 'Keywords'});
            }
//            防止有重複的tags
            var found = [];
            $("#TagsArea option").each(function () {
                if ($.inArray(this.value, found) !== -1)
                    $(this).remove();
                found.push(this.value);
            });
//            $('#TagsArea').multiSelect('refresh');
        });

        function zoomTo(v) {
            var k = height / v[2];
            view = v;
            wordnode.attr("transform", function (d) {
                return "translate(" + (d.x - v[0]) * k + "," + (d.y - v[1]) * k + ")";
            });
            circle.attr("r", function (d) {
                return d.r * k;
            });
        }
    });

    d3.select(self.frameElement).style("height", height + "px");

});