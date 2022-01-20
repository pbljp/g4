<?php
session_start();
//2022/01/11 $another_user_id → $login_user_id に変更
//ログインユーザのIDを取得
if(isset($_SESSION['login_user_id'])){
   //値がセットされていればなにも行わない
}
else{
   exit();
}

if(!(isset($_SESSION['date']))){
      $today = new DateTime();
      $date = $today->format('Y-m-d');
      $_SESSION['date']=$date;
}
else{
        $date = $_SESSION['date'];
}

//GETで閲覧対象のユーザIDを取得
if(!(isset($_GET['login_user_id']))){
   $login_user_id = $_GET['login_user_id'];
}
else{
   $login_user_id = $_GET['login_user_id'];
}

//任意の年月へ飛ぶ用のGET処理(2022/01/04)
if((isset($_GET['login_user_id'])) && (isset($_GET['date'])) && (isset($_GET['jump']))){
   $login_user_id = $_GET['login_user_id'];
   $date = $_GET['date'];
}

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
<link href="homepage.css" rel="stylesheet">
<script type="text/javascript" src="http://zeptojs.com/zepto.min.js"></script>
<script type="text/javascript">
</script>
<!-- php-jsonをインストール-->

<?php
function element($login_user_id,$end_date, $num){

   global $month;
   global $year;

   #データベース接続
   $con = mysqli_connect('localhost','root','');
   mysqli_select_db($con, "g4");
   $sql ="SELECT
            user_id, type_number, DATE(start_time) AS st, YEAR(start_time) AS year, MONTH(start_time) AS month,
            DAY(start_time) AS day, SUM(working_minutes) AS s
         FROM
            work
         WHERE
            (user_id = '$login_user_id') AND
            (MONTH(start_time)='$month') AND
            (YEAR(start_time)='$year')
         GROUP BY
            DATE(start_time), type_number
         ORDER BY
            start_time";
   $work = mysqli_query($con, $sql);

   $j=1;
   $j1=[];

   while($item = mysqli_fetch_array($work)){
      $user_id = $item['user_id'];
      $sum = $item['s'];
      $d = $item['st'];
      $junre=$item['type_number'];
      $date_db = new DateTime($d);
      $mon=$item['month'];
      $date_d = $date_db->format('d');
      $date_d = intval($date_d);

      while(($junre==$num)){
              if($date_d == $j){
                      $sum=intval($sum);
                      array_push($j1, $sum);
                      $j++;
                      break;
              }
              else{
                      array_push($j1, 0);
                      $j++;
              }
      }
   }
   for($k=$j; $k<=$end_date; $k++){
         array_push($j1, 0);
   }
   mysqli_close($con);
   return $j1;
}

$end_date =  (new DateTimeImmutable($date))->modify('last day of')->format('d'); // 2021-03-31
$end_date = intval($end_date);

/* 2022/01/11
ジャンルネーム取得機能を別ファイルに移動
*/
require("get_JunleName.php");

$j1 = []; #ジャンル1の日付ごとの作業時間を格納
$j2 = [];
$j3 = [];

$j1= element($login_user_id, $end_date, 1);
$j2= element($login_user_id, $end_date, 2);
$j3= element($login_user_id, $end_date, 3);
$date_title =json_encode($date_title);
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
   <main>
   <h1>勤怠管理システム</h1>
   <?php require("header.php");?>
   <a href="change_today.php">閲覧リストに戻る</a><br>
   <!--2022/01/11 遷移消去-->>

   <!--任意の年月へ遷移-->
   <form action="anotherUser_worktime.php" method="GET">
      <input type="hidden" name="login_user_id" value=<?php echo $login_user_id ?>>
      任意の年月を選択：<input type="month" name="date">
      <input type="submit" name="jump" value="ページに進む">
   </form>
   <label id="before"><a href="before_month_anotherUser.php?login_user_id=<?php echo $login_user_id ?>"><span>前の月へ</span><span class="material-icons">navigate_before</span></a>
   </label>
   <label>
   <a href="next_month_anotherUser.php?login_user_id=<?php echo $login_user_id ?>"><span id="next" class="material-icons">navigate_next</span><span id="next">次の月ヘ</span></a>
   </label>

   <div id = "container"></div>
   </main>
</body>
</html>