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

$start = strtotime($times);
$end = strtotime($timee);
$sum = ($end - $start) / 60;
echo $sum . '分 <br>';

$picture_name = '';
//-----------------------------------

$db = new mysqli('localhost:8889', 'root', 'root', 'mydb_new');

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