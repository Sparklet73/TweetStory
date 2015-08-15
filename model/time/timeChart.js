$(document).ready(function () {
    $(function () {
        $('#timeChart').highcharts({
            chart: {
                zoomType: 'x'
            },
            title: {
                text: 'Tweets per day'
            },
            subtitle: {
                text: document.ontouchstart === undefined ?
                        'Click and drag in the plot area to zoom in' : 'Pinch the chart to zoom in'
            },
            xAxis: {
                type: 'datetime'
            },
            yAxis: {
                title: {
                    text: 'Number of tweets'
                }
            },
            legend: {
                enabled: false
            },
            plotOptions: {
                area: {
                    fillColor: {
                        linearGradient: {
                            x1: 0,
                            y1: 0,
                            x2: 0,
                            y2: 1
                        },
                        stops: [
                            [0, Highcharts.getOptions().colors[0]],
                            [1, Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')]
                        ]
                    },
                    marker: {
                        radius: 2
                    },
                    lineWidth: 1,
                    states: {
                        hover: {
                            lineWidth: 1
                        }
                    },
                    threshold: null
                },
                series: {
                    cursor: 'pointer',
                    point: {
                        events: {
                            click: function () {
                                d = Highcharts.dateFormat('%Y-%m-%d', this.x);
                                $('#TagsArea').append($("<option></option>").attr("value", "option"+d).text(d));
                                $('#TagsArea').multiSelect('refresh');
                            }
                        }
                    }
                }
            },
            series: [{
                    type: 'area',
                    name: '# of tweets',
                    data: [[Date.UTC(2014, 07, 24), 6], [Date.UTC(2014, 07, 25), 343], [Date.UTC(2014, 07, 26), 411], [Date.UTC(2014, 07, 27), 421], [Date.UTC(2014, 07, 28), 952], [Date.UTC(2014, 07, 29), 838], [Date.UTC(2014, 07, 30), 1071], [Date.UTC(2014, 07, 31), 3667], [Date.UTC(2014, 08, 01), 2411], [Date.UTC(2014, 08, 02), 1888], [Date.UTC(2014, 08, 03), 1189], [Date.UTC(2014, 08, 04), 1127], [Date.UTC(2014, 08, 05), 872], [Date.UTC(2014, 08, 06), 615], [Date.UTC(2014, 08, 07), 595], [Date.UTC(2014, 08, 08), 678], [Date.UTC(2014, 08, 09), 1074], [Date.UTC(2014, 08, 10), 996], [Date.UTC(2014, 08, 11), 747], [Date.UTC(2014, 08, 12), 622], [Date.UTC(2014, 08, 13), 403], [Date.UTC(2014, 08, 14), 740], [Date.UTC(2014, 08, 15), 745], [Date.UTC(2014, 08, 19), 216], [Date.UTC(2014, 08, 20), 477], [Date.UTC(2014, 08, 21), 1030], [Date.UTC(2014, 08, 22), 4087], [Date.UTC(2014, 08, 23), 2162], [Date.UTC(2014, 08, 24), 1526], [Date.UTC(2014, 08, 25), 1607], [Date.UTC(2014, 08, 26), 2145], [Date.UTC(2014, 08, 27), 2547], [Date.UTC(2014, 08, 28), 16047], [Date.UTC(2014, 08, 29), 22109], [Date.UTC(2014, 08, 30), 15864], [Date.UTC(2014, 09, 01), 13724], [Date.UTC(2014, 09, 02), 16075], [Date.UTC(2014, 09, 03), 21013], [Date.UTC(2014, 09, 04), 18842], [Date.UTC(2014, 09, 05), 15946], [Date.UTC(2014, 09, 06), 13177], [Date.UTC(2014, 09, 07), 10162], [Date.UTC(2014, 09, 08), 8742], [Date.UTC(2014, 09, 09), 6322], [Date.UTC(2014, 09, 10), 7364], [Date.UTC(2014, 09, 11), 7876], [Date.UTC(2014, 09, 12), 5929], [Date.UTC(2014, 09, 13), 10705], [Date.UTC(2014, 09, 14), 8513], [Date.UTC(2014, 09, 15), 9037], [Date.UTC(2014, 09, 16), 5686], [Date.UTC(2014, 09, 17), 6057], [Date.UTC(2014, 09, 18), 5240], [Date.UTC(2014, 09, 19), 5821], [Date.UTC(2014, 09, 20), 5791], [Date.UTC(2014, 09, 21), 4737], [Date.UTC(2014, 09, 22), 5612], [Date.UTC(2014, 09, 23), 5813], [Date.UTC(2014, 09, 24), 4787], [Date.UTC(2014, 09, 25), 3989], [Date.UTC(2014, 09, 26), 5858], [Date.UTC(2014, 09, 27), 5061], [Date.UTC(2014, 09, 28), 4935], [Date.UTC(2014, 09, 29), 4471], [Date.UTC(2014, 09, 30), 3256], [Date.UTC(2014, 09, 31), 3067], [Date.UTC(2014, 10, 01), 2799], [Date.UTC(2014, 10, 02), 2776], [Date.UTC(2014, 10, 03), 3289], [Date.UTC(2014, 10, 04), 2658], [Date.UTC(2014, 10, 05), 2643], [Date.UTC(2014, 10, 06), 2640], [Date.UTC(2014, 10, 07), 2250], [Date.UTC(2014, 10, 08), 2970], [Date.UTC(2014, 10, 09), 2344], [Date.UTC(2014, 10, 10), 2087], [Date.UTC(2014, 10, 11), 2093], [Date.UTC(2014, 10, 12), 2285], [Date.UTC(2014, 10, 13), 1985], [Date.UTC(2014, 10, 14), 1855], [Date.UTC(2014, 10, 15), 1754], [Date.UTC(2014, 10, 16), 1881], [Date.UTC(2014, 10, 17), 1905], [Date.UTC(2014, 10, 18), 2121], [Date.UTC(2014, 10, 19), 2499], [Date.UTC(2014, 10, 20), 1544], [Date.UTC(2014, 10, 21), 1318], [Date.UTC(2014, 10, 22), 1317], [Date.UTC(2014, 10, 23), 3231], [Date.UTC(2014, 10, 24), 4959], [Date.UTC(2014, 10, 25), 11460], [Date.UTC(2014, 10, 26), 14118], [Date.UTC(2014, 10, 27), 6573], [Date.UTC(2014, 10, 28), 2565], [Date.UTC(2014, 10, 29), 2688], [Date.UTC(2014, 10, 30), 3177], [Date.UTC(2014, 11, 01), 5062], [Date.UTC(2014, 11, 02), 4189], [Date.UTC(2014, 11, 03), 3956], [Date.UTC(2014, 11, 04), 2336], [Date.UTC(2014, 11, 05), 2622], [Date.UTC(2014, 11, 06), 2107], [Date.UTC(2014, 11, 07), 1933], [Date.UTC(2014, 11, 08), 2239], [Date.UTC(2014, 11, 09), 3341], [Date.UTC(2014, 11, 10), 3831], [Date.UTC(2014, 11, 11), 8474], [Date.UTC(2014, 11, 12), 4314], [Date.UTC(2014, 11, 13), 2321], [Date.UTC(2014, 11, 14), 1594], [Date.UTC(2014, 11, 15), 3216], [Date.UTC(2014, 11, 16), 1850], [Date.UTC(2014, 11, 17), 514]]
                            //javascript's month begins from zero.
                }]
        });
    });
});