function bookmark_history(boolBM, thisHID) {
    $.ajaxSetup({
        cache: false
    });
    
    var jqxhr = $.getJSON('ajax_bmHistory.php', {
       bm: boolBM,
       hID: thisHID
    });
    
    jqxhr.done(function (data) {
        if (data.rsStat) {
            console.log("Bookmarked success!");
        } else {
            showMessage('danger', data.rsTweet);
        }
    });
}