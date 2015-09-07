<?php

ini_set('display_errors', true);
error_reporting(E_ALL | E_STRICT);

require_once 'config.php';

$intUID = (int) filter_input(INPUT_GET, 'uID', FILTER_SANITIZE_NUMBER_INT);

if (!$intUID) {
    $arrResult['rsStat'] = false;
    $arrResult['rsRes'] = "The parameter has problem.";
}

try {
    $dbh = new PDO("mysql:host=$hostname;dbname=$database;charset=utf8", $dbuser, $dbpass);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $sql = "SELECT `HKALLzh_main`.`text` "
            . "FROM `HKALLzh_materials`, `HKALLzh_main` "
            . "WHERE `userID` = " . $intUID ." AND `HKALLzh_main`.`id` = `HKALLzh_materials`.`tweetID`";
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    
    $arrResult['rsStat'] = true;
    $arrResult['rsRes'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
 
} catch (PDOException $ex) {
    $arrResult['rsStat'] = false;
    $arrResult['rsRes'] = $ex->getMessage();
} catch (Exception $exc) {
    echo $exc->getMessage();
}

unset($dbh);

header("Content-type: application/json; charset=utf-8");

echo json_encode($arrResult);
