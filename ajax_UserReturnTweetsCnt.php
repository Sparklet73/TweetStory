<?php

require_once 'config.php';

$strUser = filter_input(INPUT_GET, 'su', FILTER_SANITIZE_STRING);

if(!$strUser) {
    $arrResult['rsStat'] = false;
    $arrResult['rsUser'] = "The parameter has problem.";
}


try {
    $dbh = new PDO("mysql:host=$hostname;dbname=$database;charset=utf8", $dbuser, $dbpass);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT DATE_FORMAT(`created_at`, '%Y/%m/%d') dd, count(*) cnt
            FROM `HKALLzh_main` 
            WHERE `from_user_name` LIKE '" . $strUser . "' GROUP BY dd";

    $stmt = $dbh->prepare($sql);
    $stmt->execute();

    $arrResult['rsStat'] = true;
    $arrResult['rsUser'] = array();
    while($arrQue =  $stmt->fetch(PDO::FETCH_ASSOC)) {
        array_push($arrResult['rsUser'], array(
            "dd"=>$arrQue['dd'],
            "cnt"=>$arrQue['cnt']
        ));
    }
} catch (PDOException $ex) {
    $arrResult['rsStat'] = false;
    $arrResult['rsUser'] = $ex->getMessage();
} catch (Exception $exc) {
    echo $exc->getMessage();
}

unset($dbh);

header("Content-type: application/json; charset=utf-8");

echo json_encode($arrResult);
