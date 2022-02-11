<?php
session_start();

if((isset($_SESSION['date'])) && (isset($_GET['type'])) && (isset($_GET['filename']))){
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
      header("Location:input_list.php");
      exit();
   }

   $_SESSION['date']=$date;
   $filename = $_GET['filename'];
   header("Location:$filename");
   exit();
}
else{
   echo "エラーが発生したためホームページに戻ります。<br>";
   echo "<a href='input_list.php'>ホームページに戻る</a>";
}
?>