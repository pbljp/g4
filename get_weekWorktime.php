<?php
session_start();

$user_id = $_SESSION['user_id'];

$max_no = 6;//土曜日の番号
$min_no = 0;//日曜日の番号

$today = date('Y-m-d');
$before_weekday = (new DateTimeImmutable($today)) -> modify('-7 days') -> format('Y-m-d');
$two_week_before = (new DateTimeImmutable($today)) -> modify('-14days') -> format('Y-m-d');//2週間前の日時
$day_no = date('w', strtotime($today)); //今日の曜日番号
$minus = $day_no - $min_no; //今日の曜日から日曜日の番号の差分
$plus = $max_no - $day_no; //今日の曜日から土曜日の番号の差分

//先週の土日の日付を取得
$sunday = (new DateTimeImmutable($before_weekday)) -> modify("-$minus days") -> format('Y-m-d');//先週の日曜日の日にち
$saturday = (new DateTimeImmutable($before_weekday)) -> modify("+$plus days") -> format('Y-m-d');//先週の土曜日の日にち

//2週間前の土日の日付を取得
$before_sunday = (new DateTimeImmutable($two_week_before)) -> modify("-$minus days") -> format('Y-m-d');//2週間前の日曜 日の日にち
$before_saturday = (new DateTimeImmutable($two_week_before)) -> modify("+$plus days") -> format('Y-m-d');//2週間前の土曜日の日にち

//データベース接続
require("connect_g4_db.php");
$sql="SELECT
         SUM(working_minutes) as sum
      FROM
         work
      WHERE
         (user_id=?) AND
         (date(start_time) BETWEEN ? AND ?) AND
         (is_deleted= 0)";

if(($stmt1 = $mysqli->prepare($sql)) && ($stmt2 = $mysqli->prepare($sql))){
   $stmt1->bind_param("sss", $user_id, $sunday, $saturday);
   $stmt1->execute();
   $stmt1->bind_result($sum_time);
   $stmt1->fetch();
   $stmt1->close();

   $stmt2->bind_param("sss", $user_id, $before_sunday, $before_saturday);
   $stmt2->execute();
   $stmt2->bind_result($before_sum_time);
   $stmt2->fetch();
   $stmt2->close();
}
else{
   echo "DB接続失敗";
}

$mysqli->close();

//先週の平均を算出
$mean_time = intval($sum_time / 7);
$mean_hour = intval($mean_time / 60);
$mean_min = intval($mean_time % 60);

//先々週の平均を算出
$before_mean_time = intval($before_sum_time / 7);
$before_mean_hour = intval($before_mean_time / 60);
$before_mean_min = intval($before_mean_time % 60);

?>