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
        <div id="wrapper" class="toggle">
            <div id="sidebar-wrapper">
                <ul class="sidebar-nav">
                    <li class="sidebar-brand">
                        <a href="#">
                            Tweet News Story
                        </a>
                    </li>
                    <li>
                        <a href="browsing.php">Browsing Room</a>
                    </li>
                    <li>
                        <a href="materials.php">Materials Room</a>
                    </li>
                    <li>
                        <a href="#">Contact</a>
                    </li>
                </ul>
            </div>
            <button type="button" id="menu-toggle" class="btn btn-info" aria-label="Left Align">
                <span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
            </button>
            <script>
                $("#menu-toggle").click(function (e) {
                    e.preventDefault();
                    $("#wrapper").toggleClass("toggled");
                });
            </script>
            <div id="page-content-wrapper">
                <div class="container-fluid">
                    <div class="col-md-8">
                        <div class="row">                            
                            <div class="col-md-5">
                                <ol class="simple_with_animation vertical">
                                    <li>RT @wangdan1989: 我們決定：一旦中共悍然武力鎮壓佔中行動，我們將立即在全世界範圍內，...</li>                                  
                                    <li>RT @inmediahk: 【佔中即時】(20:00)學聯副秘書長岑敖暉表示，今天不是香港最黑暗的一天，因為今天只是把香港帶去了另一階段，因為接下來只會有更多的不合作運動和抗爭，不再單單是上街和投票。他又重申，極權的中共可以抹殺政制，但不能抹殺香港人爭取民... http:/…</li>
                                    <li>在大雨中的佔中集會，心情極度沉重。朋友們，我們已經無路可退了。為我們的孩子，為我們的未來，我們不能認輸，一起公民抗命，一起佔領中環吧。</li>
                                </ol>
                            </div>
                            <div class="col-md-5">
                                <ol class="simple_with_animation vertical">
                                    <li>新華社評論員：一旦佔中發生香港將不得安寧 http://t.co/t9NxlXFWrt</li>
                                    <li>齊鵬飛：走出普選第一步後才可完善 http://t.co/XfAlwGhzwV</li>
                                    <li>佔中行動集會　陳健民批評人大決定荒謬 http://t.co/VLKqL6ezD6</li>
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
                        <div class="row" >
                            <div class="summernote" id="summernote">Write a news story...</div>
                        </div>
                        <script>
                            $(document).ready(function () {
                                $('.summernote').summernote({
                                    width: 450,
                                    minHeight: null, // set minimum height of editor
                                    maxHeight: null, // set maximum height of editor
                                    focus: true // set focus to editable area after initializing summernote
                                });
                                $('.summernote').summernote();
                            });
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>