<?php

ini_set('display_errors', true);
error_reporting(E_ALL | E_STRICT);

require_once 'config.php';

if (isset($_GET['tweetTags'])) {
    $arrTweetList = $_GET['tweetTags'];
//    $arrResult['rsStat'] = true;
//    $arrResult['rsTweet'] = $json;
//    var_dump($arrTweetList);
} else {
    $arrResult['rsStat'] = false;
    $arrResult['rsTweet'] = "The parameter has problem.";
}

$intTimeW = (int) filter_input(INPUT_GET, 'timew', FILTER_SANITIZE_NUMBER_INT);
$intKeywordsW = (int) filter_input(INPUT_GET, 'keywordsw', FILTER_SANITIZE_NUMBER_INT);
$intUsersW = (int) filter_input(INPUT_GET, 'usersw', FILTER_SANITIZE_NUMBER_INT);
$intNounsW = (int) filter_input(INPUT_GET, 'nounsw', FILTER_SANITIZE_NUMBER_INT);

if (!$intTimeW || !$intKeywordsW || !$intUsersW || !$intNounsW) {
    $arrResult['rsStat'] = false;
    $arrResult['rsTweet'] = "The parameter has problem.";
}

try {
    $dbh = new PDO("mysql:host=$hostname;dbname=$database;charset=utf8", $dbuser, $dbpass);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//
//    $sql_write = 'INSERT INTO `HKALLzh_history`(`historyID`, `userID`, `applied_at`, `time`, `time_w`, '
//                . '`keywords`, `keywords_w`, `users`, `users_w`, `nouns`, `nouns_w`, `tweetsId`'
//                . 'VALUES (NULL, :userID, :applied_at, :Latitude, :Longitude)';
//    $history = $dbh->prepare($sql_write);
//    if($history) {
//        $history->bindParam(':userID', $sql_write);
//    }
//    
    $tweetScore = array();
    $tweetTags = array();
    foreach ($arrTweetList as $key => $value) {
        if ($key == "Time") {
            foreach ($value as $v) {
                $sql = "SELECT `id` FROM `HKALLzh_main` WHERE `created_at` LIKE '" . $v . "%'";
                $stmt = $dbh->prepare($sql);
                $stmt->execute();
                while ($arrQue = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    if (array_key_exists($arrQue['id'], $tweetScore)) {
                        $tweetScore[$arrQue['id']] += $intTimeW;
                        $tweetTags[$arrQue['id']] .= "/t|" . $v;
                    } else {
                        $tweetScore[$arrQue['id']] = $intTimeW;
                        $tweetTags[$arrQue['id']] = "t|" . $v;
                    }
                }
            }
        }
        if ($key == "Keywords") {
            foreach ($value as $v) {
                $sql = "SELECT `id` FROM `HKALLzh_main` WHERE `text` LIKE '%" . $v . "%'";
                $stmt = $dbh->prepare($sql);
                $stmt->execute();
                while ($arrQue = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    if (array_key_exists($arrQue['id'], $tweetScore)) {
                        $tweetScore[$arrQue['id']] += $intKeywordsW;
                        $tweetTags[$arrQue['id']] .= "/k|" . $v;
                    } else {
                        $tweetScore[$arrQue['id']] = $intKeywordsW;
                        $tweetTags[$arrQue['id']] = "k|" . $v;
                    }
                }
            }
        }
        if ($key == "Users") {
            foreach ($value as $v) {
                $sql = "SELECT `id` FROM `HKALLzh_main` WHERE `from_user_name` LIKE '" . $v . "'";
                $stmt = $dbh->prepare($sql);
                $stmt->execute();
                while ($arrQue = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    if (array_key_exists($arrQue['id'], $tweetScore)) {
                        $tweetScore[$arrQue['id']] += $intUsersW;
                        $tweetTags[$arrQue['id']] .= "/u|" . $v;
                    } else {
                        $tweetScore[$arrQue['id']] = $intUsersW;
                        $tweetTags[$arrQue['id']] = "u|" . $v;
                    }
                }
            }
        }
        if ($key == "Nouns") {
            foreach ($value as $v) {
                $sql = "SELECT `id` FROM `HKALLzh_main` WHERE `text` LIKE '%" . $v . "%'";
                $stmt = $dbh->prepare($sql);
                $stmt->execute();
                while ($arrQue = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    if (array_key_exists($arrQue['id'], $tweetScore)) {
                        $tweetScore[$arrQue['id']] += $intNounsW;
                        $tweetTags[$arrQue['id']] .= "/n|" . $v;
                    } else {
                        $tweetScore[$arrQue['id']] = $intNounsW;
                        $tweetTags[$arrQue['id']] = "n|" . $v;
                    }
                }
            }
        }
    }
    arsort($tweetScore);
    
    $arrResult['rsStat'] = true;
    $arrResult['rsTweet'] = array();
    $i = 0;
//    $onethouTweets == $arrResult['rsTweet'];
    foreach ($tweetScore as $k => $v) {
        if ($i < 1000) {
            $arrResult['rsTweet'][$k]["tags"] = $tweetTags[$k];
        }
        $i += 1;
    }

    $sql = "SELECT `id`, `from_user_name`, `created_at`, `text`, `retweet_count` 
            FROM `HKALLzh_main` 
            WHERE `id` IN (" . implode(',', array_keys($arrResult['rsTweet'])) . ")";

    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    while ($arrQue = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $arrResult['rsTweet'][$arrQue["id"]]["from_user_name"] = $arrQue['from_user_name'];
        $arrResult['rsTweet'][$arrQue["id"]]["created_at"] = $arrQue['created_at'];
        $arrResult['rsTweet'][$arrQue["id"]]["text"] = $arrQue['text'];
        $arrResult['rsTweet'][$arrQue["id"]]["retweet_cnt"] = $arrQue['retweet_count'];
    }
        
} catch (PDOException $ex) {
    $arrResult['rsStat'] = false;
    $arrResult['rsTweet'] = $ex->getMessage();
} catch (Exception $exc) {
    echo $exc->getMessage();
}

unset($dbh);

header("Content-type: application/json; charset=utf-8");

echo json_encode($arrResult);
