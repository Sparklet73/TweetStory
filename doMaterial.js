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
            console.log("success");
        } else {
            showMessage('danger', data.rsTweet);
        }
    });
}

function showMaterial(userID) {
    $.ajaxSetup({
        cache: false
    });
    
    var jqxhr = $.getJSON('ajax_showMaterial.php', {
        uID: userID
    });

    jqxhr.done(function (data) {
        if (data.rsStat) {
            $.each(data.rsRes, function (index, val) {
                $('#materialBox').append(makeMaterialObj(val));
            });
        } else {
            showMessage('danger', data.rsTweet);
        }
    });
}

function makeMaterialObj(text) {
    var rtn_content = '';
    rtn_content += '<ol class="simple_with_animation vertical">';
    for (var t in text) {
        rtn_content += '<li>' + text[t] + '</li>';
    }
    rtn_content += '</ol>';
    return rtn_content;
}