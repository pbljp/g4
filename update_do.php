<?php
require('library.php');

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    header('Location: index.php');
    exit();
}


$work_id = filter_input(INPUT_POST, 'work_id', FILTER_SANITIZE_NUMBER_INT);
$type_name = filter_input(INPUT_POST, 'genre', FILTER_SANITIZE_SPECIAL_CHARS);
$date = $_POST['date'];
$times = $date . ' ' . $_POST['times'];
$timee = $date . ' ' . $_POST['timee'];
$motivation = $_POST['motivation'];
$comment = filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_SPECIAL_CHARS);

if( !isset($date) || !isset($times) || !isset($timee) || !isset($motivation) ) {
    echo '入力されていない項目があります <br>';
    echo '<a href="homepage.php">ホームに戻る</a><br>';
    echo '<a href="input.html">&laquo;&nbsp;再入力</a>';
    exit;
}

$db = dbconnect();
$x = $db->prepare('SELECT type_number FROM types WHERE user_id=? AND type_name=?');
if (!$x) {
    die($db->error);
}
$x->bind_param('ss', $user_id, $type_name);
$success1 = $x->execute();

$x->bind_result($type_number);
$x->fetch();

$start = strtotime($times);
$end = strtotime($timee);
$sum = ($end - $start) / 60;

//時間チェック
if ($start >= $end){
    echo '時空間の歪みを感じます <br>';
    echo '<a href="homepage.php">ホームに戻る</a><br>';
    echo '<a href="input.html">&laquo;&nbsp;再入力</a>';
    exit;
}

//時間の重複確認 *データ数が増えるにつれ時間が長くなるかもー＞月ごとの指定を行う予定
function isTimeDuplication($startTime1, $endTime1, $startTime2, $endTime2) {
    return ($startTime1 < $endTime2 && $startTime2 < $endTime1);
}

$db = dbconnect();
$stmt1 = $db->prepare('select start_time, finish_time  from work where user_id=? AND work_id!=? order by work_id desc');
if (!$stmt1) {
    die($db->error);
}

$stmt1->bind_param('si', $user_id, $work_id);
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


$db = dbconnect();
$stmt = $db->prepare('update work set type_number=?, start_time=?, finish_time=?, working_minutes=?, comment=?, motivation=? where work_id=?');
if (!$stmt){
    die($db->error);
}
$stmt->bind_param('issisii', $type_number, $times, $timee, $sum, $comment, $motivation, $work_id);
$success = $stmt->execute();
if (!$success) {
    die($db->error);
}

header('Location: input_list.php');
?>
