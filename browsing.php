<?php
session_start();

require_once 'config.php';

$intUID = $_SESSION['uID'];
$reHistoryID = isset($_GET['reHID']) ? $_GET['reHID'] : 0;

try {
    $dbh = new PDO("mysql:host=$hostname;dbname=$database;charset=utf8", $dbuser, $dbpass);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $t_w = $k_w = $u_w = $n_w = 1;
    if ($reHistoryID) {
        $sql = "SELECT * FROM `HKALLzh_history` WHERE `userID` =" . $intUID
                . " AND `historyID` = " . $reHistoryID;

        $stmt = $dbh->prepare($sql);
        $stmt->execute();

        $arrResult = $stmt->fetch(PDO::FETCH_ASSOC);
        $t_w = $arrResult['time_w'];
        $k_w = $arrResult['keywords_w'];
        $u_w = $arrResult['users_w'];
        $n_w = $arrResult['nouns_w'];
    }
} catch (PDOException $ex) {
    echo $ex->getMessage();
} catch (Exception $exc) {
    echo $exc->getMessage();
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Browsing Room - HKALLzh</title>
        <meta charset="utf-8">
        <script src="jquery/jquery-2.1.3.min.js"></script>
        <script src="jquery/jquery-ui.min.js"></script>
        <script src="jquery/multiselect/jquery.multi-select.js"></script>
        <link rel="stylesheet" href="jquery/multiselect/multi-select.css" type="text/css" />
        <link href="bootstrap-3.3.1-dist/dist/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <script src="bootstrap-3.3.1-dist/dist/js/bootstrap.min.js"></script>
        <script src="bootstrap-slider/bootstrap-slider.min.js"></script>
        <link rel="stylesheet" href="bootstrap-slider/bootstrap-slider.min.css" type="text/css" />
        <script src="nprogress/nprogress.js"></script>
        <link rel="stylesheet" href="nprogress/nprogress.css" type="text/css" />
        <script src="highcharts/highcharts.js"></script>
        <script src="model/time/timeChart.js"></script>
        <script src="returnTweetsCnt.js"></script>
        <script src="vis/vis.min.js"></script>
        <link rel="stylesheet" href="vis/vis.min.css" type="text/css"/>
        <script src="model/keywords/keywordsGraph.js"></script>
        <script src="showTweets.js"></script>
        <script src="tweetParser/jquery.tweetParser.min.js"></script>
        <script src="linkurious/build/sigma.require.js"></script>
        <script src="linkurious/build/plugins/sigma.parsers.json.min.js"></script>
        <script src="linkurious/build/plugins/sigma.plugins.neighborhoods.min.js"></script>
        <script src="linkurious/build/plugins/sigma.plugins.filter.min.js"></script>
        <script src="model/nounrelation/relationGraph.js"></script>
        <script src="model/users/userGraph.js"></script>
        <script src="model/users/filterUsers.js"></script>
        <link rel="stylesheet" href="css/main.css" type="text/css" />
        <link rel="stylesheet" href="tweetParser/css/tweetParser.css" type="text/css" />
        <script src="doMaterial.js"></script>
        <script src="bookmarkHistory.js"></script>
        <style type="text/css">    
            body, html {
                background-image: url('img/page-background.png');
                background-color: rgb(245,245,245);
                font-family: "Trebuchet MS Black", "LiHei Pro", "Microsoft JhengHei";
                /*no scrollable bar*/
                overflow: hidden; 
            }
            #timeChart { 
                height: 350px;
            }
            #keywordsGraph {
                height: 340px;
            }
            #relationGraph {
                height: 335px;
            } 
            #userGraph {
                height: 350px;
            }
            /*            #well2 {
                            margin-bottom: 0;
                            border-color:#2E4272
                        }*/
            /*----usergraph filter----*/
            /*            #control-pane {
                            top: 10px;
                            bottom: 10px;
                            right: 10px;
                            position: absolute;
                            width: 200px;
                            background-color: rgb(249, 247, 237);
                            box-shadow: 0 2px 6px rgba(0,0,0,0.3);
                        }*/
            .line {
                clear: both;
                display: block;
                width: 100%;
                margin: 0;
                padding: 12px 0 0 0;
                border-bottom: 1px solid #aac789;
                background: transparent;
            }
            h2, h3, h4 {
                padding: 0;
            }
            .green {
                color: #437356;
            }
            h2.underline {
                color: #437356;
                background: #f4f0e4;
                margin: 0;
                border-radius: 2px;
                padding: 8px 12px;
                font-weight: 700;
            }
            .hidden {
                display: none;
                visibility: hidden;
            }
            input[type=range] {
                display: inline;
                width: 50px;
            }
            /*----usergraph filter end-----*/
            /*----keywordsGraph 的設定------*/
            /*            .wordnode {
                            cursor: pointer;
                        }
                        .wordnode:hover {
                            stroke: #000;
                            stroke-width: 1.5px;
                        }
                        .wordnode--leaf {
                            fill: white;
                        }
                        .label,
                        .wordnode--root,
                        .wordnode--leaf {
                            pointer-events: none;
                        }*/
            /*----keywordsGraph 設定 結束-----*/
            /*----bootstrap Tags slider 設定 開始-----*/
            #well .b {
                width: auto;
            }
            #timeSlider .slider-selection, #timeSlider .slider-handle, .timeTag{
                background: #FFC600;
            }
            #keywordsSlider .slider-selection, #keywordsSlider .slider-handle, .keywordsTag{
                background: #A6DE38;
            }
            #usersSlider .slider-selection, #usersSlider .slider-handle, .usersTag{
                background: #248E8E;
            }
            #nounsSlider .slider-selection, #nounsSlider .slider-handle, .nounsTag{
                background: #ED3C3C;
            }
            #timeSlider, #keywordsSlider, #usersSlider, #nounsSlider{
                width: 130px;
            }
            /*----bootstrap Tags slider 設定 結束-----*/
        </style>
    </head>

    <body oncontextmenu="return false;">
        <div class="mywindow" style="margin:0 auto;">
            <nav class="navbar navbar-inverse">
                <div class="container-fluid">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" href="#">TweetStory</a>
                    </div>

                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                        <ul class="nav navbar-nav">
                            <li class="active"><a href="#">Browsing Room<span class="sr-only">(current)</span></a></li>
                            <li><a href="materials.php" target = '_blank'>Materials Room</a></li>
                            <li><a href="history.php" target = '_blank'>History</a></li>
                        </ul>
                        <ul class="nav navbar-nav navbar-right">
                            <p class="navbar-text">Dataset: HKALLzh --- 497,519 tweets (from 2014-08-24 22:06:20 to 2014-12-17 13:55:22)</p>
                        </ul>
                    </div><!-- /.navbar-collapse -->
                </div> <!-- /.container-fluid -->
            </nav>
            <div class="col-md-4">
                <div class="row">
                    <h2>Tweets Display Area</h2>
                    <!--<b style="color:#686868;">Show tweets with score by your tags adjustment.</b>-->
                    <!--<ul style="text-align: right;margin-right: 20px;margin-bottom: 3px;">return 6 tweets</ul>-->
                    <div class="row" style="text-align: right;margin-bottom:5px;">
                        <label class="radio-inline">
                            <input type="radio" name="inlineRadioOptions" id="radio-weights" value="radio-weights" checked disabled> Order by weights.
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="inlineRadioOptions" id="radio-time" value="radio-time" disabled> Order by time.
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="inlineRadioOptions" id="radio-rt" value="radio-rt" disabled> Order by RT counts.
                        </label>
                    </div>
                </div>
                <div class="tweet-container" style="overflow-y: auto; overflow-x: hidden;">
                    <div class="row" id="tweetsDisplay">
                        <!--                        <div class="panel panel-info">
                                                    <p class="tweet_user">chenkang888</p><p class="tweet_time"> 2014/08/04 12:12:12 </p><br>
                                                    <p class="tweet">
                                                        @abc12 刘植荣：【香港“反占中”游行花钱雇佣参与者】印佣组织发言人表示，有雇主要求她们交出身分证号码，并在表格上签名，因外佣不懂中文，所以不知表格的用意。会员被游说参加八一七游行，并称可提供200至300元酬劳，而说客称游行是为了香港的和平及繁荣，但没有详细解释原因
                                                    </p>
                                                    <p class="rtcnt">retweet</p>
                                                    <a href="#" id="comments">Insert memo!</a>
                                                    <a class="btn icon-btn btn-collect" href="#"><span class="glyphicon btn-glyphicon glyphicon-plus img-circle text-collect"></span>Collect</a>
                                                    <span class="label nounsTag">
                                                        梁振英
                                                    </span>
                                                </div>-->
                        <!--                        <script>
                                                    $(function () {
                                                        $('#comments').editable({
                                                            type: 'textarea',
                                                            pk: 1,
                                                            name: 'comments',
                                                            url: 'post.php',
                                                            title: 'Enter comments'
                                                        });
                                                    });
                                                </script>-->
                    </div>                        
                </div>
            </div>
        </div>
        <!--好像不需要在這邊assign了，當append上推文後，有另外寫函數去執行tweetParse。-->
        <script type="text/javascript">
            //            當開始執行ajax時的時候都會start，不管返回成功還是失敗都可以done
            $(document).ajaxStart(function () {
                NProgress.start();
            })
                    .ajaxStop(function () {
                        NProgress.done();
                    });

            $(".tweet").tweetParser({
                urlClass: "tweet_link", //this is default
                userClass: "tweet_mention", //this is default
                hashtagClass: "hashtag", //this is default
                target: "_blank", //this is default
                searchWithHashtags: true
            });
        </script>

        <div class="col-md-3">
            <div class="row">
                <h2>Tags Zone</h2>
                <br>
                <div class="row">
                    <div class="col-md-10" style="text-align: left;">
                        <b style="color:#686868;">Adjust weights for 4 tags and bookmark a state if you want.</b>
                    </div>
                    <div class="col-md-2">
                        <a class="btn btn-success btn-bookmark" href="#" data-toggle="tooltip" title="Bookmark this state." style="text-shadow: black 3px 3px 3px;">
                            <span type="button" name="btn-bookmark" id="btn-boookmark" class="glyphicon glyphicon-bookmark" style="text-align:center;"></span>
                        </a>
                    </div>
                </div>
                <br>
                <div class="modal fade" id="bm-alert" tabindex="-1" role="dialog" aria-labelledby="bm-alertLabel">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header" style="border-bottom:initial;">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="bm-alertLabel">You didn't apply any tags.</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <script>
