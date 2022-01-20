<?php
session_start();

if(isset($_SESSION['login_user_id'])){
   $login_user_id = $_SESSION['login_user_id'];
}
else{
   echo "値がセットされていません";
}

//dateのセッションをチェック
if(isset($_SESSION['date'])){
   //今のところ特になし
}
else{
   //なし
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta chaset="UTF-8">
<link rel="stylesheet" href="ranking.css"></style>
</head>

<body>
   <main>
   <h1>ユーザー作業時間閲覧</h1>
   <?php require("header.php");?>
   <!--2022/01/11 遷移先変更-->
   <a href="session_delete.php?page_no=1">ホームページに戻る</a><br>
   <h3>ランキング</h3>

   <!--ランキングテーブル作成-->
   <table>
      <tr>
         <th>順位</th><th>ユーザ名</th><th>作業合計時間(分)</th>
      </tr>

   <?php
   $rank = 1; //順位
   $month = (new DateTime('2021-11-01'))->format('m');
   $year = (new DateTime('2021-11-01'))->format('Y');
   //ここでデータベース接続
   $con = mysqli_connect('localhost','root','');
   mysqli_select_db($con, "g4");
   //ユーザごとの今月の作業時間合計を求める(今回は11月だけ)
   //今回はis_publicは0に設定(本来は1に設定)
   $sql ="SELECT
            users.user_id AS user_id, DATE(work.start_time) AS st, YEAR(work.start_time) AS year,
            MONTH(work.start_time) AS month, DAY(work.start_time) AS day, SUM(work.working_minutes) AS sum
         FROM
            users
         INNER JOIN
            work
         ON
            users.user_id=work.user_id
         WHERE
            (MONTH(work.start_time)='$month') AND
            (YEAR(work.start_time)='$year') AND
            (users.is_public=0) AND
            (users.is_deleted=0) AND
            (work.is_deleted=0) AND
            (users.user_id!='$login_user_id')
         GROUP BY
            users.user_id
         ORDER BY
            SUM(work.working_minutes) DESC";

   $result = mysqli_query($con, $sql);
   while($id_list = mysqli_fetch_array($result)){
      $element_id = $id_list['user_id'];
      $work_time = $id_list['sum'];
      echo "<tr><td>$rank</td>";
      echo "<td><a href='anotherUser_worktime.php?login_user_id=$element_id'>$element_id</a></td>";
      echo "<td>$work_time</td></tr>";
      $rank++; // 順位を下げる
   }
   mysqli_close($con);
   ?>
   </table>
   </main>
</body>
</html>