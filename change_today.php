<?php
//受け渡す日付をアクセスした月に戻す
session_start();
$today = (new DateTime())->format('Y-m-d');
$_SESSION['date'] = $today;
header("Location: ranking.php");
exit();
?>