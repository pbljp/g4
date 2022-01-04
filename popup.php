<?php

session_start();

$login_user_id = $_SESSION['login_user_id'];

$max_no = 6;//土曜日の番号
$min_no = 0;//日曜日の番号

$today = date('Y-m-d');
$before_weekday = (new DateTimeImmutable($today)) -> modify('-7 days') -> format('Y-m-d');
$day_no = date('w', strtotime($today)); //今日の曜日番号
$minus = $day_no - $min_no; //今日の曜日から日曜日の番号の差分
$plus = $max_no - $day_no; //今日の曜日から土曜日の番号の差分
$sunday = (new DateTimeImmutable($before_weekday)) -> modify("-$minus days") -> format('Y-m-d');
$saturday = (new DateTimeImmutable($before_weekday)) -> modify("+$plus days") -> format('Y-m-d');

$saturday = json_encode($saturday);
$sunday = json_encode($sunday);

//データベース接続
$con_mean = mysqli_connect('ホスト名', 'ユーザ名', '');
mysqli_select_db($con_mean, "データベース名");
mysqli_set_charset($con_mean, "utf8");
$sql="SELECT
         SUM(working_minutes) as sum
      FROM
         work
      WHERE
         (user_id='$login_user_id') AND
         (start_time BETWEEN '$sunday' AND '$saturday')";
$sum_time = mysqli_query($con_mean, $sql);
foreach($sum_time as $mean){
   $mean_time = $mean['sum'];
   $mean_time /= 7;
   $mean_time = intval($mean_time);
}

$mean_time = json_encode($mean_time);
mysqli_close($con_mean);

//ポップアップ内容
$alert = "<script type='text/javascript'>
            const saturday = $saturday;
            const sunday = $sunday;
            const mean_time = $mean_time;
            alert(sunday + ' ～ ' + saturday + 'の平均作業時間は' + mean_time + '分です');
         </script>";
echo $alert;
?>