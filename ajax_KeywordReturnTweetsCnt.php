<?php

require_once 'config.php';

$strKeyword = filter_input(INPUT_GET, 'sk', FILTER_SANITIZE_STRING);

if(!$strKeyword) {
    $arrResult['rsStat'] = false;
    $arrResult['rsKeyword'] = "The parameter has problem.";
}

try {
    $dbh = new PDO("mysql:host=$hostname;dbname=$database;charset=utf8", $dbuser, $dbpass);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$sql = "SELECT DATE_FORMAT(`created_at`, '%Y/%m/%d') dd, count(*) cnt
            FROM `HKALLzh_main` 
            WHERE `text` LIKE '%" . $strKeyword . "%' GROUP BY dd";

    $stmt = $dbh->prepare($sql);
    $stmt->execute();

    $arrResult['rsStat'] = true;
    $arrResult['rsKeyword'] = array();
    while($arrQue =  $stmt->fetch(PDO::FETCH_ASSOC)) {
//        $arrDate = explode(",", $arrQue['dd']);
//        $arrDate[1] = (int)$arrDate[1] - 1 ;
        array_push($arrResult['rsKeyword'], array(
            "dd"=>$arrQue['dd'],
            "cnt"=>$arrQue['cnt']
        ));
    }
} catch (PDOException $ex) {
    $arrResult['rsStat'] = false;
    $arrResult['rsKeyword'] = $ex->getMessage();
} catch (Exception $exc) {
    echo $exc->getMessage();
}

unset($dbh);

header("Content-type: application/json; charset=utf-8");

echo json_encode($arrResult);
