<!DOCTYPE html>
<html>
    <head>
        <title>Materials Room</title>
        <meta charset="utf-8">
        <script src="jquery/jquery-2.1.3.min.js"></script>
        <script src="jquery/jquery-ui.min.js"></script>
        <link href="bootstrap-3.3.1-dist/dist/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <script src="bootstrap-3.3.1-dist/dist/js/bootstrap.min.js"></script>
        <!--<link href="summernote/font-awesome.min.css" rel="stylesheet">-->
        <link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css" rel="stylesheet">
        <script src="summernote/summernote.min.js"></script>
        <link href="summernote/summernote.css" rel="stylesheet" />
        <link rel="stylesheet" href="css/simple-sidebar.css" type="text/css" />
        <script src="draggable/jquery-sortable-min.js"></script>
        <link href="draggable/application.css" rel="stylesheet"/>
        <style type="text/css">
            body, html {
                background-color: rgb(255,245,245);
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

        </style>
    </head>
    <body>
        <div class="mywindow" style="width:1366px; margin:0 auto;">
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
                        </ul>
                        <ul class="nav navbar-nav navbar-right">
                            <p class="navbar-text">Dataset: HKALLzh --- 497,519 tweets (from 2014-08-24 22:06:20 to 2014-12-17 13:55:22)</p>
                        </ul>
                    </div><!-- /.navbar-collapse -->
                </div> <!-- /.container-fluid -->
            </nav>
            <div class="col-md-8">
                <div class="row">                            
                    <div class="col-md-4">
                        <h4>Materials Box</h4>
                        <ol class="simple_with_animation vertical">
                            <li>RT @wangdan1989: 我們決定：一旦中共悍然武力鎮壓佔中行動，我們將立即在全世界範圍內，...</li>                                  
                            <li>RT @inmediahk: 【佔中即時】(20:00)學聯副秘書長岑敖暉表示，今天不是香港最黑暗的一天，因為今天只是把香港帶去了另一階段，因為接下來只會有更多的不合作運動和抗爭，不再單單是上街和投票。他又重申，極權的中共可以抹殺政制，但不能抹殺香港人爭取民... http:/…</li>
                            <li>在大雨中的佔中集會，心情極度沉重。朋友們，我們已經無路可退了。為我們的孩子，為我們的未來，我們不能認輸，一起公民抗命，一起佔領中環吧。</li>
                            <li>"@nytchinese: 周五夜间，香港“占中”组织成员在中环政府合署外集会，呼吁民主选举。《苹果...</li>
                            <li>全中国都看着香港占中发展！</li>
                            <li>@dingyi777 反對佔中，反對危害，反對盜竊，反對弄虛作假..希望廉署給廣大市民一個交代......</li>
                        </ol>
                    </div>
                    <div class="col-md-4">
                        <input type="text" class="span6" align= "text-center" placeholder="Subject" style="margin-bottom: 10px;">
                        <ol class="simple_with_animation vertical">
                            <li>新華社評論員：一旦佔中發生香港將不得安寧 http://t.co/t9NxlXFWrt</li>
                            <li>齊鵬飛：走出普選第一步後才可完善 http://t.co/XfAlwGhzwV</li>
                            <li>佔中行動集會　陳健民批評人大決定荒謬 http://t.co/VLKqL6ezD6</li>
                            <li>占中投票個資疑外洩　港警調查http://t.co/ybRo9mf3GJ  #反占中</li>
                        </ol>
                    </div>
                    <div class="col-md-4">
                        <input type="text" class="span6" align= "text-center" placeholder="Subject" style="margin-bottom: 10px;">
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
                    <div class="summernote" id="summernote">Write a news story...</div>
                </div>
                <script>
                    $(document).ready(function () {
                        $('.summernote').summernote({
                            width: 430,
                            minHeight: null, // set minimum height of editor
                            maxHeight: null, // set maximum height of editor
                            focus: true // set focus to editable area after initializing summernote
                        });
                        $('.summernote').summernote();
                    });
                </script>
            </div>
        </div>
    </body>
</html>