<?php
session_start();
require('library.php');

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    header('Location: login.php');
    exit();
}

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
$stmt = $db->prepare('select work_id, type_number, start_time, finish_time, working_minutes, comment, motivation from work where user_id=? order by work_id desc');
if (!$stmt) {
    die($db->error);
}

$stmt->bind_param('s', $user_id);
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
    <title>Document</title>
</head>
<body>
<?php require('header.php') ?>
<div id="whole">
    <div id="head">
    <h1>項目追加</h1>
    </div>
    <div id="content">
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

