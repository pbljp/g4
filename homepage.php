<?php
session_start();
require("to_login.php");
require("get_weekWorktime.php");
$today = (new DateTime())->format('Y-m-d');
$this_month_endday = (new DateTimeImmutable($today))->modify('last day of')->format('d');//今月の最後の日を格納

if(!(isset($_SESSION['date']))){
   $today = new DateTime();
   $date = $today->format('Y-m-d');
   $_SESSION['date']=$date;
   //ポップアップを表示
   require("popup.php");
}
else{
   $date = $_SESSION['date'];
}


//1228 仮処理追加
if($_POST['jump']){
   $date = $_POST['date'];
}
//2022/02/01変更
$user_id = $_SESSION['user_id'];

$month = (new DateTime($date))->format('m');
$year = (new DateTime($date))->format('Y');
$date_title = (new DateTime($date))->format('Y-m');
$month = intval($month);
$year = intval($year);
$filename = basename(__FILE__);

require("get_type_names.php");
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8"/>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

<link href="style.css" rel="stylesheet">
<link href="homepage.css" rel="stylesheet">
<script type="text/javascript" src="http://zeptojs.com/zepto.min.js"></script>

<?php
function element($user_id,$end_date, $num){

   global $month;
   global $year;
   #データベース接続

   require("connect_g4_db.php");
   $sql="SELECT
            type_number, DATE(start_time),
            MONTH(start_time) AS month,
            SUM(working_minutes)
         FROM
            work
         WHERE
            (user_id = ? )AND
            (MONTH(start_time)= ?) AND
            (YEAR(start_time)= ?) AND
            (is_deleted = 0)
         GROUP BY
            DATE(start_time), type_number
         ORDER BY
            start_time";

   if($stmt = $mysqli->prepare($sql)){
      $stmt->bind_param("sii", $user_id, $month, $year);
      $stmt->execute();
      $stmt->bind_result($type_no, $start_time, $month, $work_time);
   }
   else{
      echo "DB接続失敗";
   }


   $row_no=1; //レコードの行数と対応させる
   $data=[]; //各ジャンルの作業時間を格納するための配列

   while($stmt->fetch()){
      $start_time = intval((new DateTime($start_time))->format('d'));
      while(($type_no==$num)){
         if($start_time == $row_no){
            $work_time=intval($work_time);
            array_push($data, $work_time);
            $row_no++; //次の行へ進む
            break;
         }
         else{
            //該当しない場合(該当日のデータがない場合)は0を配列に格納
            array_push($data, 0);
            $row_no++;
         }
      }
   }

   for($k=$row_no; $k<=$end_date; $k++){
      array_push($data, 0);
   }

   $stmt->close();
   $mysqli->close();
   return $data;
}


$end_date =  (new DateTimeImmutable($date))->modify('last day of')->format('d'); // 2021-03-31
$end_date = intval($end_date);

$j1 = []; #ジャンル1の日付ごとの作業時間を格納
$j2 = [];
$j3 = [];

$j1= element($user_id, $end_date, 1);
$j2= element($user_id, $end_date, 2);
$j3= element($user_id, $end_date, 3);
$date_title =json_encode($date_title);
$junre1=json_encode($j1);
$junre2=json_encode($j2);
$junre3=json_encode($j3);
$j1_name = json_encode($type_names[1]);
$j2_name = json_encode($type_names[2]);
$j3_name = json_encode($type_names[3]);
$date=json_encode($date);
$end_date = json_encode($end_date); //2022/02/12追加
?>

<!--グラフ表示-->
<script src="https://code.highcharts.com/highcharts.js"></script>
<script type="text/javascript">
   const junre1 = <?php echo $junre1; ?>;
   const junre2 = <?php echo $junre2; ?>;
   const junre3 = <?php echo $junre3; ?>;
   const j1_name = <?php echo $j1_name; ?>;
   const j2_name = <?php echo $j2_name; ?>;
   const j3_name = <?php echo $j3_name; ?>;
   const date_title = <?php echo $date_title; ?>;
   const end_date=<?php echo $end_date; ?>; //2022/02/12追加
</script>
<script src="homepage.js"></script>

</head>

<body>
   <?php require("header.php"); ?>
   <main>
      <div id="head">
         <h1>ホームページ</h1>
      </div>
      <!--2022/01/20　ボタンを削除-->
      <!--0128 モチベーション、ランキング遷移ボタン削除-->
      <!--2022/01/26追加-->
      <div id="article">
         <div class="goal">
            <div class="goal_list">
               <h3>今月の作業時間達成状況</h3>
                  <?php
                     require("goal_homepage.php");
                  ?>
                  <p>目標時間：<?php echo $goal_hour.時間.$goal_min.分; ?></p>
                  <p>現在の作業時間：<?php echo $sum_hour.時間.$sum_min.分; ?></p>
                  <p><b>達成率：<?php echo $rate_time; ?>%</b></p>
                  <?php
                     require("goal_effect.php");
                  ?>
            </div>
            <div class="goal_list">
               <h3>1日の平均作業時間</h3>
               <p>先々週：(<?php echo $before_sunday.～.$before_saturday; ?>)</p>
               <p><?php echo $before_mean_hour.時間.$before_mean_min.分;?></p>
               <p>先週：(<?php echo $sunday.～.$saturday; ?>)</p>
               <p><?php echo $mean_hour.時間.$mean_min.分; ?></p>
            </div>
         </div>

         <!--1228追加-->
         <div id ="graph">
            <form action="homepage.php" method="POST">
               <br>
               任意の年月を選択：<input type="month" name="date">
               <input class="btn" type="submit" name = "jump" value="ページに進む">
            </form>
            <!--2022/01/13追加(遷移先変更)-->
            <div id = "transition">
               <label id="before">
                  <a href="month_transition.php?type=before&filename=<?php echo $filename ?>"><span>前の月へ</span><span class="material-icons">navigate_before</span></a>
               </label>
               <label id="next">
                  <a href="month_transition.php?type=next&filename=<?php echo $filename ?>"><span class="material-icons">navigate_next</span><span>次の月ヘ</span></a>
               </label>
            </div>
         <div id="container"></div>
      </div>
   </main>
</body>
</html>