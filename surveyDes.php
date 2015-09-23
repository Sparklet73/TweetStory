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
                <h2>以社群媒體輔助新聞主題探索的視覺化資訊系統</h2>
                <div class="row">
                    <p>您好，感謝您參加『以社群媒體輔助新聞主題探索的視覺化資訊系統』之實驗。

本研究目的是在瞭解您在使用本系統之行為及新聞主題之探索模式，實驗分為兩個階段，第一階段實驗為引導式任務，目標為熟悉本系統介面操作；第二階段為指定任務，目標為利用本系統完成指定任務。兩階段實驗中，各需要填寫一份問卷，且進行約十分鐘之訪談，實驗時間約一小時。
</p>
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