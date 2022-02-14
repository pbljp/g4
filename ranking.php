<?php
session_start();

require("to_login.php");
$today = (new DateTime())->format('Y-m-d');
$filename = basename(__FILE__);

if(isset($_SESSION['date'])){
   $date = $_SESSION['date'];
}
else{
   echo "エラーが発生";
}

if($_POST['jump']){
   $date = $_POST['date'];
}

$rank = 1; //順位
$month = (new DateTime($date))->format('m');
$year = (new DateTime($date))->format('Y');
$title_month = intval($month);
$user_id = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta chaset="UTF-8">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
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
   <div id="content">
   <a href="default_date.php?filename=homepage.php">ホームページに戻る</a><br>

   <h3 align="center"><?php echo $year.年.$title_month.月; ?></h3>
   <form action="ranking.php" method="POST">
      任意の年月を選択：<input type="month" name="date">
      <input class="btn" type="submit" name = "jump" value="ページに進む">
   </form>
   <div id = "transition">
      <label id="before">
         <a href="month_transition.php?type=before&filename=<?php echo $filename ?>"><span>前の月へ</span><span class="material-icons">navigate_before</span></a>
      </label>
      <label id="next">
         <a href="month_transition.php?type=next&filename=<?php echo $filename ?>"><span class="material-icons">navigate_next</span><span>次の月ヘ</span></a>
      </label>
   </div>
   <!--ランキングテーブル作成-->
   <table>
      <tr>
         <th>順位</th><th>ユーザ名</th><th>合計作業時間(分)</th>
      </tr>
      </div>
   <?php
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
            (work.is_deleted=0)
         GROUP BY
            users.user_id
         ORDER BY
            SUM(work.working_minutes) DESC";

   if($stmt = $mysqli->prepare($sql)){
      $stmt->bind_param("ii", $month, $year);
      $stmt->execute();
      $stmt->bind_result($element_id, $work_time);
   }
   else{
      echo "DB接続失敗";
   }

   while($stmt->fetch()){ //20220203修正
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