<!DOCTYPE html>
<html>
    <head>
        <title>History</title>
        <meta charset="utf-8">
        <script src="jquery/jquery-2.1.3.min.js"></script>
        <script src="jquery/jquery-ui.min.js"></script>
        <link href="bootstrap-3.3.1-dist/dist/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <script src="bootstrap-3.3.1-dist/dist/js/bootstrap.min.js"></script>
        <script src="bootstrap-slider/bootstrap-slider.min.js"></script>
        <link rel="stylesheet" href="bootstrap-slider/bootstrap-slider.min.css" type="text/css" />
        <script src="returnHistory.js"></script>
        <style type="text/css">
            body, html {
                background-image: url('img/page-background.png');
                font-family: "Trebuchet MS Black", "LiHei Pro", "Microsoft JhengHei";
                /*overflow: hidden;*/
            }
            .history {
                overflow-x: scroll;
                overflow-y: hidden;
                white-space: nowrap;
            }
            .history_item {
                white-space: initial;
                display: inline-block;
                width: 300px;
                margin: 10px;
                box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.26);
                vertical-align: top;
            }
            //----4 tags color (list-group) 設定 開始-----
            .well {
                width: inherit;
            }
            .timeTag{
                background: #FFC600;
            }
            .keywordsTag{
                background: #A6DE38;
            }
            .usersTag{
                background: #248E8E;
            }
            .nounsTag{
                background: #ED3C3C;
            }
            //----4 tags color (list-group) 設定 結束-----
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
                            <li><a href="browsing.php">Browsing Room<span class="sr-only">(current)</span></a></li>
                            <li><a href="materials.php">Materials Room</a></li>
                            <li class="active"><a href="#">History</a></li>
                        </ul>
                        <ul class="nav navbar-nav navbar-right">
                            <p class="navbar-text">Dataset: HKALLzh --- 497,519 tweets (from 2014-08-24 22:06:20 to 2014-12-17 13:55:22)</p>
                        </ul>
                    </div><!-- /.navbar-collapse -->
                </div> <!-- /.container-fluid -->
            </nav>
            <div class="col-md-12">
                <h4 style="margin-top: 0px;margin-bottom: 0px;">History</h4>
                <div class="history" id="history">
                </div>
                <script>
                    returnHistory(0);
                </script>
            </div>
        </div>
    </body>
</html>