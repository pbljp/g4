<?php
session_start();

if((isset($_SESSION['date'])) && (isset($_GET['type'])) && (isset($_GET['user_id']))){
   $user_id=$_GET['user_id'];
   $date=$_SESSION['date'];
   $type = $_GET['type']; //先月から来月かを示す
   $date = (new DateTimeImmutable($date)) -> modify('first day of this month')->format('Y-m-d');
   if($type == "before"){
      //先月のページに移動する場合
      $date = (new DateTimeImmutable($date))->modify("-1 months")->format('Y-m-d');
   }
   else if($type == "next"){
      //来月のページに移動する場合
      $date = (new DateTimeImmutable($date))->modify("+1 months")->format('Y-m-d');
   }
   else{
      //before, next以外が入力されたとき
      header("Location:homepage.php");
      exit();
   }

   $_SESSION['date']=$date;
   header("Location:anotherUser_worktime.php?user_id=$user_id");
   exit();
}
else{
   echo "エラーが発生したためホームページに戻ります。<br>";
   echo "<a href='homepage.php'>ホームページに戻る</a>";
}
?>