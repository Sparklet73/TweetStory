<?php
//
//ini_set('display_errors', true);
//error_reporting(E_ALL | E_STRICT);

require_once 'config.php';

if (isset($_GET['tJO'])) {
    $arrTagsGroup = $_GET['tJO'];
//    var_dump($arrTagsGroup);
} else {
    $arrResult['rsStat'] = false;
    $arrResult['rsRes'] = "The parameter has problem.";
}

$intUID = (int) filter_input(INPUT_GET, 'uID', FILTER_SANITIZE_NUMBER_INT);

if (!$intUID) {
    $arrResult['rsStat'] = false;
    $arrResult['rsRes'] = "The parameter has problem.";
}

try {
    $dbh = new PDO("mysql:host=$hostname;dbname=$database;charset=utf8", $dbuser, $dbpass);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $strSql = array();
    foreach ($arrTagsGroup["tag"] as $tag) {
        $sq = '`HKALLzh_materials`.`tags` LIKE "%' . $tag . '%"';
        array_push($strSql, $sq);
    }
    
    $sql = "SELECT `HKALLzh_main`.`text`
            FROM `HKALLzh_materials`, `HKALLzh_main`
            WHERE (" . implode(" OR ", $strSql) .
            ") and `HKALLzh_materials`.`tweetID`=`HKALLzh_main`.`id` 
            and `HKALLzh_materials`.`userID` = " . $intUID;
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
