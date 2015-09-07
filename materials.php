<?php
require_once 'config.php';

$intUID = (int) filter_input(INPUT_GET, 'uID', FILTER_SANITIZE_NUMBER_INT);


try {
    $dbh = new PDO("mysql:host=$hostname;dbname=$database;charset=utf8", $dbuser, $dbpass);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT `HKALLzh_main`.`text` "
            . "FROM `HKALLzh_materials`, `HKALLzh_main` "
            . "WHERE `userID` = " . $intUID . " AND `HKALLzh_main`.`id` = `HKALLzh_materials`.`tweetID`";
    $stmt = $dbh->prepare($sql);
    $stmt->execute();

    $materialContent = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $ex) {
    $arrResult['rsStat'] = false;
    $arrResult['rsRes'] = $ex->getMessage();
} catch (Exception $exc) {
    echo $exc->getMessage();
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Materials Room</title>
        <meta charset="utf-8">
        <script src="jquery/jquery-2.1.3.min.js"></script>
        <script src="jquery/jquery-ui.min.js"></script>
        <link href="bootstrap-3.3.1-dist/dist/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <script src="bootstrap-3.3.1-dist/dist/js/bootstrap.min.js"></script>
        <!--<link href="tweetParser/css/tweetParser.css" rel="stylesheet" type="text/css" />-->
        <!--<link href="summernote/font-awesome.min.css" rel="stylesheet">-->
        <link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css" rel="stylesheet">
        <script src="summernote/summernote.min.js"></script>
        <link href="summernote/summernote.css" rel="stylesheet" />
        <script src="draggable/jquery-sortable-min.js"></script>
        <link href="draggable/application.css" rel="stylesheet"/>
        <script src="doMaterial.js"></script>
        <script src="tag-it/js/tag-it.min.js"></script>
        <link href="tag-it/css/jquery.tagit.css" rel="stylesheet"/>
        <link href="tag-it/css/tagit.ui-zendesk.css" rel="stylesheet"/>
        <style type="text/css">
            body, html {
                background-image: url('img/page-background.png');
                font-family: "Trebuchet MS Black", "LiHei Pro", "Microsoft JhengHei";
                //overflow: hidden; //no scrollable bar
            }
            body.dragging, body.dragging * {
                cursor: move !important;
            }
            .dragged {
                position: absolute;
                opacity: 0.5;
                z-index: 2000;
            }

            ol.example li.placeholder {
                position: relative;
                /** More li styles **/
            }
            ol.example li.placeholder:before {
                position: absolute;
                /** Define arrowhead **/
            }
            .tagsbox{
                background-color: #fff;
                border: 1px solid #ccc;
                box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
                padding: 4px 6px;
                margin-top: 5px;
                margin-bottom: 10px;
                color: #555;
                vertical-align: middle;
                border-radius: 4px;
                max-width: 100%;
                line-height: 22px;
                cursor: text;
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
            #materialBox .li {
                background-color: #fff;
            }
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
                            <li class="active"><a href="#">Materials Room</a></li>
                            <li><a href="history.php">History</a></li>
                        </ul>
                        <ul class="nav navbar-nav navbar-right">
                            <p class="navbar-text">Dataset: HKALLzh --- 497,519 tweets (from 2014-08-24 22:06:20 to 2014-12-17 13:55:22)</p>
                        </ul>
                    </div><!-- /.navbar-collapse -->
                </div> <!-- /.container-fluid -->
            </nav>
            <div class="col-md-8">
                <div class="row">  
                    <div class="col-md-4" id="materialBox">
                        <h4>Materials Box</h4>
                        <ol class="simple_with_animation vertical" style="height:560px;overflow-y:scroll;">
                            <?php
                            foreach ($materialContent as $content) {
                                echo "<li>" . $content['text'] . "</li>";
                            }
                            ?>
                        </ol>
                    </div>
<!--                    <script>
                        showMaterial(0);
                    </script>-->
                    <div class="col-md-4">
                        <ul id="singleFieldTags"></ul>
                        <!--<input type="text" class="span6" align= "text-center" placeholder="Group some tags..." style="margin-bottom: 10px;">-->
                        <ol class="simple_with_animation vertical">
                            <li>新華社評論員：一旦佔中發生香港將不得安寧 http://t.co/t9NxlXFWrt</li>
                            <li>齊鵬飛：走出普選第一步後才可完善 http://t.co/XfAlwGhzwV</li>
                            <li>佔中行動集會　陳健民批評人大決定荒謬 http://t.co/VLKqL6ezD6</li>
                            <li>占中投票個資疑外洩　港警調查http://t.co/ybRo9mf3GJ  #反占中</li>
                        </ol>
                    </div>
                    <script>
                        $(function () {
                            var sampleTags = ['c++', 'java', 'php', 'coldfusion', 'javascript', 'asp', 'ruby', 'python', 'c', 'scala', 'groovy', 'haskell', 'perl', 'erlang', 'apl', 'cobol', 'go', 'lua'];
                            $('#singleFieldTags').tagit({
                                availableTags: sampleTags,
                                // This will make Tag-it submit a single form value, as a comma-delimited field.
                                singleField: true,
                                removeConfirmation: true
                            });
                        });
                    </script>
                    <div class="col-md-4">
                        <input type="text" class="span6" align= "text-center" placeholder="Group some tags..." style="margin-bottom: 10px;">
                        <ol class="simple_with_animation vertical">
                            <li>新華社評論員：一旦佔中發生香港將不得安寧 http://t.co/t9NxlXFWrt</li>
                            <li>齊鵬飛：走出普選第一步後才可完善 http://t.co/XfAlwGhzwV</li>
                            <li>佔中行動集會　陳健民批評人大決定荒謬 http://t.co/VLKqL6ezD6</li>
                            <li>占中投票個資疑外洩　港警調查http://t.co/ybRo9mf3GJ  #反占中</li>
                        </ol>
                    </div>
                </div>
                <script>
                    $(document).ready(function () {
                        var adjustment;

                        $("ol.simple_with_animation").sortable({
                            group: 'simple_with_animation',
                            pullPlaceholder: false,
                            // animation on drop
                            onDrop: function ($item, container, _super) {
                                var $clonedItem = $('<li/>').css({height: 0});
                                $item.before($clonedItem);
                                $clonedItem.animate({'height': $item.height()});

                                $item.animate($clonedItem.position(), function () {
                                    $clonedItem.detach();
                                    _super($item, container);
                                });
                            },
                            // set $item relative to cursor position
                            onDragStart: function ($item, container, _super) {
                                var offset = $item.offset(),
                                        pointer = container.rootGroup.pointer;

                                adjustment = {
                                    left: pointer.left - offset.left,
                                    top: pointer.top - offset.top
                                };

                                _super($item, container);
                            },
                            onDrag: function ($item, position) {
                                $item.css({
                                    left: position.left - adjustment.left,
                                    top: position.top - adjustment.top
                                });
                            }
                        });
                    });
                </script>
            </div>
            <div class="col-md-4">
                <div class="row" style="margin-left:0px;">
                    <h5>Text Editor</h5>
                    <div class="summernote" id="summernote">Write a news story...</div>
                </div>
                <script>
                    $(document).ready(function () {
                        $('.summernote').summernote({
//                            width: 430,
                            height: 270,
                            minHeight: 250,
                            maxHeight: 250, // set maximum height of editor
                            focus: true // set focus to editable area after initializing summernote
                        });
                        $('.summernote').summernote();
                    });
                </script>
                <div class="row">
                    <h5>Tags Overview</h5>
                    <div class="tagsbox">
                        <span class="label nounsTag" style="margin-right: 3px;">
                            梁振英
                        </span>
                        <span class="label nounsTag" style="margin-right: 3px;">
                            梁振英
                        </span>
                        <span class="label nounsTag" style="margin-right: 3px;">
                            梁振英
                        </span>
                        <span class="label nounsTag" style="margin-right: 3px;">
                            梁振英
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
<?php
unseet($dbh);
