<?php

ini_set('display_errors', true);
error_reporting(E_ALL | E_STRICT);

require_once 'config.php';

$boolBM = filter_input(INPUT_GET, 'bm', FILTER_VALIDATE_BOOLEAN);
$intHID = (int) filter_input(INPUT_GET, 'hID', FILTER_SANITIZE_NUMBER_INT);

if (!$boolBM || !$intHID) {
    $arrResult['rsStat'] = false;
    $arrResult['rsRes'] = "The parameter has problem.";
}

try {
    $dbh = new PDO("mysql:host=$hostname;dbname=$database;charset=utf8", $dbuser, $dbpass);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    if ($boolBM == TRUE) {
        $sql = "UPDATE `HKALLzh_history` SET `bookmarked` = 1 WHERE `historyID` = " . $intHID;
    } else if ($boolBM == FALSE) {
        $sql = "UPDATE `HKALLzh_history` SET `bookmarked` = 0 WHERE `historyID` = " . $intHID;
    }
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    
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
