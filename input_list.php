<?php
session_start();
require('library.php');

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    header('Location: login.php');
    exit();
}

if(!(isset($_SESSION['date']))){
    $today = (new DateTime())->format('Y-m-d');
    $month_endday = (new DateTimeImmutable($today))->modify('last day of')->format('y-m-d');//今月の最後の日を格納
    $month_firstday = (new DateTimeImmutable($today))->modify('first day of')->format('y-m-d');//今月の最初の日を格納    
    $_SESSION['date']=$todate;
}
else{
    $date = $_SESSION['date'];
    $month_endday = (new DateTimeImmutable($date))->modify('last day of')->format('y-m-d');//最後の日を格納
    $month_firstday = (new DateTimeImmutable($date))->modify('first day of')->format('y-m-d');//最初の日を格納    
}

if( isset($_POST['jump'])){
    $date = $_POST['date'];
    $month_endday = (new DateTimeImmutable($date))->modify('last day of')->format('y-m-d');//今月の最後の日を格納
    $month_firstday = (new DateTimeImmutable($date))->modify('first day of')->format('y-m-d');//今月の最初の日を格納    
}


$month_e = strtotime($month_endday);

$month = (new DateTime($date))->format('m');
$filename = basename(__FILE__);

$user_id = 'user_example2';

//ジャンルを取り出す
$db = dbconnect();
        $x = $db->prepare('SELECT type_number, type_name FROM types WHERE user_id=? ');
        if (!$x) {
        die($db->error);
        }
        $x->bind_param('s', $user_id);
        $success1 = $x->execute();

        $x->bind_result($type_number, $type_name);
        while ($x->fetch()) {
            $genre[$type_number] = $type_name;
        }
//-------------------------

$db = dbconnect();
$stmt = $db->prepare('SELECT work_id, type_number, start_time, finish_time, working_minutes, comment, motivation from work where (user_id=?) AND (start_time BETWEEN ? AND ?) order by work_id desc');
if (!$stmt) {
    die($db->error);
}

$stmt->bind_param('sss', $user_id, $month_firstday, $month_endday);
$success = $stmt->execute();
if(!$success) {
    die($db->error);
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link href="homepage.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <title>リスト</title>
</head>
<body>
<?php require('header.php') ?>
<div id="whole">
    <div id="head">
    <h1>項目追加</h1>
    </div>
    <div id="content">
    <form action="input_list.php" method="POST">
               <br>
               任意の年月を選択 : <input type="month" name="date">
               <input class="btn" type="submit" name = "jump" value="ページに進む">
            </form>
            <div id = "transition">
               <label id="before">
                  <a href="list_transition.php?type=before&filename=<?php echo $filename ?>"><span>前の月へ</span><span class="material-icons">navigate_before</span></a>
               </label>
               <?php echo $month . '月'; ?>
               <label id="next">
                  <a href="list_transition.php?type=next&filename=<?php echo $filename ?>"><span class="material-icons">navigate_next</span><span>次の月ヘ</span></a>
               </label>
            </div>
    <?php
    $i=0;
    $stmt->bind_result($work_id, $type_number, $start_time, $finish_time, $working_minutes, $comment, $motivation);
    while ($stmt->fetch()):
        $i++;
    ?>
    <div class="msg">
        <p>作業番号<?php echo h($i); ?></p>
        <p>
        ジャンル : <?php echo h($genre[$type_number]); ?> <br>
        日時 : <?php echo h($start_time); ?> ~ <?php echo h($finish_time); ?><br>
        作業時間 : <?php echo h($working_minutes); ?> 分<br>
        モチベーション : <?php echo h($motivation); ?>    
        [<a href="update.php?id=<?php echo h($work_id); ?>">変更</a>]
        [<a href="delete.php?id=<?php echo h($work_id); ?>" style="color: #F33;">削除</a>]
        </p>
        <hr>
    </div>
    <?php endwhile; ?>
    </div>
</div>
    
</body>
</html>

