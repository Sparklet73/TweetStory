<!DOCTYPE html>
<html>
    <head>
        <title>Browsing Room</title>
        <meta charset="utf-8">
        <script src="jquery/jquery-2.1.3.min.js"></script>
        <script src="jquery/jquery-ui.min.js"></script>
        <link href="bootstrap-3.3.1-dist/dist/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <script src="bootstrap-3.3.1-dist/dist/js/bootstrap.min.js"></script>
        <script src="tweetParser/jquery.tweetParser.min.js"></script>
        <script src="linkurious/build/sigma.require.js"></script>
        <script src="linkurious/build/plugins/sigma.parsers.json.min.js"></script>
        <script src="linkurious/build/plugins/sigma.plugins.neighborhoods.min.js"></script>
        <script src="linkurious/build/plugins/sigma.plugins.filter.min.js"></script>
        <script src="relationGraph.js"></script>
        <script src="userGraph.js"></script>
        <script src="filterUsers.js"></script>
        <link rel="stylesheet" href="css/main.css" type="text/css" />
        <link rel="stylesheet" href="tweetParser/css/tweetParser.css" type="text/css" />
        <link href="editable/css/bootstrap-editable.css" rel="stylesheet">
        <script src="editable/js/bootstrap-editable.js"></script>
        <script src="d3/d3.min.js"></script>
        <script src="keywordsGraph.js"></script>
        <script src="highcharts/highcharts.js"></script>
        <script src="timeChart.js"></script>
        <style type="text/css">    
            body, html {
                font-family: "Trebuchet MS Black", "LiHei Pro", "Microsoft JhengHei";
                overflow: hidden; //no scrollable bar
            }
            #relationGraph, #userGraph, #keywordsGraph {
                top: 0;
                bottom: 0;
                left: 0;
                right: 0;
                height: 400px;
            }
            .panel-primary {
                margin-left: 15px;
                margin-right: 25px;
                width: 93%;
            }

            #well2 {
                margin-bottom: 0;
                border-color:#056445
            }
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
            #control-pane > div {
                margin: 10px;
                overflow-x: auto;
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
        </style>
    </head>

    <body>
        <nav class="navbar navbar-default">
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
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <p class="navbar-text">Dataset: HKALLzh --- 497,519 tweets (from 2014-08-24 22:06:20 to 2014-12-17 13:55:22)</p>
                    </ul>
                </div><!-- /.navbar-collapse -->
                <div class="col-md-5">
                    <h2>Tweet Display Area</h2>
                    <div class="tweet-container" style="overflow-y: auto; overflow-x: hidden;">
                        <div class="row">
                            <div class="panel panel-primary">
                                <p class="user">chenkang888：</p><br>
                                <p class="tweet">
                                    刘植荣：【香港“反占中”游行花钱雇佣参与者】印佣组织发言人表示，有雇主要求她们交出身分证号码，并在表格上签名，因外佣不懂中文，所以不知表格的用意。会员被游说参加八一七游行，并称可提供200至300元酬劳，而说客称游行是为了香港的和平及繁荣，但没有详细解释原因
                                </p>
                                <br>

                                <a href="#" id="username" data-type="text" data-pk="1">Insert memo..</a>
                                <script>
                                    $(function () {
                                        $('#username').editable({
                                            url: '/post',
                                            title: 'Enter username'
                                        });
                                    });
                                </script>
                                <a class="btn icon-btn btn-collect" href="#"><span class="glyphicon btn-glyphicon glyphicon-plus img-circle text-collect"></span>Collect</a>
                            </div>
                            <div class="panel panel-primary"> 
                                <p class="user">User name</p>
                                <p class="tweet">
                                    RT @cbazhengenchong: 香港占中行动发起人戴耀廷称正考虑行动细节 http://t.co/V2Zqybm3xy 中共高层已表明，在香港政改问题上不妥协、不让步的态度，就看习近平如何拍板了。港民占领中环、罢课等一系列的抗议活动已不可避免，正在考虑细节。内地的镇压…
                                </p>
                                <br>
                                <a class="btn icon-btn btn-collect" href="#"><span class="glyphicon btn-glyphicon glyphicon-plus img-circle text-collect"></span>Collect</a>
                            </div>
                            <div class="panel panel-primary">
                                <p class="user">User name</p>
                                <p class="tweet">
                                    不止不止。RT @QIAOFU88: 十八大以来，山西省委常委会十三人中，已经有五人被查，占省常委的38.5%.如果再加上曾任太原市委书记申维辰，则已经超过46%.而据消息可靠人士透露，还会有至少一名常委肯定还会下马。这样就是说，至少有一半是贪官污吏。山西如此，其它地区难道不是
                                </p>
                                <br>
                                <a class="btn icon-btn btn-collect" href="#"><span class="glyphicon btn-glyphicon glyphicon-plus img-circle text-collect"></span>Collect</a>
                            </div>
                            <div class="panel panel-primary">
                                <p class="user">User name</p>
                                <p class="tweet">
                                    睇到一張支持全民退保講 2041 年的圖，又有人寫：「以為乜都唔做, 我地到時仍然可以馬照跑, 舞照跳?」睇完真係好想笑－－2041？你爭取到 2017 年有真普選先發夢啦，香港的後生仔而家就要去跳樓啦，仲諗 2041? http://t.co/sr6nZqSHJC
                                </p>
                                <br>
                                <a class="btn icon-btn btn-collect" href="#"><span class="glyphicon btn-glyphicon glyphicon-plus img-circle text-collect"></span>Collect</a>
                            </div>
                            <div class="panel panel-primary">
                                <p class="user">User name</p>
                                <p class="tweet">
                                    反佔中: 藏曼巴 苗家秘方 肤颜美 浴足粉 8gx36袋/盒x3盒:  http://t.co/T6...
                                </p>
                                <br>
                                <a class="btn icon-btn btn-collect" href="#"><span class="glyphicon btn-glyphicon glyphicon-plus img-circle text-collect"></span>Collect</a>
                            </div>
                            <div class="panel panel-primary">
                                <p class="user">User name</p>
                                <p class="tweet">
                                    [CENSORED POST]發起公投的澳門民間組織發起人被帶走協助調查。世人的眼光多聚焦香港佔中行...
                                </p>
                                <br>
                                <a class="btn icon-btn btn-collect" href="#"><span class="glyphicon btn-glyphicon glyphicon-plus img-circle text-collect"></span>Collect</a>
                            </div>
                            <div class="panel panel-primary">
                                <p class="user">User name</p>
                                <p class="tweet">
                                    “夺妻占财”风波法官被处理，不冤 - 新浪网: 星辰在线“夺妻占财”风波法官被处理，不冤新浪网这起“...
                                </p>
                                <br>
                                <a class="btn icon-btn btn-collect" href="#"><span class="glyphicon btn-glyphicon glyphicon-plus img-circle text-collect"></span>Collect</a>
                            </div>
                            <div class="panel panel-primary">
                                <p class="user">User name</p>
                                <p class="tweet">
                                    程翔：重讀《歷史的先聲》 拒絕假「普選」 http://t.co/nBWG9Y4Z4W
                                </p>
                                <br>
                                <a class="btn icon-btn btn-collect" href="#"><span class="glyphicon btn-glyphicon glyphicon-plus img-circle text-collect"></span>Collect</a>
                            </div>
                            <div class="panel panel-primary">
                                <p class="user">User name</p>
                                <p class="tweet">
                                    汉丰网 港媒解读中央对香港政改底线：特首必须爱国爱港 中国新闻网 针对“占中”威胁，李飞直言，“如果...
                                </p>
                                <br>
                                <a class="btn icon-btn btn-collect" href="#"><span class="glyphicon btn-glyphicon glyphicon-plus img-circle text-collect"></span>Collect</a>
                            </div>
                            <div class="panel panel-primary">
                                <p class="user">User name</p>
                                <p class="tweet">
                                    遭中共恐吓 戴耀廷最后通牒：人大阻政改占中必启动 - 大纪元 http://t.co/4S81poA...
                                </p>
                                <br>
                                <a class="btn icon-btn btn-collect" href="#"><span class="glyphicon btn-glyphicon glyphicon-plus img-circle text-collect"></span>Collect</a>
                            </div>
                        </div>
                    </div>

                </div>
                <script type="text/javascript">
                    $(".tweet").tweetParser({
                        urlClass: "tweet_link", //this is default
                        userClass: "tweet_user", //this is default
                        hashtagClass: "hashtag", //this is default
                        target: "_blank", //this is default
                        searchWithHashtags: true
                    });
                </script>
                <div class="col-md-7">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="row">
                                <h2>Selected Tags</h2>
                                <div class="input-group">
                                    <div class="form-control tag-input" >
                                        <ul>
                                            <li>
                                                <span class="label label-default">
                                                    梁振英
                                                    <button type="button" class="close" data-dismiss="li">
                                                        <span aria-hidden="true">&times;</span>
                                                        <span class="sr-only">Close</span>
                                                    </button>
                                                </span>
                                            </li>
                                            <li>
                                                <span class="label label-primary">
                                                    10/7
                                                    <button type="button" class="close" data-dismiss="li">
                                                        <span aria-hidden="true">&times;</span>
                                                        <span class="sr-only">Close</span>
                                                    </button>
                                                </span>
                                            </li>
                                            <li>
                                                <span class="label label-success">
                                                    普选权           
                                                    <button type="button" class="close" data-dismiss="li">
                                                        <span aria-hidden="true">&times;</span>
                                                        <span class="sr-only">Close</span>
                                                    </button>
                                                </span>
                                            </li>
                                            <li>
                                                <span class="label label-default">
                                                    曾伟雄
                                                    <button type="button" class="close" data-dismiss="li">
                                                        <span aria-hidden="true">&times;</span>
                                                        <span class="sr-only">Close</span>
                                                    </button>
                                                </span>
                                            </li>
                                        </ul>

                                    </div> 
                                    <button class="btn btn-apply" type="button">Apply</button>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="panel panel-info">
                                    <div class="panel-heading">Information Panel</div>
                                    <div class="panel-body">...</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="panel with-nav-tabs panel-primary">
                                <div class="panel-heading">
                                    <ul class="nav nav-tabs pull-right">
                                        <li class="active"><a href="#tab1primary" data-toggle="tab">Time</a></li>
                                        <li><a href="#tab2primary" data-toggle="tab">Keywords</a></li>
                                        <li><a href="#tab3primary" data-toggle="tab">Users</a></li>
                                        <li><a href="#tab4primary" data-toggle="tab">Noun Co-word</a></li>
                                    </ul>
                                </div>
                                <div class="panel-body" style="height: 450px; padding: 2px;">
                                    <div class="tab-content">
                                        <div class="tab-pane fade in active" id="tab1primary">
                                            <div id="timeChart" style="overflow-y: hidden; overflow-x:auto;"></div>
                                        </div>
                                        <div class="tab-pane fade" id="tab2primary">
                                            <div id="keywordsGraph"></div>
                                        </div>
                                        <div class="tab-pane fade" id="tab3primary">
                                            <div class="col-md-10" style="padding:0px;">
                                                <div id="userGraph"></div>
                                            </div>
                                            <div class="col-md-2" style="padding:0px;">
                                                <div id="control-pane">
                                                    <h2 class="underline">filters</h2>
                                                    <div>
                                                        <h3>min degree <span id="min-degree-val">0</span></h3>
                                                        0 <input id="min-degree" type="range" min="0" max="0" value="0"> <span id="max-degree-value">0</span><br>
                                                    </div>
                                                    <span class="line"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="tab4primary">
                                            <div class="panel panel-default">
                                                <button type="button" name="restart-camera" id="restart-camera" class="btn btn-default">Reset Camera</button>
                                                <button type="button" name="reset-graph" id="reset-graph" class="btn btn-default">Reset Graph</button>
                                                <div id="relationGraph"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <h2>History</h2>
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
                    </div>
                </div>
            </div> <!-- /.container-fluid -->
        </nav>
    </body>
</html>