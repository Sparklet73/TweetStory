<?php
require_once 'config.php';

//$intUID = (int) filter_input(INPUT_GET, 'uID', FILTER_SANITIZE_NUMBER_INT);
$intUID = 0;

try {
    $dbh = new PDO("mysql:host=$hostname;dbname=$database;charset=utf8", $dbuser, $dbpass);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT `HKALLzh_main`.`text` "
            . "FROM `HKALLzh_materials`, `HKALLzh_main` "
            . "WHERE `userID` = " . $intUID . " AND `HKALLzh_main`.`id` = `HKALLzh_materials`.`tweetID`";
    $stmt = $dbh->prepare($sql);
    $stmt->execute();

    $materialContent = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $sql_tags = "SELECT `tag` FROM `HKALLzh_collections`"
            . "WHERE `userID` = " . $intUID . " GROUP BY `tag`";
    $stmt_tags = $dbh->prepare($sql_tags);
    $stmt_tags->execute();

    $tags_rs = $stmt_tags->fetchAll(PDO::FETCH_ASSOC);
    $tags_ov = array();
    foreach ($tags_rs as $tag) {
        array_push($tags_ov, $tag['tag']);
    }
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
            .tagsBox{
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
            #materialBox .li {
                background-color: #fff;
            }
            input:focus, input.focus {border: 2px solid #2E4272;}
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
                    <div class="col-md-8">
                        <div class="col-md-6">
                            <input type="text" id="subg1" placeholder="Group some tags..." style="margin-bottom: 10px;">
                            <ol class="simple_with_animation vertical" id="grouptags1" style="height:560px;overflow-y:scroll;"></ol>
                        </div>
                        <div class="col-md-6">
                            <input type="text" id="subg2" placeholder="Group some tags..." style="margin-bottom: 10px;">
                            <ol class="simple_with_animation vertical" id="grouptags2" style="height:560px;overflow-y:scroll;"></ol>
                        </div>
                        <!-- myModal_a start-->
                        <div class="modal fade" id="myModal_a" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title" id="gridSystemModalLabel">Group some tags you want to focus on.</h4>
                                    </div>
                                    <div class="modal-body">
                                        <?php
                                        foreach ($tags_ov as $tt) {
                                            echo '<input name="selector[]" id="tag|' . $tt . '" class="ads_checkbox_a" type="checkbox" style="margin-right:5px;">' . $tt;
                                        }
                                        ?>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="toggle" class="btn btn-primary" id="modal-save-event1" data-dismiss="modal">Save changes</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- myModal_a end-->
                        <!-- myModal_b start-->
                        <div class="modal fade" id="myModal_b" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title" id="gridSystemModalLabel">Group some tags you want to focus on.</h4>
                                    </div>
                                    <div class="modal-body">
                                        <?php
                                        foreach ($tags_ov as $tt) {
                                            echo '<input name="selector[]" id="tag|' . $tt . '" class="ads_checkbox_b" type="checkbox" style="margin-right:5px;">' . $tt;
                                        }
                                        ?>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="toggle" class="btn btn-primary" id="modal-save-event2" data-dismiss="modal">Save changes</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- myModal-b end-->
                        <script>
                            var uID = 0;
//                            first group column.
                            $("input#subg1").on("focus", function () {
                                $('#myModal_a').modal('show');
                            });
                            $('#modal-save-event1').on('click', function (evt)
                            {
                                var val_a = [];
                                $('.ads_checkbox_a:checked').each(function (i) {
                                    var w = $(this).attr('id').split('|');
                                    val_a[i] = w[1];
                                });
                                $("input#subg1").val(val_a);
                                groupMaterial(uID, val_a,'#grouptags1');
                            });
                            
//                            second group column.
                            $("input#subg2").on("focus", function () {
                                $('#myModal_b').modal('show');
                            });
                            $('#modal-save-event2').on('click', function (evt)
                            {
                                var val_b = [];
                                $('.ads_checkbox_b:checked').each(function (i) {
                                    var w = $(this).attr('id').split('|');
                                    val_b[i] = w[1];
                                });
                                $("input#subg2").val(val_b);
                                groupMaterial(uID, val_b,'#grouptags2');
                            });
                        </script>
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
            </div>
        </div>
    </body>
</html>
<?php
unseet($dbh);
