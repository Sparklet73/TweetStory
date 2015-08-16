$(document).ready(function () {

    var parseTags = function (aryLists) {
        
        
        for (var s in aryLists) {
            var ps = s.split('|');
        }
        $.ajaxSetup({
            cache: false
        });

        var jqxhr = $.getJSON('ajax_showtweets.php', {
            sd: startday,
            ed: endday
        });

        jqxhr.done(function (data) {

            if (data.rsStat) {
                buildKeywordGraph(data.rsGraph);
            } else {
                showMessage('danger', data.rsGraph);
            }
        });
    };
});