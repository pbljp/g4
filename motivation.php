<?php
session_start();

if(isset($_SESSION['date'])){
   $date = $_SESSION['date'];
}
else{
   $today = new DateTime();
   $date = $today->format('Y-m-d');
   $_SESSION['date']=$date;
}


if(!(isset($_SESSION['user_id']))){
   $user_id="user_example2";
   $_SESSION['user_id']=$user_id;
}
else{
   $user_id=$_SESSION['user_id'];
}

//任意の年月からの値を取得
if(isset($_POST['jump'])){
   $date = $_POST['date'];
}

$month = (new DateTime($date))->format('m');
$year = (new DateTime($date))->format('Y');
$date_title = (new DateTime($date))->format('Y-m');
$month = intval($month);
$year = intval($year);
$filename = basename(__FILE__); //自身のファイル名を取得

require("get_type_names.php");
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<link href="motivation.css" rel="stylesheet">
<link href="style.css" rel="stylesheet">
<script type="text/javascript" src="http://zeptojs.com/zepto.min.js"></script>
<script type="text/javascript">
</script>

<?php

function element($user_id,$end_date, $num){

   global $month;
   global $year;
   #データベース接続
   require("connect_g4_db.php");
   $sql="SELECT
            type_number, DATE(start_time) AS start,
            MONTH(start_time) AS month,
            AVG(motivation) AS motivation
         FROM
            work
         WHERE
            (user_id = ?) AND
            (MONTH(start_time)= ?) AND
            (YEAR(start_time)= ?)
         GROUP BY
            DATE(start_time), type_number
         ORDER BY
            start_time";

   if($stmt = $mysqli->prepare($sql)){
      $stmt->bind_param("sii", $user_id, $month, $year);
      $stmt->execute();
      $stmt->bind_result($type_no, $start_time, $month, $motivation);
   }
   else{
      echo "DB接続失敗";
   }

   $row_no=1;
   $data=[];

   while($stmt->fetch()){
      $start_time = intval((new DateTime($start_time))->format('d'));
      while(($type_no==$num)){
         if($start_time == $row_no){
            $motivation=floatval($motivation);
            array_push($data, $motivation);
            $row_no++;
            break;
         }
         else{
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
</script>
<script src="motivation.js"></script>
</head>

<body>
   <?php require("header.php"); ?>
   <main>
   <div id="head">
      <h1>モチベーショングラフ</h1>
   </div>
   <a href="session_delete.php?filename=homepage.php">ホームページに戻る</a>
   <div id="graph">
   <form action="motivation.php" method="POST">
      <br>
      任意の年月を選択：<input type="month" name="date">
      <input type="submit" name="jump" value="ページに進む">
   </form>
   <div id="transition">
   <label id="before">
      <a href="month_transition.php?type=before&filename=<?php echo $filename ?>"><span>前の月へ</span><span class="material-icons">navigate_before</span></a>
   </label>
   <label id ="next">
      <a href="month_transition.php?type=next&filename=<?php echo $filename ?>"><span class="material-icons">navigate_next</span><span>次の月ヘ</span></a>
   </label>
   </div>
   <div id = "container"></div>
   </div>
   </main>
</body>
</html>