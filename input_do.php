<?php
session_start();
require('library.php');
if (isset($_SESSION['user_id']) ) {
    $user_id = $_SESSION['user_id'];
} else {
    header('Location: login.php');
    exit();
}

//仮ID
// $user_id = 'user_example2';

$type_name = filter_input(INPUT_POST, 'genre', FILTER_SANITIZE_SPECIAL_CHARS);
$date = $_POST['date'];
$times = $date . ' ' . $_POST['times'];
$timee = $date . ' ' . $_POST['timee'];
$motivation = $_POST['motivation'];
$comment = filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_SPECIAL_CHARS);

// echo $type_name . '<br>';
// echo $date . '<br>';
// echo $times . '<br>';
// echo $timee . '<br>';
// echo $comment . '<br>';


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
// echo 'type_number=' . $type_number . '<br>';
//-----------------------------------

//時間の計算
$start = strtotime($times);
$end = strtotime($timee);
$sum = ($end - $start) / 60;
// echo $sum . '分 <br>';
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
        echo '<a href="homepage.php">ホームに戻る</a><br>';
        echo '<a href="input.html">&laquo;&nbsp;再入力</a>';
        exit;
    }
}

$picture_name = '';
//-----------------------------------

$db = dbconnect();
$stmt = $db->prepare('insert into work(user_id, type_number, start_time, finish_time, working_minutes, comment, picture_name, motivation)  values(?, ?, ?, ?, ?, ?, ?, ?)');
if(!$stmt):
    die($db->error);
endif;
$stmt->bind_param('sississi', $user_id , $type_number, $times, $timee, $sum, $comment, $picture_name, $motivation);
$ret = $stmt->execute();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>input_do.php</title>
</head>
<body>

    <div id="whole">
        <div id="head">
            <h1>項目追加</h1>
        </div>
        <div id="content">
        <?php
        if($ret):
            echo '登録されました  <br>';
            echo '<a href="homepage.php">ホームに戻る</a><br>';
            echo '<a href="input.php">&laquo;&nbsp;続けて入力する</a>';
        else:
            echo $db->error;
        endif;
        ?>
        </div>
    </div>
</body>
</html>