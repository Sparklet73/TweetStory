<?php

ini_set('display_errors', true);
error_reporting(E_ALL | E_STRICT);

require_once 'config.php';

$intUID = (int) filter_input(INPUT_GET, 'uID', FILTER_SANITIZE_NUMBER_INT);
$intTID = (int) filter_input(INPUT_GET, 'tID', FILTER_SANITIZE_NUMBER_INT);

if (!$intUID || !$intTID) {
    $arrResult['rsStat'] = false;
    $arrResult['rsRes'] = "The parameter has problem.";
}

try {
    $dbh = new PDO("mysql:host=$hostname;dbname=$database;charset=utf8", $dbuser, $dbpass);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $sql = "SELECT `tag` FROM `HKALLzh_collections` "
            . "WHERE `userID` = ". $intUID ." "
            . "and `tweetID` = ". $intTID ." GROUP BY `tag`";
    $tweet_tag = $dbh->prepare($sql);
    $tweet_tag->execute();
    $arrQue = $tweet_tag->fetchAll(PDO::FETCH_ASSOC);
    $arrTags = array();
    foreach($arrQue as $s) {
        array_push($arrTags, $s['tag']);
    }
    
    $strTags = implode("|", $arrTags);
    
//    save material tweet
    $sql_write = "INSERT INTO `HKALLzh_materials`(`materialID`, `userID`, `tweetID`, `tags`) VALUES (NULL, :userID, :tweetID, :tags)";
    $stmt = $dbh->prepare($sql_write);
    if ($stmt) {
        $stmt->bindParam(':userID', $intUID, \PDO::PARAM_INT);
        $stmt->bindParam(':tweetID', $intTID, \PDO::PARAM_INT);
        $stmt->bindParam(':tags', $strTags, \PDO::PARAM_STR);
        $stmt->execute();
    }
    $arrResult['rsStat'] = true;
    $arrResult['rsRes'] = "Success!";
 
} catch (PDOException $ex) {
    $arrResult['rsStat'] = false;
    $arrResult['rsRes'] = $ex->getMessage();
} catch (Exception $exc) {
    echo $exc->getMessage();
}

unset($dbh);

header("Content-type: application/json; charset=utf-8");

echo json_encode($arrResult);
