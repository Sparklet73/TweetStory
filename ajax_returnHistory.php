<?php

ini_set('display_errors', true);
error_reporting(E_ALL | E_STRICT);

require_once 'config.php';

$intUID = (int) filter_input(INPUT_GET, 'uID', FILTER_SANITIZE_NUMBER_INT);

if (!$intUID) {
    $arrResult['rsStat'] = false;
    $arrResult['rsHistory'] = "The parameter has problem.";
}

try {
    $dbh = new PDO("mysql:host=$hostname;dbname=$database;charset=utf8", $dbuser, $dbpass);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT * FROM `HKALLzh_history` WHERE `userID` =" . $intUID;
    $stmt = $dbh->prepare($sql);
    $stmt->execute();

    $arrResult['rsStat'] = true;
    $arrResult['rsHistory'] = array();
    $arr10tweetsID = array();

    while($arrQue = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $arrAns[$arrQue['historyID']] = $arrQue;
    }
    
    foreach ($arrAns as $k => $v) {
        $arrTweets = explode("|", $v['set_tweetsID']);
        unset($arrAns[$k]['set_tweetsID']);
        for ($i = 0; $i < 10; $i++) {
            $arr10tweetsID[$v['historyID']][] = $arrTweets[$i];
        }
    }
    
    $arr10tweetsContent = array();
    foreach ($arr10tweetsID as $k => $v) {
        $sql_tweets = "SELECT `text` FROM `HKALLzh_main` WHERE `id` IN (" . implode(',', array_values($v)) . ")";        
        $stmt = $dbh->prepare($sql_tweets);
        $stmt->execute();
        $arr10tweetsContent[$k] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
        
    foreach($arrAns as $k => $v) {
        $arrAns[$v['historyID']]['tweets'] = $arr10tweetsContent[$v['historyID']];
    }
    $arrResult['rsHistory'] = $arrAns;
    
} catch (PDOException $ex) {
    $arrResult['rsStat'] = false;
    $arrResult['rsHistory'] = $ex->getMessage();
} catch (Exception $exc) {
    echo $exc->getMessage();
}

unset($dbh);

header("Content-type: application/json; charset=utf-8");

echo json_encode($arrResult);