//                    bookmark按下的不同反應，toggle bookmark跟勾勾，bookmark後會將資料庫中的history bookmarked改值。
                    $("a.btn-bookmark").click(function () {
                        var thisHID = $('#hbm').val();
                        var $this = $("#btn-boookmark");
                        if ($('#hbm').val() !== '') {
                            if ($this.hasClass("glyphicon-ok")) {
                                $this.removeClass("glyphicon-ok").addClass("glyphicon-bookmark");
                                bookmark_history(false, thisHID);
                                return;
                            }
                            if ($this.hasClass("glyphicon-bookmark")) {
                                $this.removeClass("glyphicon-bookmark").addClass("glyphicon-ok");
                                bookmark_history(true, thisHID);
                                return;
                            }
                        } else {
                            $('#bm-alert').modal('show');
                        }
                    });
                </script>
                <div class="well" style="padding:15px;">
                    <div class="row">
                        <div class="col-md-4" style="font-size:15px;">Time</div> <div class="col-md-6"><input id="timeSlider" data-slider-id='timeSlider' type="text" data-slider-min="1" data-slider-max="10" data-slider-step="1" data-slider-value="<?php echo $t_w; ?>" data-slider-tooltip="hide"/></div><div class="col-md-2"><span id="timeSliderVal"><?php echo $t_w; ?></span></div> 
                        <div class="col-md-4" style="font-size:15px;">Keywords</div> <div class="col-md-6"><input id="keywordsSlider" data-slider-id='keywordsSlider' type="text" data-slider-min="1" data-slider-max="10" data-slider-step="1" data-slider-value="<?php echo $k_w; ?>" data-slider-tooltip="hide"/></div><div class="col-md-2"><span id="keywordsSliderVal"><?php echo $k_w; ?></span></div> 
                        <div class="col-md-4" style="font-size:15px;">Users</div> <div class="col-md-6"><input id="usersSlider" data-slider-id='usersSlider' type="text" data-slider-min="1" data-slider-max="10" data-slider-step="1" data-slider-value="<?php echo $u_w; ?>" data-slider-tooltip="hide"/></div><div class="col-md-2"><span id="usersSliderVal"><?php echo $u_w; ?></span></div> 
                        <div class="col-md-4" style="font-size:15px;">Nouns</div> <div class="col-md-6"><input id="nounsSlider" data-slider-id='nounsSlider' type="text" data-slider-min="1" data-slider-max="10" data-slider-step="1" data-slider-value="<?php echo $n_w; ?>" data-slider-tooltip="hide"/></div><div class="col-md-2"><span id="nounsSliderVal"><?php echo $n_w; ?></span></div> 
                    </div>
                </div>
                <input type="text" id="handmadeTag" class="form-control" placeholder="Add keyword tags by yourself." style="margin-bottom: 10px;">
                <script>
                    $("#handmadeTag").bind("keypress", {}, keypressInBox);
                    function keypressInBox(e) {
                        if (e.keyCode === 13) {
                            var hmT = $('#handmadeTag').val();
                            $('#TagsArea').multiSelect('addOption', {value: "Keywords|" + hmT, text: hmT, index: 0, nested: 'Keywords'});
                            var found = [];
                            $("#TagsArea option").each(function () {
                                if ($.inArray(this.value, found) !== -1)
                                    $(this).remove();
                                found.push(this.value);
                            });
                            $('#handmadeTag').val('');
                        }
                    }
                    ;
                </script>
                <!--當使用者是從history那邊點選恢復來browsing這邊的時候，要恢復tag跟權重。-->
                <?php
                if ($reHistoryID) {
                    echo '<select id="TagsArea" multiple="multiple">';
                    if ($arrResult['time'] != "") {
                        $timeList = explode('|', $arrResult['time']);
                        echo '<optgroup label="Time">';
                        foreach ($timeList as $w) {
                            echo '<option value="Time|' . $w . '" selected>' . $w . '</option>';
                        }
                        echo '</optgroup>';
                    } else {
                        echo '<optgroup label="Time"></optgroup>';
                    }
                    if ($arrResult['keywords'] != "") {
                        $kwList = explode('|', $arrResult['keywords']);
                        echo '<optgroup label="Keywords">';
                        foreach ($kwList as $w) {
                            echo '<option value="Keywords|' . $w . '" selected>' . $w . '</option>';
                        }
                        echo '</optgroup>';
                    } else {
                        echo '<optgroup label="Keywords"></optgroup>';
                    }
                    if ($arrResult['users'] != "") {
                        $userList = explode('|', $arrResult['users']);
                        echo '<optgroup label="Users">';
                        foreach ($userList as $w) {
                            echo '<option value="Users|' . $w . '" selected>' . $w . '</option>';
                        }
                        echo '</optgroup>';
                    } else {
                        echo '<optgroup label="Users"></optgroup>';
                    }
                    if ($arrResult['nouns'] != "") {
                        $nounsList = explode('|', $arrResult['nouns']);
                        echo '<optgroup label="Nouns">';
                        foreach ($nounsList as $w) {
                            echo '<option value="Nouns|' . $w . '" selected>' . $w . '</option>';
                        }
                        echo '</optgroup>';
                    } else {
                        echo '<optgroup label="Nouns"></optgroup>';
                    }
                    echo '</select>';
                } else {
                    echo '<select id="TagsArea" multiple="multiple">
                    <optgroup label="Time"></optgroup>
                    <optgroup label="Keywords"></optgroup>
                    <optgroup label="Users"></optgroup>
                    <optgroup label="Nouns"></optgroup>
                </select>';
                }
                ?>
                <br>
                <div class="row" style="text-align: center">
                    <!--<button type="button" name="btn_clear" id="btn_clear" class="btn btn-clear" style="margin-top: 5px;">Clear</button>-->
                    <button type="button" name="btn_apply" id="btn_apply" class="btn btn-apply" style="margin-top: 5px;">Apply</button>
                </div>
                <input type="hidden" id="hbm">
                <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header" style="border-bottom:initial;">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myModalLabel">Explore story models to get some tags.</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <script>
                    var userID = <?php echo $intUID; ?>; //for test using.
                    var tv = <?php echo $t_w; ?>, kv = <?php echo $k_w; ?>, uv = <?php echo $u_w; ?>, nv = <?php echo $n_w; ?>;
                    $('#timeSlider').bootstrapSlider({});
                    $("#timeSlider").on("slide", function (slideEvt) {
                        $("#timeSliderVal").text(slideEvt.value);
                        tv = slideEvt.value;
                    });
                    $('#keywordsSlider').bootstrapSlider({});
                    $("#keywordsSlider").on("slide", function (slideEvt) {
                        $("#keywordsSliderVal").text(slideEvt.value);
                        kv = slideEvt.value;
                    });
                    $('#usersSlider').bootstrapSlider({});
                    $("#usersSlider").on("slide", function (slideEvt) {
                        $("#usersSliderVal").text(slideEvt.value);
                        uv = slideEvt.value;
                    });
                    $('#nounsSlider').bootstrapSlider({});
                    $("#nounsSlider").on("slide", function (slideEvt) {
                        $("#nounsSliderVal").text(slideEvt.value);
                        nv = slideEvt.value;
                    });

                    $('#TagsArea').multiSelect({
                        selectableHeader: "<div class='TagsArea'>Selectable tags</div>",
                        selectionHeader: "<div class='TagsArea'>Selected tags</div>"
                    });
                    $("button[name='btn_apply']").click(function () {
                        var radioReset = true;
//                        NProgress.start();
                        var current = new Date();
                        //format: 2015/9/2 14:42:8
                        var strDatetime = current.getFullYear() + "/"
                                + (current.getMonth() + 1) + "/"
                                + current.getDate() + " "
                                + current.getHours() + ":"
                                + current.getMinutes() + ":"
                                + current.getSeconds();
                        var tags = $('select#TagsArea').val();
                        if (tags === null) {
                            $('#myModal').modal('show');
//                            NProgress.done();
                        } else {
                            //console.log(tags + tv + kv + uv + nv);
                            parseTags(userID, "weights", strDatetime, tags, tv, kv, uv, nv);
                        }
                        $("#btn-boookmark").attr('class', 'glyphicon glyphicon-bookmark');
                        $('#radio-weights').attr("disabled", false);
                        $('#radio-time').attr("disabled", false);
                        $('#radio-rt').attr("disabled", false);
                        $('#radio-weights').prop("checked", true);
                        
                        $(function () {
                            $(document).on('change', 'input:radio[id^="radio-"]', function (event) {
                                if ($(this).attr('id') == "radio-rt") {
                                    parseTags(userID, "rt", strDatetime, tags, tv, kv, uv, nv);
                                } else if ($(this).attr('id') == "radio-time") {
                                    parseTags(userID, "time", strDatetime, tags, tv, kv, uv, nv);
                                } else if (radioReset===false) {
                                    parseTags(userID, "weightsNotHistory", strDatetime, tags, tv, kv, uv, nv);
                                } else {
                                    radioReset = false;
                                }

                            });
                        });
                    });
                </script>
            </div>
            <!--                        <br>
                                    <div class="row">
                                        <div class="panel panel-info">
                                            <div class="panel-heading">Information Panel</div>
                                            <div class="panel-body">...</div>
                                        </div>
                                    </div>-->
        </div>
        <script>
