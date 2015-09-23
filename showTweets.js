function parseTags(userID, strDatetime, aryLists, tv, kv, uv, nv) {
    var jObject = {};
    var arrTime = [];
    var arrKeywords = [];
    var arrUsers = [];
    var arrNouns = [];
    for (var s in aryLists) {
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
    if (arrTime.length > 0) {
        jObject["Time"] = arrTime;
    }
    if (arrKeywords.length > 0) {
        jObject["Keywords"] = arrKeywords;
    }
    if (arrUsers.length > 0) {
        jObject["Users"] = arrUsers;
    }
    if (arrNouns.length > 0) {
        jObject["Nouns"] = arrNouns;
    }

    $.ajaxSetup({
        cache: false
    });

    var jqxhr = $.getJSON('ajax_showtweets.php', {
        uID: userID,
        dtime: strDatetime,
        tweetTags: jObject,
        timew: tv,
        keywordsw: kv,
        usersw: uv,
        nounsw: nv
    });

    jqxhr.done(function (data) {
        if (data.rsStat) {
            document.getElementById('hbm').value = data.bookmarkID;
            $('#tweetsDisplay').html("");
            $.each(data.rsTweet, function (index, val) {
                $('#tweetsDisplay').append(makeTweetContent(index, val.created_at, val.from_user_name, val.from_user_description, val.text, val.retweet_cnt, val.tags));
            });
            makeTweetParsed();
//            NProgress.done();
        } else {
//            NProgress.done();
            alert("These tags are not found in this dataset.");
        }
    });
    jqxhr.fail(function () {
//        NProgress.done();
        alert("These tags are not found in this dataset.");
    });
}

function makeTweetContent(tid, time, user, user_des, content, rtcount, tags) {
    var rtn_content = '';
    rtn_content += '<div class="panel panel-info"><a class="tweet_user" id="'+ user +'" href="http://twitter.com/' + user + '" ';
    rtn_content += ' target = "_blank" data-toggle="tooltip" title="'+ user_des + '">';
    rtn_content += user + '</a><p class="tweet_time"> ' + time + ' </p><br>';
    rtn_content += '<p class="tweet">' + content + '</p>';
    rtn_content += '<p class="rtcnt">Retweet count: ' + rtcount + '</p>';
    rtn_content += '<a class="btn icon-btn btn-collect" id="cl-' + tid + '">';
    rtn_content += '<span class="glyphicon btn-glyphicon glyphicon-plus img-circle text-collect"></span>Collect</a>';
    var tagList = tags.split('/');
    for (var t in tagList) {
        rtn_content += '<span class="label ';
        var tword = tagList[t].split('|');
        if (tword[0] == "t") {
            rtn_content += 'timeTag';
        } else if (tword[0] == "k") {
            rtn_content += 'keywordsTag';
        } else if (tword[0] == "u") {
            rtn_content += 'usersTag';
        } else if (tword[0] == "n") {
            rtn_content += 'nounsTag';
        }
        rtn_content += '" style="margin-left:2px;margin-right:2px;">' + tword[1] + '</span>';
    }
    rtn_content += '</div>';
    return rtn_content;
}

function makeTweetParsed() {
    $(".tweet").tweetParser({
        urlClass: "tweet_link", //this is default
        userClass: "tweet_mention", //this is default
        hashtagClass: "hashtag", //this is default
        target: "_blank", //this is default
        searchWithHashtags: true
    });
    $('[data-toggle="tooltip"]').tooltip();
}