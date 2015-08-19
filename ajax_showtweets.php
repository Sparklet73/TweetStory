<?php
require_once 'config.php';

if(isset($_GET['tweetTags'])) {
    $arrTweetList = $_GET['tweetTags'];
//    $arrResult['rsStat'] = true;
//    $arrResult['rsTweet'] = $json;
    var_dump($arrTweetList);
} else {
    $arrResult['rsStat'] = false;
    $arrResult['rsTweet'] = "The parameter has problem.";
}

try {
    $dbh = new PDO("mysql:host=$hostname;dbname=$database;charset=utf8", $dbuser, $dbpass);
    $dbh ->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

    foreach($arrTweetList as $key => $value){
        
    }
    
} catch (pDOException $ex) {

} catch (Exception $exc) {
    echo $exc->getMessage();
}
//echo json_encode($arrResult);