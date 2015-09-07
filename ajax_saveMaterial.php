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
    
//    save material tweet
    $sql_write = "INSERT INTO `HKALLzh_materials`(`materialID`, `userID`, `tweetID`) VALUES (NULL, :userID, :tweetID)";
    $stmt = $dbh->prepare($sql_write);
    if ($stmt) {
        $stmt->bindParam(':userID', $intUID, \PDO::PARAM_INT);
        $stmt->bindParam(':tweetID', $intTID, \PDO::PARAM_INT);
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
