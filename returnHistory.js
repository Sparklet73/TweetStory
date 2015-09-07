function returnHistory(userID) {

    $.ajaxSetup({
        cache: false
    });

    var jqxhr = $.getJSON('ajax_returnHistory.php', {
        uID: userID
    });

    jqxhr.done(function (data) {
        if (data.rsStat) {
            $.each(data.rsHistory, function (index, val) {
                console.log(val.tweets);
                $('#history').append(makeHistoryContent(index, val.applied_at, val.time, val.time_w, val.keywords, val.keywords_w, val.users, val.users_w, val.nouns, val.nouns_w, val.tweets));
            });
        } else {
            showMessage('danger', data.rsHistory);
        }
    });
}

function makeHistoryContent(hid, applied_at, time, time_w, keywords, keywords_w, users, users_w, nouns, nouns_w, tweets) {
    var rtn_content = '';
    rtn_content += '<div class="history_item" id="hs_';
    rtn_content += hid + '"><button type="button" name="btn_history" id="btn_history" class="btn btn-info">Recover</button><h5 style="text-align:right;display:inline;">';
    rtn_content += applied_at + '</h5><ul class="list-group"><li class="list-group-item" style="height: 50px;><h5 class="list-group-item-heading">Time:';
    rtn_content += time_w + '</h5><p class="list-group-item-text" style="text-align: right;">';
    var word = time.split('|');
    for (var w in word) {
        rtn_content += '<span class="label timeTag" style="margin-left: 3px;">';
        rtn_content += word[w] + '</span>';
    }
    rtn_content += '</p></li><li class="list-group-item" style="height: 50px;><h5 class="list-group-item-heading">Keywords:';
    rtn_content += keywords_w + '</h5><p class="list-group-item-text" style="text-align: right;">';
    var word = keywords.split('|');
    for (var w in word) {
        rtn_content += '<span class="label keywordsTag" style="margin-left: 3px;">';
        rtn_content += word[w] + '</span>';
    }
    rtn_content += '</p></li><li class="list-group-item" style="height: 50px;><h5 class="list-group-item-heading">Users:';
    rtn_content += users_w + '</h5><p class="list-group-item-text" style="text-align: right;">';
    var word = users.split('|');
    for (var w in word) {
        rtn_content += '<span class="label usersTag" style="margin-left: 3px;">';
        rtn_content += word[w] + '</span>';
    }
    rtn_content += '</p></li><li class="list-group-item" style="height: 50px;><h5 class="list-group-item-heading">Nouns:';
    rtn_content += nouns_w + '</h5><p class="list-group-item-text" style="text-align: right;">';
    var word = nouns.split('|');
    for (var w in word) {
        rtn_content += '<span class="label nounsTag" style="margin-left: 3px;">';
        rtn_content += word[w] + '</span>';
    }
    rtn_content += '</p></li></ul><div class="row" style="overflow-x: hidden; overflow-y:auto; height: 300px;"><div class="well" id="well2">';
    rtn_content += '<div class="list-group">';
    for (var t in tweets) {
        rtn_content += '<li href="#" class="list-group-item" style="padding:10px 15px;"><p class="list-group-item-text">';
        rtn_content += tweets[t]['text'] +'</p></li>';
    }
    rtn_content += '</li></div></div></div></div>';
    return rtn_content;
}