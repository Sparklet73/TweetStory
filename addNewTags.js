function parseTags(aryLists) {
    var jObject = {};
    var arrTime = [];
    var arrKeywords = [];
    var arrUsers = [];
    var arrNouns = [];
    for (var s in aryLists) {
        console.log(aryLists[s]);
        ps = aryLists[s].split('|');
        switch (ps[0]) {
            case "Time":
                arrTime.push(ps[1]);
                break;
            case "Keywords":
                arrKeywords.push(ps[1]);
                break;
            case "Users":
                arrUsers.push(ps[1]);
                break;
            case "Nouns":
                arrNouns.push(ps[1]);
                break;
        }
    }
    if(arrTime.length > 0) {
        jObject["Time"] = arrTime;
    }
    if(arrKeywords.length > 0) {
        jObject["Keywords"] = arrKeywords;
    }
    if(arrUsers.length > 0) {
        jObject["Users"] = arrUsers;
    }
    if(arrNouns.length > 0) {
        jObject["Nouns"] = arrNouns;
    }

    $.ajaxSetup({
        cache: false
    });

    var jqxhr = $.getJSON('ajax_showtweets.php', {
        tweetTags: jObject
    });

    jqxhr.done(function (data) {
        if (data.rsStat) {
            $('#tweetsDisplay').append(makeTweetContent());
        } else {
            showMessage('danger', data.rsTweet);
        }
    });
}

function makeTweetContent(tid, time, user, content, rtcount) {
    var rtn_content = '';
    rtn_content += '<div class="panel panel-info"><p class="tweet_user">';
    rtn_content += user + '</p><p class="tweet_time"> ' + time + ' </p><br>';
    rtn_content += '<p class="tweet">' + content + '</p>';
    rtn_content += '<p class="rtcnt">' + rtcount + '</p>';
    rtn_content += '<a href="#" id="comments">Insert memo!</a>';
    rtn_content += '<a class="btn icon-btn btn-collect" href="#" id="cl-' + tid + '">';
    rtn_content += '<span class="glyphicon btn-glyphicon glyphicon-plus img-circle text-collect"></span>Collect</a></div>';
    return rtn_content;
}