<?php

require_once 'config.php';

$intUID = (int) filter_input(INPUT_GET, 'uID', FILTER_SANITIZE_NUMBER_INT);
$strContent = filter_input(INPUT_GET, 'content', FILTER_SANITIZE_STRING);


if (!$intUID || !$strContent) {
    $arrResult['rsStat'] = false;
    $arrResult['rsTxt'] = "The parameter has problem.";
}

try {
    $dbh = new PDO("mysql:host=$hostname;dbname=$database;charset=utf8", $dbuser, $dbpass);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $sql_update = "UPDATE `HKALLzh_userNote` SET `text` = '" . $strContent . "' WHERE `uID` = " . $intUID;
    $stmt_update = $dbh->prepare($sql_update);
    $stmt_update->execute();
    
    $arrResult['rsStat'] = true;
    $arrResult['rsTxt'] = "success";
    
} catch (PDOException $ex) {
    $arrResult['rsStat'] = false;
    $arrResult['rsTxt'] = $ex->getMessage();
} catch (Exception $exc) {
    $arrResult['rsStat'] = false;
    $arrResult['rsTxt'] = $ex->getMessage();
}

unset($dbh);

header("Content-type: application/json; charset=utf-8");

echo json_encode($arrResult);
