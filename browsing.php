<!DOCTYPE html>
<html>
    <head>
        <title>Browsing Room</title>
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
        <script src="returnTweetsCnt.js"></script>
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
        <link href="editable/css/bootstrap-editable.css" rel="stylesheet">
        <script src="editable/js/bootstrap-editable.js"></script>
        <script src="d3/d3.min.js"></script>
        <script src="model/keywords/keywordsGraph.js"></script>
        <script src="model/time/timeChart.js"></script>
        <style type="text/css">    
            body, html {
                background-color: rgb(245,245,245);
                font-family: "Trebuchet MS Black", "LiHei Pro", "Microsoft JhengHei";
                overflow: hidden; //no scrollable bar
            }
            #timeChart, #relationGraph, #userGraph, #keywordsGraph {
                /*                top: 0;
                                bottom: 0;
                                left: 0;
                                right: 0;*/
                height: 350px;
            }
            /*            #well2 {
                            margin-bottom: 0;
                            border-color:#2E4272
                        }*/
            //----usergraph filter----//
            #control-pane {
                top: 10px;
                /*bottom: 10px;*/
                right: 10px;
                position: absolute;
                width: 200px;
                background-color: rgb(249, 247, 237);
                box-shadow: 0 2px 6px rgba(0,0,0,0.3);
            }
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
                font-variant: small-caps;
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
            //----usergraph filter end-----
            //----keywordsGraph 的設定------
            .wordnode {
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
            }
            //----keywordsGraph 設定 結束-----
            //----bootstrap Tags slider 設定 開始-----
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
            //----bootstrap Tags slider 設定 結束-----
        </style>
    </head>

    <body>
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
                            <li><a href="materials.php">Materials Room</a></li>
                            <li><a href="history.php">History</a></li>
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
                    <!--<ul style="text-align: right;margin-right: 20px;margin-bottom: 3px;">return 6 tweets</ul>-->
                </div>
                <div class="tweet-container" style="overflow-y: auto; overflow-x: hidden;background-color: #fff;">
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
        <script type="text/javascript">
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
                <h2>Tags</h2>
                <b style="color:#A9A9A9;">Adjust weights for 4 tags.</b>
                <div class="well">
                    <div class="row">
                        <div class="col-md-4" style="font-size:15px;">Time</div> <div class="col-md-6"><input id="timeSlider" data-slider-id='timeSlider' type="text" data-slider-min="1" data-slider-max="10" data-slider-step="1" data-slider-value="1" data-slider-tooltip="hide"/></div><div class="col-md-2"><span id="timeSliderVal">1</span></div> 
                        <div class="col-md-4" style="font-size:15px;">Keywords</div> <div class="col-md-6"><input id="keywordsSlider" data-slider-id='keywordsSlider' type="text" data-slider-min="1" data-slider-max="10" data-slider-step="1" data-slider-value="1" data-slider-tooltip="hide"/></div><div class="col-md-2"><span id="keywordsSliderVal">1</span></div> 
                        <div class="col-md-4" style="font-size:15px;">Users</div> <div class="col-md-6"><input id="usersSlider" data-slider-id='usersSlider' type="text" data-slider-min="1" data-slider-max="10" data-slider-step="1" data-slider-value="1" data-slider-tooltip="hide"/></div><div class="col-md-2"><span id="usersSliderVal">1</span></div> 
                        <div class="col-md-4" style="font-size:15px;">Nouns</div> <div class="col-md-6"><input id="nounsSlider" data-slider-id='nounsSlider' type="text" data-slider-min="1" data-slider-max="10" data-slider-step="1" data-slider-value="1" data-slider-tooltip="hide"/></div><div class="col-md-2"><span id="nounsSliderVal">1</span></div> 
                    </div>
                </div>
                <select id='TagsArea' multiple='multiple'>
                    <optgroup label="Time"></optgroup>
                    <optgroup label="Keywords"></optgroup>
                    <optgroup label="Users"></optgroup>
                    <optgroup label="Nouns"></optgroup>
                </select>
                <button type="button" name="btn_apply" id="btn_apply" class="btn btn-apply" style="margin-top: 5px;">Apply</button>
                <script>
                    var tv=1, kv=1, uv=1, nv=1;
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
                        NProgress.start();
                        var tags = $('select#TagsArea').val();
