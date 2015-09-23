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
            console.log("save material wrong");
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
                $(loc).append(makeMaterialObj(val.text, val.tt));
            });
        } else {
            console.log("group material wrong");
        }
    });
}

function makeMaterialObj(text) {
    var rtn_content = '';
    rtn_content += '<li>' + text + ' <p style="text-align:right;color:#39AF26">"' + tt + '"</p></li>';
    return rtn_content;
}