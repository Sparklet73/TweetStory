<?php
require_once 'config.php';

$intUID = 0;
$bm = isset($_GET['bm']);

try {
    $dbh = new PDO("mysql:host=$hostname;dbname=$database;charset=utf8", $dbuser, $dbpass);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    if($bm){
        $sql = "SELECT * FROM `HKALLzh_history` WHERE `userID` =" . $intUID . " AND `bookmarked` = 1"
            . " ORDER BY `HKALLzh_history`.`applied_at` DESC";
    }else{
        $sql = "SELECT * FROM `HKALLzh_history` WHERE `userID` =" . $intUID
            . " ORDER BY `HKALLzh_history`.`applied_at` DESC";
    }
    
    $stmt = $dbh->prepare($sql);
    $stmt->execute();

    $arrResult['rsHistory'] = array();
    $arr10tweetsID = array();

    while ($arrQue = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $arrAns[$arrQue['historyID']] = $arrQue;
    }
} catch (PDOException $ex) {
    $arrResult['rsStat'] = false;
    $arrResult['rsHistory'] = $ex->getMessage();
} catch (Exception $exc) {
    echo $exc->getMessage();
}
?>
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
        <style type="text/css">
            body, html {
                background-image: url('img/page-background.png');
                font-family: "Trebuchet MS Black", "LiHei Pro", "Microsoft JhengHei";
                /*                overflow: hidden;*/
            }
            .history {
                overflow-x: scroll;
                overflow-y: hidden;
                /*white-space: nowrap;*/ //控制是否要讓history_item不換行陳列
            }
            .history_item {
                white-space: nowrap;
                display: inline-block;
                width: 180px;
                margin: 10px;
                box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.26);
                vertical-align: top;
                border-radius: 5px;
            }
            .history_item:hover {cursor:pointer;transform: translateY(-5px);box-shadow: 2px 2px 2px rgba(0, 0, 0, 0.6);}
            /*----4 tags color (list-group) 設定 開始-----*/
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
            /*----4 tags color (list-group) 設定 結束-----*/
            .list-group-item {
                text-align: initial;
            }
            .hitemTxt {
                text-align: right;
                overflow: hidden;
                width: 160px;
            }
            .hitemTxt:hover { overflow-x:auto; }
        </style>
    </head>
    <body>
        <div class="mywindow" style="margin:0 auto;">
            <nav class="navbar navbar-inverse navbar-fixed-top">
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
                <h4 style="margin-top:60px;"></h4>
                <b style="color:#686868;">Here are the history you have explored. You can also toggle the button on the right to just see bookmarks.</b>
                <?php
                if($bm){
                    echo '<a name="btn_filterBM" class="btn btn-default btn-filterBM" href="history.php">HISTORY</a>';
                }else{
                    echo '<a name="btn_filterBM" class="btn btn-success btn-filterBM" href="history.php?bm">BOOKMARK</a>';
                }
                ?>
                <br><b style="color:#686868;">Click the history item if you want to recover that state.</b>
                <div class="history" id="history" style="text-align: center;">
                    <?php
                    foreach ($arrAns as $k => $v) {
                        $rtn_content = '';
                        $rtn_content .= '<div class="history_item" id="hs_' . $k . '">';
                        if ($v['bookmarked'] == 1) {
                            $rtn_content .= '<span type="button" class="glyphicon glyphicon-bookmark" style="transform:scale(1.5,1.5);color:limegreen;text-shadow: black 1px 1px 1px;"></span>';
                        }
                        $rtn_content .= '<br><h5 style="text-align:right;display:inline;">';
                        $rtn_content .= $v['applied_at'] . '</h5><ul class="list-group"><li class="list-group-item" style="min-height: 50px;><h5 class="list-group-item-heading">Time:';
                        $rtn_content .= '<span style="margin-left:5px;">' . $v['time_w'] . '</span></h5><p class="list-group-item-text hitemTxt">';
                        $word = explode('|', $v['time']);
                        foreach ($word as $w) {
                            $rtn_content .= '<span class="label timeTag" style="margin-left: 3px;">';
                            $rtn_content .= $w . '</span>';
                        }
                        $rtn_content .= '</p></li><li class="list-group-item" style="min-height: 50px;><h5 class="list-group-item-heading">Keywords:';
                        $rtn_content .= '<span style="margin-left:5px;">' . $v['keywords_w'] . '</span></h5><p class="list-group-item-text hitemTxt">';
                        $word = explode('|', $v['keywords']);
                        foreach ($word as $w) {
                            $rtn_content .= '<span class="label keywordsTag" style="margin-left: 3px;">';
                            $rtn_content .= $w . '</span>';
                        }
                        $rtn_content .= '</p></li><li class="list-group-item" style="min-height: 50px;><h5 class="list-group-item-heading">Users:';
                        $rtn_content .= '<span style="margin-left:5px;">' . $v['users_w'] . '</span></h5><p class="list-group-item-text hitemTxt">';
                        $word = explode('|', $v['users']);
                        foreach ($word as $w) {
                            $rtn_content .= '<span class="label usersTag" style="margin-left: 3px;">';
                            $rtn_content .= $w . '</span>';
                        }
                        $rtn_content .= '</p></li><li class="list-group-item" style="min-height: 50px;><h5 class="list-group-item-heading">Nouns:';
                        $rtn_content .= '<span style="margin-left:5px;">' . $v['nouns_w'] . '</span></h5><p class="list-group-item-text hitemTxt">';
                        $word = explode('|', $v['nouns']);
                        foreach ($word as $w) {
                            $rtn_content .= '<span class="label nounsTag" style="margin-left: 3px;">';
                            $rtn_content .= $w . '</span>';
                        }
                        $rtn_content .= '</p></li></ul></div>';
                        echo $rtn_content;
                    }
                    ?>
                </div>
                <script>
                    $(document).ready(function () {
                        $("#history").on("click", ".history_item", function(){
                            var reHID = $(this).attr("id");
                            reHID = reHID.replace("hs_", "");
                            location.href = "browsing.php?reHID=" + reHID ;
                        });
                    });
                </script>
            </div>
        </div>
    </body>
</html>
<?php
unset($dbh);