//                        console.log(tags + tv + kv + uv + nv);
                        parseTags(tags, tv, kv, uv, nv);
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
        <div class="col-md-5">
            <div class="row">
                <h2>Story elements model</h2>
                <div class="panel with-nav-tabs panel-primary">
                    <div class="panel-heading">
                        <ul class="nav nav-tabs pull-right">
                            <li class="active"><a href="#tab1primary" data-toggle="tab">Time</a></li>
                            <li><a href="#tab2primary" data-toggle="tab">Keywords</a></li>
                            <li><a href="#tab3primary" data-toggle="tab">Users</a></li>
                            <li><a href="#tab4primary" data-toggle="tab">Noun Co-word</a></li>
                        </ul>
                    </div>
                    <div class="panel-body" style="padding: 2px;">
                        <div class="tab-content">
                            <div class="tab-pane fade in active" id="tab1primary">
                                <div id="timeChart" style="overflow-y: hidden; overflow-x:auto;"></div>
                            </div>
                            <div class="tab-pane fade" id="tab2primary">
                                <div style="text-align: right;">
                                    <button type="button" name="add-tags-topics" id="add-tags-topics" class="btn btn-default">Add tags</button>
                                </div>
                                <div id="keywordsGraph"></div>
                            </div>
                            <div class="tab-pane fade" id="tab3primary">
                                <div class="col-md-10" style="padding:0px;">
                                    <ul>Click the user point to add tag.</ul>
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
                                <div class="panel panel-default">
                                    <div style="text-align: right;">
                                        <button type="button" name="restart-camera" id="restart-camera" class="btn btn-default">Reset camera</button>
                                        <button type="button" name="reset-graph" id="reset-graph" class="btn btn-default">Reset graph</button>
                                        <button type="button" name="add-tags-noun" id="add-tags-noun" class="btn btn-default">Add tags</button>
                                    </div>
                                    <div id="relationGraph"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" style="margin-top: 8px;box-shadow: 0 2px 5px 0 rgba(0, 0, 0, 0.26);border-radius:3px;border-color: #ccc;">
                <div id="showTweetsFreq" style="min-width: 310px; height: 120px;border-color: #ccc;"></div>
            </div>
        </div>


        <!--                <h2>History</h2>
                        <div class="row" style="overflow-x: hidden; overflow-y:auto; height: 120px;">
                            <div class="well" id="well2">
                                <div class="list-group"> 
                                    <a href="#" class="list-group-item">
                                        <p class="list-group-item-text">#滕彪 李克强：推销中国高铁我特别有底气 - 　　8月22日，李克强考察中国铁路总公司，他在铁路运输...</p>
                                    </a>
                                    <a href="#" class="list-group-item">
                                        <p class="list-group-item-text">RT @siutopoon: 何姨姨就是用這份中共喉舌於１９４４年２月日的社論，秒殺李飛的！且看中共當年如何狠批有篩選的假普選！ http://t.co/35tZa568EB</p>
                                    </a>
                                    <a href="#" class="list-group-item">
                                        <p class="list-group-item-text">同行的Vivian 遇上一位北京人，北京人說不會到香港，因為香港有佔中。至少，知道佔中。</p>
                                    </a>
                                    <a href="#" class="list-group-item">
                                        <p class="list-group-item-text">戴耀廷回应李飞：人大作不合理决定即发动占中 http://t.co/hoauI5p4cC</p>
                                    </a>
                                    <a href="#" class="list-group-item">
                                        <p class="list-group-item-text">「由原燒看台灣人的民族性?」不得不說我非常認同・・・日本能夠在服務業打著客人至上的原則，那無非是有素養高的國民在背後支撐。而我認為這套原則在台灣的服務業中未必是全面適用，到頭來只會淪為消費者占小便宜的說詞罷了。</p>
                                    </a>
                                    <a href="#" class="list-group-item">
                                        <p class="list-group-item-text">Some text...</p>
                                    </a>
                                    <a href="#" class="list-group-item">
                                        <p class="list-group-item-text">Some text...</p>
                                    </a>
                                    <a href="#" class="list-group-item">
                                        <p class="list-group-item-text">Some text...</p>
                                    </a>
                                    <a href="#" class="list-group-item">
                                        <p class="list-group-item-text">Some text...</p>
                                    </a>
                                    <a href="#" class="list-group-item">
                                        <p class="list-group-item-text">Some text...</p>
                                    </a>
                                    <a href="#" class="list-group-item">
                                        <p class="list-group-item-text">Some text...</p>
                                    </a>
                                    <a href="#" class="list-group-item">
                                        <p class="list-group-item-text">Some text...</p>
                                    </a>
                                    <a href="#" class="list-group-item">
                                        <p class="list-group-item-text">Some text...</p>
                                    </a>
                                </div>
                            </div>
                        </div>-->

    </div>
</body>
</html>