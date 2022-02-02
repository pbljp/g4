<?php
session_start();
require('library.php');

//仮ID
$user_id = 'user_example2';

$type_name = filter_input(INPUT_POST, 'genre', FILTER_SANITIZE_SPECIAL_CHARS);
$date = $_POST['date'];
$times = $date . ' ' . $_POST['times'];
$timee = $date . ' ' . $_POST['timee'];
$comment = filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_SPECIAL_CHARS);

echo $type_name . '<br>';
echo $date . '<br>';
echo $times . '<br>';
echo $timee . '<br>';
echo $comment . '<br>';


//type_numberの取得、オールでいいのか？
$db = dbconnect();
$x = $db->prepare('SELECT type_number FROM types WHERE user_id=? AND type_name=?');
if (!$x) {
    die($db->error);
}
$x->bind_param('ss', $user_id, $type_name);
$success1 = $x->execute();

$x->bind_result($type_number);
$x->fetch();
echo 'type_number=' . $type_number . '<br>';
//-----------------------------------

//時間の計算
$start = strtotime($times);
$end = strtotime($timee);
$sum = ($end - $start) / 60;
echo $sum . '分 <br>';
//-------------------------

//時間の重複確認 *データ数が増えるにつれ時間が長くなるかもー＞月ごとの指定を行う予定
function isTimeDuplication($startTime1, $endTime1, $startTime2, $endTime2) {
    return ($startTime1 < $endTime2 && $startTime2 < $endTime1);
}

$db = dbconnect();
$stmt1 = $db->prepare('select start_time, finish_time  from work where user_id=? order by work_id desc');
if (!$stmt1) {
    die($db->error);
}

$stmt1->bind_param('s', $user_id);
$success = $stmt1->execute();
if(!$success) {
    die($db->error);
}

$stmt1->bind_result($sTime, $eTime);
while ($stmt1->fetch()) {
    $startTime = strtotime($sTime);
    $endTime = strtotime($eTime);

    if (isTimeDuplication($startTime, $endTime, $start, $end) == 1){
        echo '時間が重複しています <br>';
        echo '<a href="">ホームに戻る</a><br>';
        echo '<a href="input.html">&laquo;&nbsp;再入力</a>';
        exit;
    }
}

$picture_name = '';
//-----------------------------------

$db = dbconnect();
$stmt = $db->prepare('insert into work(user_id, type_number, start_time, finish_time, working_minutes, comment, picture_name)  values(?, ?, ?, ?, ?, ?, ?)');
if(!$stmt):
    die($db->error);
endif;
$stmt->bind_param('sississ', $user_id , $type_number, $times, $timee, $sum, $comment, $picture_name);
$ret = $stmt->execute();

if($ret):
    echo '登録されました  <br>';
    echo '<a href="">ホームに戻る</a><br>';
    echo '<a href="input.html">&laquo;&nbsp;続けて入力する</a>';
else:
    echo $db->error;
endif;

?>