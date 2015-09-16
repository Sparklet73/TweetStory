<?php
session_start();

require_once 'config.php';

$strUName = filter_input(INPUT_GET, 'uName', FILTER_SANITIZE_STRING);
$intUID = (int) filter_input(INPUT_GET, 'uID', FILTER_SANITIZE_NUMBER_INT);

if (!$strUName || !$intUID) {
    $arrResult['rsStat'] = false;
    $arrResult['rsRecord'] = "The parameter has problem.";
}

try {
    $dbh = new PDO("mysql:host=$hostname;dbname=$database;charset=utf8", $dbuser, $dbpass);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $sql = 'INSERT INTO `HKALLzh_experiment`(`userID`, `userName`) '
            . 'VALUES (:userID, :userName)';
    $record = $dbh->prepare($sql);
    if ($record) {
        $record->bindParam(':userID', $intUID, \PDO::PARAM_INT);
        $record->bindParam(':userName', $strUName, \PDO::PARAM_STR);
        $record->execute();
    }
    $arrResult['rsStat'] = true;
    $arrResult['rsRecord'] = "Success!";
    
    $_SESSION['uID'] = $intUID;
    
} catch (PDOException $ex) {
    $arrResult['rsStat'] = false;
    $arrResult['rsRecord'] = $ex->getMessage();
} catch (Exception $exc) {
    echo $exc->getMessage();
}

unset($dbh);

header("Content-type: application/json; charset=utf-8");

echo json_encode($arrResult);