//            當使用者按下Collect按鈕時，紀錄該則推文ID。
            $(document).ready(function () {
                $("#tweetsDisplay").on("click", "a.btn-collect", function () {
                    saveMaterial(userID, $(this).attr("id"));
                    $(this).removeClass('btn-collect').addClass('btn-collected');
                });

                $("#tweetsDisplay").on("mousedown", "a.tweet_user", function (e) {
                    if (e.which == 3) //1: left, 2: middle, 3: right
                    {
                        var Username = $(this).attr("id").toLowerCase();
                        $('#TagsArea').multiSelect('addOption', {value: "Users|" + Username, text: Username, index: 0, nested: 'Users'});
                        var found = [];
                        $("#TagsArea option").each(function () {
                            if ($.inArray(this.value, found) !== -1)
                                $(this).remove();
                            found.push(this.value);
                        });
                    }
                });

                $("#tweetsDisplay").on("mousedown", "a.tweet_mention", function (e) {
                    if (e.which == 3) //1: left, 2: middle, 3: right
                    {
                        var Username = $(this).attr("id").toLowerCase();
                        $('#TagsArea').multiSelect('addOption', {value: "Users|" + Username, text: Username, index: 0, nested: 'Users'});
                        var found = [];
                        $("#TagsArea option").each(function () {
                            if ($.inArray(this.value, found) !== -1)
                                $(this).remove();
                            found.push(this.value);
                        });
                    }
                });
            });
        </script>
        <div class="col-md-5">
            <div class="row">
                <h2>Story Elements Model</h2>
                <b style="color:#686868;">Explore the data story and pick some tags you're interested in.</b>
                <div class="panel with-nav-tabs panel-primary">
                    <div class="panel-heading">
                        <ul class="nav nav-tabs pull-right">
                            <li class="active"><a href="#tab1primary" data-toggle="tab">Time</a></li>
                            <li><a href="#tab2primary" data-toggle="tab">Keywords</a></li>
                            <li><a href="#tab3primary" data-toggle="tab">Users</a></li>
                            <li><a href="#tab4primary" data-toggle="tab">Noun Co-word</a></li>
                        </ul>
                    </div>
                    <div class="panel-body" style="padding: 2px;height:380px;">
                        <div class="tab-content">
                            <div class="tab-pane fade in active" id="tab1primary">
                                <div id="timeChart" style="overflow-y: hidden; overflow-x:auto;"></div>
                            </div>
                            <div class="tab-pane fade" id="tab2primary">
                                <select id="week-keywords">
                                    <option selected value="week1">week 1 - 2014-8-24 ---</option>
                                    <option value="week2">week 2 - 2014-8-25 to 2014-8-31</option>
                                    <option value="week3">week 3 - 2014-9-1 to 2014-9-7</option>
                                    <option value="week4">week 4 - 2014-9-8 to 2014-9-14</option>
                                    <option value="week5">week 5 - 2014-9-15 to 2014-9-21</option>
                                    <option value="week6">week 6 - 2014-9-22 to 2014-9-28</option>
                                    <option value="week7">week 7 - 2014-9-29 to 2014-10-5</option>
                                    <option value="week8">week 8 - 2014-10-6 to 2014-10-12</option>
                                    <option value="week9">week 9 - 2014-10-13 to 2014-10-19</option>
                                    <option value="week10">week 10 - 2014-10-20 to 2014-10-26</option>
                                    <option value="week11">week 11 - 2014-10-27 to 2014-11-2</option>
                                    <option value="week12">week 12 - 2014-11-3 to 2014-11-9</option>
                                    <option value="week13">week 13 - 2014-11-10 to 2014-11-16</option>
                                    <option value="week14">week 14 - 2014-11-17 to 2014-11-23</option>
                                    <option value="week15">week 15 - 2014-11-24 to 2014-11-30</option>
                                    <option value="week16">week 16 - 2014-12-1 to 2014-12-7</option>
                                    <option value="week17">week 17 - 2014-12-8 to 2014-12-14</option>
                                    <option value="week18">week 18 - 2014-12-15 to 2014-12-17</option>
                                </select>
                                <script>
                                    $('#week-keywords').on('change', function () {
//                                            alert($(this).val());
                                        tmp = $(this).val().split("k");
                                        changeWeekKeywords(tmp[1]);
                                    });
                                </script>        
                                <div id="keywordsGraph"></div>
                            </div>
                            <div class="tab-pane fade" id="tab3primary">
                                <div class="col-md-10" style="padding:0px;">
                                    <ul>Top 50 users who was mentioned most.</ul><ul>Click the user point to add tag and check their post level below.</ul>
                                    <div id="userGraph"></div>
                                </div>
                                <div class="col-md-2" style="padding:0px;">
                                    <div id="control-pane">
                                        <h2 class="underline">filters</h2>
                                        <div>
                                            <h4>min degree <span id="min-degree-val">0</span></h4>
                                            0 <input id="min-degree" type="range" min="0" max="0" value="0"> <span id="max-degree-value">0</span><br>
                                        </div>
                                        <span class="line"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="tab4primary">
                                <div class="col-md-11" style="padding:0px;">
                                    <ul>These noun-points were extracted from the tweets which RT count > 10.</ul>
                                    <ul>The relation are about the co-occurrence in same tweets.</ul> 
                                    <ul>Click the point to explore their relation and tweets count below.</ul>
                                    <div id="relationGraph"></div>
                                </div>
                                <div class="col-md-1" style="padding:0px;">
                                    <div id="control-pane">
                                        <a class="btn btn-primary btn-restart-camera" href="#" data-toggle="tooltip" title="Restart camera to center." style="margin-bottom:3px;text-shadow: black 3px 3px 3px;">
                                            <span type="button" name="restart-camera" id="restart-camera" class="glyphicon glyphicon-screenshot"></span>
                                        </a>
                                        <a class="btn btn-info btn-reset-graph" href="#" data-toggle="tooltip" title="Reset to original graph."style="margin-bottom:3px;text-shadow: black 3px 3px 3px;">
                                            <span type="button" name="reset-graph" id="reset-graph" class="glyphicon glyphicon-refresh"></span>
                                        </a>
                                        <a class="btn btn-danger btn-add-tags-noun" href="#" data-toggle="tooltip" title="Add these tags to zone." style="margin-bottom:3px;text-shadow: black 3px 3px 3px;">
                                            <span type="button" name="add-tags-noun" id="add-tags-noun" class="glyphicon glyphicon-plus-sign"></span>
                                        </a>
                                        <!--                                        <button type="button" name="restart-camera" id="restart-camera" class="btn btn-info" style="width:60px;margin-bottom:8px;text-align: right;">Restart<br>camera</button>
                                                                                <button type="button" name="reset-graph" id="reset-graph" class="btn btn-info" style="width:60px;margin-bottom:8px;">Reset<br>graph</button>
                                                                                <button type="button" name="add-tags-noun" id="add-tags-noun" class="btn btn-info" style="width:60px;margin-bottom:8px;">Add tags</button>-->
                                    </div>
                                    <script>
                                        $('[data-toggle="tooltip"]').tooltip();
                                    </script>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" style="margin-top: 8px;box-shadow: 0 2px 5px 0 rgba(0, 0, 0, 0.26);border-radius:3px;border-color: #ccc;">
                <div id="showTweetsFreq" style="min-width: 310px; height: 120px;border-color: #ccc;background-color: #fff;"></div>
            </div>
        </div>
    </div>
</body>
</html>
<?php
unset($dbh);
