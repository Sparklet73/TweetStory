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
$strOrderby = filter_input(INPUT_GET, 'orderby', FILTER_SANITIZE_STRING);
$strDatetime = filter_input(INPUT_GET, 'dtime', FILTER_SANITIZE_STRING);
$intTimeW = (int) filter_input(INPUT_GET, 'timew', FILTER_SANITIZE_NUMBER_INT);
$intKeywordsW = (int) filter_input(INPUT_GET, 'keywordsw', FILTER_SANITIZE_NUMBER_INT);
$intUsersW = (int) filter_input(INPUT_GET, 'usersw', FILTER_SANITIZE_NUMBER_INT);
$intNounsW = (int) filter_input(INPUT_GET, 'nounsw', FILTER_SANITIZE_NUMBER_INT);

if (!$intUID || !$strOrderby || !$strDatetime || !$intTimeW || !$intKeywordsW || !$intUsersW || !$intNounsW) {
    $arrResult['rsStat'] = false;
    $arrResult['rsTweet'] = "The parameter has problem.";
}

try {
    $dbh = new PDO("mysql:host=$hostname;dbname=$database;charset=utf8", $dbuser, $dbpass);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    if ($strOrderby == "weights") {
        $time_tags = array();
        $keywords_tags = array();
        $users_tags = array();
        $nouns_tags = array();

        $tweetScore = array();
        $tweetTags = array();
        foreach ($arrTweetList as $key => $value) {
            if ($key == "Time") {
                foreach ($value as $v) {
                    array_push($time_tags, $v);
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
                    array_push($keywords_tags, $v);
                    $sql = "SELECT `id` FROM `HKALLzh_main` WHERE `text` LIKE '%" . $v . "%'";
                    $stmt = $dbh->prepare($sql);
                    if (!$stmt->execute()) {
                        throw new Exception("nothing");
                    }
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
                    array_push($users_tags, $v);
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
                    array_push($nouns_tags, $v);
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
        foreach ($tweetScore as $k => $v) {
            if ($i < 1000) {
                $arrResult['rsTweet'][$k]["tags"] = $tweetTags[$k];
//            $set_tweetsID .= strval($k) . "|";
                $i += 1;
            } else {
                break;
            }
        }

//    write into collections table
        $sql_w_tag = "INSERT INTO `HKALLzh_collections`(`colID`,`userID`,`tweetID`,`tag`) VALUES (NULL, :uID, :tweetID, :tag)";
        $st_w_tag = $dbh->prepare($sql_w_tag);
        if ($st_w_tag) {
            $st_w_tag->bindParam(':uID', $intUID, \PDO::PARAM_INT);
            $st_w_tag->bindParam(':tweetID', $intTID, \PDO::PARAM_INT);
            $st_w_tag->bindParam(':tag', $wtag, \PDO::PARAM_STR);
            foreach ($arrResult['rsTweet'] as $k => $v) {
                $intTID = $k;
                $tl = explode("/", $v["tags"]);
                foreach ($tl as $w) {
                    $t = explode("|", $w);
                    $wtag = $t[1];
                    $st_w_tag->execute();
                }
            }
        }
        $strTimeTag = implode("|", $time_tags);
        $strKeywordsTag = implode("|", $keywords_tags);
        $strUsersTag = implode("|", $users_tags);
        $strNounsTag = implode("|", $nouns_tags);

//    write history record
        $sql_write = 'INSERT INTO `HKALLzh_history`(`historyID`, `userID`, `applied_at`, `time`, `time_w`, '
                . '`keywords`, `keywords_w`, `users`, `users_w`, `nouns`, `nouns_w`) '
                . 'VALUES (NULL, :userID, :applied_at, :time, :time_w, '
                . ':keywords, :keywords_w, :users, :users_w, :nouns, :nouns_w)';
        $history = $dbh->prepare($sql_write);
        if ($history) {
            $history->bindParam(':userID', $intUID, \PDO::PARAM_INT);
            $history->bindParam(':applied_at', $strDatetime, \PDO::PARAM_STR);
            $history->bindParam(':time', $strTimeTag, \PDO::PARAM_STR);
            $history->bindParam(':time_w', $intTimeW, \PDO::PARAM_INT);
            $history->bindParam(':keywords', $strKeywordsTag, \PDO::PARAM_STR);
            $history->bindParam(':keywords_w', $intKeywordsW, \PDO::PARAM_INT);
            $history->bindParam(':users', $strUsersTag, \PDO::PARAM_STR);
            $history->bindParam(':users_w', $intUsersW, \PDO::PARAM_INT);
            $history->bindParam(':nouns', $strNounsTag, \PDO::PARAM_STR);
            $history->bindParam(':nouns_w', $intNounsW, \PDO::PARAM_INT);
//        $history->bindParam(':set_tweetsID', $set_tweetsID, \PDO::PARAM_STR);
            $history->execute();
//        紀錄最後一筆歷史的id，以方便新增下一筆。
            $lastHistoryId = $dbh->lastInsertId();
        }

//    return tweets to tweets display area
        $sql = "SELECT `id`, `from_user_name`, `from_user_description`, DATE_FORMAT(`created_at`,'%Y-%m-%d %H:%i') created_at, `text`, `retweet_count` 
            FROM `HKALLzh_main` 
            WHERE `id` IN (" . implode(',', array_keys($arrResult['rsTweet'])) . ")";

        $stmt = $dbh->prepare($sql);
        $stmt->execute();
        while ($arrQue = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $arrResult['rsTweet'][$arrQue["id"]]["from_user_name"] = $arrQue['from_user_name'];
            $arrResult['rsTweet'][$arrQue["id"]]["from_user_description"] = $arrQue['from_user_description'];
            $arrResult['rsTweet'][$arrQue["id"]]["created_at"] = $arrQue['created_at'];
            $arrResult['rsTweet'][$arrQue["id"]]["text"] = $arrQue['text'];
            $arrResult['rsTweet'][$arrQue["id"]]["retweet_cnt"] = $arrQue['retweet_count'];
        }

        $arrResult['bookmarkID'] = $lastHistoryId;
    } else if ($strOrderby == "rt") {

        $time_tags = array();
        $keywords_tags = array();
        $users_tags = array();
        $nouns_tags = array();

        $tweetRtcnt = array();
        $tweetTags = array();
        foreach ($arrTweetList as $key => $value) {
            if ($key == "Time") {
                foreach ($value as $v) {
                    array_push($time_tags, $v);
                    $sql = "SELECT `id`,`retweet_count` FROM `HKALLzh_main` WHERE `created_at` LIKE '" . $v . "%'";
                    $stmt = $dbh->prepare($sql);
                    $stmt->execute();
                    while ($arrQue = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        if (array_key_exists($arrQue['id'], $tweetRtcnt)) {
                            $tweetTags[$arrQue['id']] .= "/t|" . $v;
                        } else {
                            $tweetRtcnt[$arrQue['id']] = $arrQue['retweet_count'];
                            $tweetTags[$arrQue['id']] = "t|" . $v;
                        }
                    }
                }
            }
            if ($key == "Keywords") {
                foreach ($value as $v) {
                    array_push($keywords_tags, $v);
                    $sql = "SELECT `id`,`retweet_count` FROM `HKALLzh_main` WHERE `text` LIKE '%" . $v . "%'";
                    $stmt = $dbh->prepare($sql);
                    if (!$stmt->execute()) {
                        throw new Exception("nothing");
                    }
                    while ($arrQue = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        if (array_key_exists($arrQue['id'], $tweetRtcnt)) {
                            $tweetTags[$arrQue['id']] .= "/k|" . $v;
                        } else {
                            $tweetRtcnt[$arrQue['id']] = $arrQue['retweet_count'];
                            $tweetTags[$arrQue['id']] = "k|" . $v;
                        }
                    }
                }
            }
            if ($key == "Users") {
                foreach ($value as $v) {
                    array_push($users_tags, $v);
                    $sql = "SELECT `id`,`retweet_count` FROM `HKALLzh_main` WHERE `from_user_name` LIKE '" . $v . "'";
                    $stmt = $dbh->prepare($sql);
                    $stmt->execute();
                    while ($arrQue = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        if (array_key_exists($arrQue['id'], $tweetRtcnt)) {
                            $tweetTags[$arrQue['id']] .= "/u|" . $v;
                        } else {
                            $tweetRtcnt[$arrQue['id']] = $arrQue['retweet_count'];
                            $tweetTags[$arrQue['id']] = "u|" . $v;
                        }
                    }
                }
            }
            if ($key == "Nouns") {
                foreach ($value as $v) {
                    array_push($nouns_tags, $v);
                    $sql = "SELECT `id`,`retweet_count` FROM `HKALLzh_main` WHERE `text` LIKE '%" . $v . "%'";
                    $stmt = $dbh->prepare($sql);
                    $stmt->execute();
                    while ($arrQue = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        if (array_key_exists($arrQue['id'], $tweetRtcnt)) {
                            $tweetTags[$arrQue['id']] .= "/n|" . $v;
                        } else {
                            $tweetRtcnt[$arrQue['id']] = $arrQue['retweet_count'];
                            $tweetTags[$arrQue['id']] = "n|" . $v;
                        }
                    }
                }
            }
            arsort($tweetRtcnt);

            $arrResult['rsStat'] = true;
            $arrResult['rsTweet'] = array();
            $i = 0;
            foreach ($tweetRtcnt as $k => $v) {
                if ($i < 3000) {
                    $arrResult['rsTweet'][$k]["tags"] = $tweetTags[$k];
                    $i += 1;
                } else {
                    break;
                }
            }
            //    return tweets to tweets display area
            $sql = "SELECT `id`, `from_user_name`, `from_user_description`, DATE_FORMAT(`created_at`,'%Y-%m-%d %H:%i') created_at, `text`, `retweet_count` 
            FROM `HKALLzh_main` 
            WHERE `id` IN (" . implode(',', array_keys($arrResult['rsTweet'])) . ")";

            $stmt = $dbh->prepare($sql);
            $stmt->execute();
            while ($arrQue = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $arrResult['rsTweet'][$arrQue["id"]]["from_user_name"] = $arrQue['from_user_name'];
                $arrResult['rsTweet'][$arrQue["id"]]["from_user_description"] = $arrQue['from_user_description'];
                $arrResult['rsTweet'][$arrQue["id"]]["created_at"] = $arrQue['created_at'];
                $arrResult['rsTweet'][$arrQue["id"]]["text"] = $arrQue['text'];
                $arrResult['rsTweet'][$arrQue["id"]]["retweet_cnt"] = $arrQue['retweet_count'];
            }
        }
    } else if ("$strOrderby" == "weightsNotHistory") {
        $time_tags = array();
        $keywords_tags = array();
        $users_tags = array();
        $nouns_tags = array();

        $tweetScore = array();
        $tweetTags = array();
        foreach ($arrTweetList as $key => $value) {
            if ($key == "Time") {
                foreach ($value as $v) {
                    array_push($time_tags, $v);
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
                    array_push($keywords_tags, $v);
                    $sql = "SELECT `id` FROM `HKALLzh_main` WHERE `text` LIKE '%" . $v . "%'";
                    $stmt = $dbh->prepare($sql);
                    if (!$stmt->execute()) {
                        throw new Exception("nothing");
                    }
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
                    array_push($users_tags, $v);
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
                    array_push($nouns_tags, $v);
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
        foreach ($tweetScore as $k => $v) {
            if ($i < 3000) {
                $arrResult['rsTweet'][$k]["tags"] = $tweetTags[$k];
//            $set_tweetsID .= strval($k) . "|";
                $i += 1;
            } else {
                break;
            }
        }

//    return tweets to tweets display area
        $sql = "SELECT `id`, `from_user_name`, `from_user_description`, DATE_FORMAT(`created_at`,'%Y-%m-%d %H:%i') created_at, `text`, `retweet_count` 
            FROM `HKALLzh_main` 
            WHERE `id` IN (" . implode(',', array_keys($arrResult['rsTweet'])) . ")";

        $stmt = $dbh->prepare($sql);
        $stmt->execute();
        while ($arrQue = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $arrResult['rsTweet'][$arrQue["id"]]["from_user_name"] = $arrQue['from_user_name'];
            $arrResult['rsTweet'][$arrQue["id"]]["from_user_description"] = $arrQue['from_user_description'];
            $arrResult['rsTweet'][$arrQue["id"]]["created_at"] = $arrQue['created_at'];
            $arrResult['rsTweet'][$arrQue["id"]]["text"] = $arrQue['text'];
            $arrResult['rsTweet'][$arrQue["id"]]["retweet_cnt"] = $arrQue['retweet_count'];
        }
    } else if ($strOrderby == "time") {
        
        $time_tags = array();
        $keywords_tags = array();
        $users_tags = array();
        $nouns_tags = array();

        $tweetTime = array();
        $tweetTags = array();
        foreach ($arrTweetList as $key => $value) {
            if ($key == "Time") {
                foreach ($value as $v) {
                    array_push($time_tags, $v);
                    $sql = "SELECT `id`, DATE_FORMAT(`created_at`,'%Y-%m-%d %H:%i') created_at FROM `HKALLzh_main` WHERE `created_at` LIKE '" . $v . "%'";
                    $stmt = $dbh->prepare($sql);
                    $stmt->execute();
                    while ($arrQue = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        if (array_key_exists($arrQue['id'], $tweetTime)) {
                            $tweetTags[$arrQue['id']] .= "/t|" . $v;
                        } else {
                            $tweetTime[$arrQue['id']] = $arrQue['created_at'];
                            $tweetTags[$arrQue['id']] = "t|" . $v;
                        }
                    }
                }
            }
            if ($key == "Keywords") {
                foreach ($value as $v) {
                    array_push($keywords_tags, $v);
                    $sql = "SELECT `id`,DATE_FORMAT(`created_at`,'%Y-%m-%d %H:%i') created_at FROM `HKALLzh_main` WHERE `text` LIKE '%" . $v . "%'";
                    $stmt = $dbh->prepare($sql);
                    if (!$stmt->execute()) {
                        throw new Exception("nothing");
                    }
                    while ($arrQue = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        if (array_key_exists($arrQue['id'], $tweetTime)) {
                            $tweetTags[$arrQue['id']] .= "/k|" . $v;
                        } else {
                            $tweetTime[$arrQue['id']] = $arrQue['created_at'];
                            $tweetTags[$arrQue['id']] = "k|" . $v;
                        }
                    }
                }
            }
            if ($key == "Users") {
                foreach ($value as $v) {
                    array_push($users_tags, $v);
                    $sql = "SELECT `id`,DATE_FORMAT(`created_at`,'%Y-%m-%d %H:%i') created_at FROM `HKALLzh_main` WHERE `from_user_name` LIKE '" . $v . "'";
                    $stmt = $dbh->prepare($sql);
                    $stmt->execute();
                    while ($arrQue = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        if (array_key_exists($arrQue['id'], $tweetTime)) {
                            $tweetTags[$arrQue['id']] .= "/u|" . $v;
                        } else {
                            $tweetTime[$arrQue['id']] = $arrQue['created_at'];
                            $tweetTags[$arrQue['id']] = "u|" . $v;
                        }
                    }
                }
            }
            if ($key == "Nouns") {
                foreach ($value as $v) {
                    array_push($nouns_tags, $v);
                    $sql = "SELECT `id`,DATE_FORMAT(`created_at`,'%Y-%m-%d %H:%i') created_at FROM `HKALLzh_main` WHERE `text` LIKE '%" . $v . "%'";
                    $stmt = $dbh->prepare($sql);
                    $stmt->execute();
                    while ($arrQue = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        if (array_key_exists($arrQue['id'], $tweetTime)) {
                            $tweetTags[$arrQue['id']] .= "/n|" . $v;
                        } else {
                            $tweetTime[$arrQue['id']] = $arrQue['created_at'];
                            $tweetTags[$arrQue['id']] = "n|" . $v;
                        }
                    }
                }
            }
            asort($tweetTime);

            $arrResult['rsStat'] = true;
            $arrResult['rsTweet'] = array();
            $i = 0;
            foreach ($tweetTime as $k => $v) {
                if ($i < 3000) {
                    $arrResult['rsTweet'][$k]["tags"] = $tweetTags[$k];
                    $i += 1;
                } else {
                    break;
                }
            }
            //    return tweets to tweets display area
            $sql = "SELECT `id`, `from_user_name`, `from_user_description`, DATE_FORMAT(`created_at`,'%Y-%m-%d %H:%i') created_at, `text`, `retweet_count` 
            FROM `HKALLzh_main` 
            WHERE `id` IN (" . implode(',', array_keys($arrResult['rsTweet'])) . ")";

            $stmt = $dbh->prepare($sql);
            $stmt->execute();
            while ($arrQue = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $arrResult['rsTweet'][$arrQue["id"]]["from_user_name"] = $arrQue['from_user_name'];
                $arrResult['rsTweet'][$arrQue["id"]]["from_user_description"] = $arrQue['from_user_description'];
                $arrResult['rsTweet'][$arrQue["id"]]["created_at"] = $arrQue['created_at'];
                $arrResult['rsTweet'][$arrQue["id"]]["text"] = $arrQue['text'];
                $arrResult['rsTweet'][$arrQue["id"]]["retweet_cnt"] = $arrQue['retweet_count'];
            }
        }
    }
} catch (PDOException $ex) {
    $arrResult['rsStat'] = false;
    $arrResult['rsTweet'] = $ex->getMessage();
} catch (Exception $exc) {
    $arrResult['rsStat'] = false;
    $arrResult['rsTweet'] = $ex->getMessage();
}

unset($dbh);

header("Content-type: application/json; charset=utf-8");

echo json_encode($arrResult);
