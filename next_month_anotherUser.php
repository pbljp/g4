<?php
session_start();

//ログインユーザのIDを取得
if(isset($_SESSION['login_user_id'])){
   //値がセットされていればなにも行わない
}
else{
   exit();//強制終了
}

//GETで閲覧対象ユーザIDを取得
$another_user_id = $_GET['login_user_id'];
$date=$_SESSION['date'];
$date = (new DateTimeImmutable($date))->modify('first day of this month')->format('Y-m-d');
$date = (new DateTimeImmutable($date))->modify('+1 months')->format('Y-m-d');
$_SESSION['date']=$date;
header("Location:anotherUser_worktime.php?login_user_id=$another_user_id");
exit();
?>