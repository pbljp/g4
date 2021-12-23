<?php
session_start();

//ログインユーザーのIDを受け取り
if(isset($_SESSION['login_user_id'])){
   //値がセットされている場合なにも行わない
}
else{
   exit();//強制終了
}

//GETで閲覧対象のユーザIDを取得
$another_user_id = $_GET['login_user_id'];//他ユーザーのIDを代入
$date=$_SESSION['date'];
$date = (new DateTimeImmutable($date)) -> modify('first day of this month')->format('Y-m-d');
$date = (new DateTimeImmutable($date))->modify("-1 months")->format('Y-m-d');
$_SESSION['date']=$date;
header("Location:anotherUser_worktime.php?login_user_id=$another_user_id");
exit();
?>