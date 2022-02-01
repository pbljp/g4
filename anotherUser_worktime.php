<?php
session_start();

require("to_login.php");
//2022/01/11 $another_user_id → $login_user_id に変更

if(!(isset($_SESSION['date']))){
      $today = new DateTime();
      $date = $today->format('Y-m-d');
      $_SESSION['date']=$date;
}
else{
        $date = $_SESSION['date'];
}

//GETで閲覧対象のユーザIDを取得
if(!(isset($_GET['user_id']))){
   $user_id = $_GET['user_id'];
}
else{
   $user_id = $_GET['user_id'];
}

//任意の年月へ飛ぶ用のGET処理(2022/01/04)
if((isset($_GET['user_id'])) && (isset($_GET['date'])) && (isset($_GET['jump']))){
   $user_id = $_GET['user_id'];
   $date = $_GET['date'];
}

//get_types_name.phpの処理(session変数をgetの値に置き換え元に戻す)
$main_id = $_SESSION['user_id'];
$_SESSION['user_id'] = $user_id;
require("get_type_names.php");
$_SESSION['user_id'] = $main_id;

$month = (new DateTime($date))->format('m');
$year = (new DateTime($date))->format('Y');
$date_title = (new DateTime($date))->format('Y-m');
$month = intval($month);
$year = intval($year);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8"/>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<link href="motivation.css" rel="stylesheet">
<link href="style.css" rel="stylesheet">
<script type="text/javascript" src="http://zeptojs.com/zepto.min.js"></script>
<script type="text/javascript">
</script>
<!-- php-jsonをインストール-->

<?php
function element($user_id,$end_date, $num){

   global $month;
   global $year;

   #データベース接続
   require("connect_g4_db.php");
   $sql ="SELECT
            type_number, DATE(start_time), MONTH(start_time) AS month,
            SUM(working_minutes)
         FROM
            work
         WHERE
            (user_id = ?) AND
            (MONTH(start_time)= ?) AND
            (YEAR(start_time)= ?) AND
            (is_deleted= 0)
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

   $row_no=1;
   $data=[];

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

/* 2022/01/11
ジャンルネーム取得機能を別ファイルに移動
*/

$j1 = []; #ジャンル1の日付ごとの作業時間を格納
$j2 = [];
$j3 = [];

$j1= element($user_id, $end_date, 1);
$j2= element($user_id, $end_date, 2);
$j3= element($user_id, $end_date, 3);
$date_title =json_encode($date_title);
$j1_name = json_encode($type_names[1]);
$j2_name = json_encode($type_names[2]);
$j3_name = json_encode($type_names[3]);
$junre1=json_encode($j1);
$junre2=json_encode($j2);
$junre3=json_encode($j3);
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
<script src="homepage.js"></script>

</head>

<body>
   <?php require("header.php"); ?>
   <main>
      <div id="head">
         <h1>作業時間</h1>
      </div>
   <?php require("header.php");?>
   <!--2022/01/11 遷移消去-->

   <!--任意の年月へ遷移-->
   <div id="graph">
   <a id="url" href="default_date.php?filename=ranking.php">ランキングに戻る</a><br> <!--位置変更(2022/01/31)-->
   <h2 align="center"><?php echo $user_id ?>さんの作業時間</h2> <!--2021/01/31追加-->
   <form action="anotherUser_worktime.php" method="GET">
      <input type="hidden" name="user_id" value=<?php echo $user_id ?>>
      任意の年月を選択：<input type="month" name="date">
      <input class="btn" type="submit" name="jump" value="ページに進む"> <!--classを追加(2022/01/31)-->
   </form>
   <div id="transition">
   <label id="before"><a href="month_transition_anotherUser.php?type=before&user_id=<?php echo $user_id ?>"><span>前の月へ</span><span class="material-icons">navigate_before</span></a>
   </label>
   <label id="next">
   <a href="month_transition_anotherUser.php?type=next&user_id=<?php echo $user_id ?>"><span class="material-icons">navigate_next</span><span>次の月ヘ</span></a>
   </label>
   </div>

   <div id = "container"></div>
   </div>
   </main>
</body>
</html>