<?php
session_start();

require("to_login.php");
$user_id = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta chaset="UTF-8">
<link rel="stylesheet" href="ranking.css"></style>
<link rel="stylesheet" href="style.css"></style>
</head>
<div id="whole">
<body>
   <?php require("header.php"); ?>
   <main>
      <div id="head">
         <h1>ランキング</h1>
      </div>
   <!--2022/01/11 遷移先変更-->
   <a href="default_date.php?filename=homepage.php">ホームページに戻る</a><br>

   <!--ランキングテーブル作成-->
   <table>
      <tr>
         <th>順位</th><th>ユーザ名</th><th>作業合計時間(分)</th>
      </tr>

   <?php
   $rank = 1; //順位
   $month = (new DateTime())->format('m');
   $year = (new DateTime())->format('Y');
   //ここでデータベース接続
   require("connect_g4_db.php");
   //ユーザごとの今月の作業時間合計を求める(今回は11月だけ)
   //今回はis_publicは0に設定(本来は1に設定)
   $sql ="SELECT
            users.user_id AS user_id,
            SUM(work.working_minutes) AS sum
         FROM
            users
         INNER JOIN
            work
         ON
            users.user_id=work.user_id
         WHERE
            (MONTH(work.start_time)= ?) AND
            (YEAR(work.start_time)= ?) AND
            (users.is_public=1) AND
            (users.is_deleted=0) AND
            (work.is_deleted=0) AND
            (users.user_id!= ?)
         GROUP BY
            users.user_id
         ORDER BY
            SUM(work.working_minutes) DESC";

   if($stmt = $mysqli->prepare($sql)){
      $stmt->bind_param("iis", $month, $year, $user_id);
      $stmt->execute();
      $stmt->bind_result($element_id, $work_time);
   }
   else{
      echo "DB接続失敗";
   }

   if($stmt->fetch()){
      echo "<tr><td>$rank</td>";
      echo "<td><a href='anotherUser_worktime.php?user_id=$element_id'>$element_id</a></td>";
      echo "<td>$work_time</td></tr>";
      $rank++; // 順位を下げる
   }
   $stmt->close();
   $mysqli->close();
   ?>
   </table>
   </main>
   </div>
</body>
</html>