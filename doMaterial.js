function saveMaterial(userID, tweet) {
    $.ajaxSetup({
        cache: false
    });
    
    var tweetID = tweet.replace("cl-", "");

    var jqxhr = $.getJSON('ajax_saveMaterial.php', {
        uID: userID,
        tID: tweetID
    });

    jqxhr.done(function (data) {
        if (data.rsStat) {
            console.log("Save material succeed!");
        } else {
            showMessage('danger', data.rsTweet);
        }
    });
}

function groupMaterial(userID, aryList, loc) {
    
    var tagsJson = {};
    tagsJson["tag"] = aryList;
    
    $.ajaxSetup({
        cache: false
    });
    
    var jqxhr = $.getJSON('ajax_groupMaterial.php', {
        uID: userID,
        tJO: tagsJson
    });

    jqxhr.done(function (data) {
        if (data.rsStat) {
            $(loc).html("");
            $.each(data.rsRes, function (index, val) {
                $(loc).append(makeMaterialObj(val));
            });
        } else {
            showMessage('danger', data.rsTweet);
        }
    });
}

function makeMaterialObj(text) {
    var rtn_content = '';
    for (var t in text) {
        rtn_content += '<li>' + text[t] + '</li>';
    }
    return rtn_content;
}