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

$intUID = (int) filter_input(INPUT_GET, 'uID', FILTER_SANITIZE_NUMBER_INT);
$strDatetime = filter_input(INPUT_GET, 'dtime', FILTER_SANITIZE_STRING);
$intTimeW = (int) filter_input(INPUT_GET, 'timew', FILTER_SANITIZE_NUMBER_INT);
$intKeywordsW = (int) filter_input(INPUT_GET, 'keywordsw', FILTER_SANITIZE_NUMBER_INT);
$intUsersW = (int) filter_input(INPUT_GET, 'usersw', FILTER_SANITIZE_NUMBER_INT);
$intNounsW = (int) filter_input(INPUT_GET, 'nounsw', FILTER_SANITIZE_NUMBER_INT);

if (!$intUID || !$strDatetime || !$intTimeW || !$intKeywordsW || !$intUsersW || !$intNounsW) {
    $arrResult['rsStat'] = false;
    $arrResult['rsTweet'] = "The parameter has problem.";
}

try {
    $dbh = new PDO("mysql:host=$hostname;dbname=$database;charset=utf8", $dbuser, $dbpass);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $time_tags = "";
    $keywords_tags = "";
    $users_tags = "";
    $nouns_tags = "";
    $set_tweetsID = "";
    
    $tweetScore = array();
    $tweetTags = array();
    foreach ($arrTweetList as $key => $value) {
        if ($key == "Time") {
            foreach ($value as $v) {
                $time_tags .= $v . "|";
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
                $keywords_tags .= $v . "|";
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
                $users_tags .= $v . "|";
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
                $nouns_tags .= $v . "|";
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
            $set_tweetsID .= strval($k) . "|";
            $i += 1;
        } else {
            break;
        }
    }

//    write history record
    $sql_write = 'INSERT INTO `HKALLzh_history`(`historyID`, `userID`, `applied_at`, `time`, `time_w`, '
            . '`keywords`, `keywords_w`, `users`, `users_w`, `nouns`, `nouns_w`, `set_tweetsID`) '
            . 'VALUES (NULL, :userID, :applied_at, :time, :time_w, '
            . ':keywords, :keywords_w, :users, :users_w, :nouns, :nouns_w, :set_tweetsID)';
    $history = $dbh->prepare($sql_write);
    if ($history) {
        $history->bindParam(':userID', $intUID, \PDO::PARAM_INT);
        $history->bindParam(':applied_at', $strDatetime, \PDO::PARAM_STR);
        $history->bindParam(':time', $time_tags, \PDO::PARAM_STR);
        $history->bindParam(':time_w', $intTimeW, \PDO::PARAM_INT);
        $history->bindParam(':keywords', $keywords_tags, \PDO::PARAM_STR);
        $history->bindParam(':keywords_w', $intKeywordsW, \PDO::PARAM_INT);
        $history->bindParam(':users', $users_tags, \PDO::PARAM_STR);
        $history->bindParam(':users_w', $intUsersW, \PDO::PARAM_INT);
        $history->bindParam(':nouns', $nouns_tags, \PDO::PARAM_STR);
        $history->bindParam(':nouns_w', $intNounsW, \PDO::PARAM_INT);
        $history->bindParam(':set_tweetsID', $set_tweetsID, \PDO::PARAM_STR);
        $history->execute();
    }

//    return tweets to tweets display area
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
