<!DOCTYPE html>
<html>
    <head>
        <title>Survey Description</title>
        <meta charset="utf-8">
        <script src="jquery/jquery-2.1.3.min.js"></script>
        <script src="jquery/jquery-ui.min.js"></script>
        <link href="bootstrap-3.3.1-dist/dist/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <script src="bootstrap-3.3.1-dist/dist/js/bootstrap.min.js"></script>
        <style type="text/css">
            body, html {
                background-image: url('img/page-background.png');
                font-family: "Trebuchet MS Black", "LiHei Pro", "Microsoft JhengHei";
                overflow: hidden; /*no scrollable bar*/
            }
        </style>
    </head>
    <body>
        <div class="jumbotron">
            <div class="container">
                <h2>以社群媒體輔助新聞敘事之可客製化資訊系統</h2>
                <div class="row">
                    <p>實驗說明</p>
                </div>
                <form class="form-inline" role="form">
                    <div class="form-group">
                        <label>姓名：</label>
                        <input type="text" class="form-control" id="userName" required="true">
                    </div>
                    <div class="form-group">
                        <label>實驗編號：</label>
                        <input type="text" class="form-control" id="userNumber" required="true">
                    </div>
                    <button type="button" class="btn btn-success" id="btn-caseStart">開始實驗</button>
                </form>
            </div>
        </div>
        <script>
            $("#btn-caseStart").click(function () {
                var userName = $('#userName').val();
                var userNumber = $('#userNumber').val();
                $.ajaxSetup({
                    cache: false
                });

                var jqxhr = $.getJSON('ajax_RecordUser.php', {
                    uName: userName,
                    uID: userNumber
                });

                jqxhr.done(function (data) {
                    if (data.rsStat) {
                        location.href = "browsing.php";
                    } else {
                        showMessage('danger', data.rsRecord);
                    }
                });
            });
        </script>
    </body>
</html>