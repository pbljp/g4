<?php
//目標作業時間に応じて画面出力を変えるページ

//今日の年月を取得
$this_month = intval((new DateTime())->format('m'));
$this_year = intval((new DateTime())->format('Y'));

//データベース接続
require("connect_g4_db.php");
$sql_goal ="SELECT
               goal_minutes
            FROM
               users
            WHERE
               user_id = ?";

$sql_sum="SELECT
            SUM(working_minutes)
         FROM
            work
         WHERE
            (user_id = ? )AND
            (MONTH(start_time)= ?) AND
            (YEAR(start_time)= ?) AND
            (is_deleted=0)";

if(($stmt_goal = $mysqli->prepare($sql_goal)) && ($stmt_sum = $mysqli->prepare($sql_sum))){
   //目標時間を取得
   $stmt_goal->bind_param("i", $user_id);
   $stmt_goal->execute();
   $stmt_goal->bind_result($goal_minutes);
   $stmt_goal->fetch();
   $goal_minutes *= $this_month_endday; //今月の目標時間を算出
   $stmt_goal->close();

   //月の合計値を取得
   $stmt_sum->bind_param("sii", $user_id, $this_month, $this_year);
   $stmt_sum->execute();
   $stmt_sum->bind_result($sum_minutes);
   $stmt_sum->fetch();
   $stmt_sum->close();
   //デバッグ用$sum_minutes = 6020;

   if($sum_minutes == null){
      $sum_minutes=0;
   }



   if(!($goal_minutes == 0)){//目標時間が0の場合
      $rate_time = $sum_minutes / $goal_minutes;
      $rate_time = intval($rate_time*100);
   }
   else{
      $rate_time = 0;
   }

   //分→時間と分に分割
   $goal_hour = intval($goal_minutes / 60);
   $goal_min = intval($goal_minutes % 60);
   $sum_hour = intval($sum_minutes / 60);
   $sum_min = intval($sum_minutes % 60);
}
else{
   echo "DB接続失敗";
}


?>