<?php
session_start();

if(!(isset($_SESSION['date']))){
   $today = new DateTime();
   $date = $today->format('Y-m-d');
   $_SESSION['date']=$date;
}
else{
   $date = $_SESSION['date'];
}

//2021/12/20追加
if(!(isset($_SESSION['login_user_id']))){
   $login_user_id="user_example2";
   $_SESSION['login_user_id']=$login_user_id;
}
else{
   $login_user_id=$_SESSION['login_user_id'];
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

<?php
function element($login_user_id,$end_date, $num){

   global $month;
   global $year;
   #データベース接続
   $con = mysqli_connect('ホスト名','ユーザ名','');
   mysqli_select_db($con, "データベース名");
   $data = mysqli_query($con, 'SELECET * FROM types');
   $db = mysqli_fetch_all($data);
   $work = mysqli_query($con, "SELECT user_id,  type_number, DATE(start_time) AS st, YEAR(start_time) AS year, MONTH(start_time) AS month, DAY(start_time) AS day, SUM(working_minutes) AS s  FROM work where (user_id = '$login_user_id' )AND (MONTH(start_time)='$month') AND(YEAR(start_time)='$year') GROUP BY DATE(start_time), type_number ORDER BY start_time");

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


#データベース接続
$name_array=[]; #ジャンルの名前を格納
$con_junre = mysqli_connect('ホスト名', 'ユーザ名', '');
mysqli_select_db($con_junre, "データベース名");
mysqli_set_charset($con_junre, "utf8");
$junre_name=mysqli_query($con_junre, "SELECT * FROM types WHERE user_id='$login_user_id'");
while($name=mysqli_fetch_array($junre_name)){
   array_push($name_array, $name['type_name']);
}

mysqli_close($con_junre);

$j1_name=json_encode($name_array[0]);
$j2_name=json_encode($name_array[1]);
$j3_name=json_encode($name_array[2]);
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
<h1>勤怠管理システム</h1>
<button onclick="location.href='homepage.php'">編集</button>
<button onclick="location.href='homepage.php'">作業時間を見る</button>
<div align="right">
<button onclick="location.href='homepage.php'" align="right">作業時間を入力</button>
</div>

<!--2021/12/19追記(遷移先を変更)-->
<div align="ranking">
<button onclick="location.href='change_today.php'">他のユーザーの作業時間を見る</button>
</div>

<div id = "date"></div>

<label id="before"><a href="before_month.php"><span>前の月へ</span><span class="material-icons">navigate_before</span></a>
</label>
<label>
<a href="next_month.php"><span id="next" class="material-icons">navigate_next</span><span id="next">次の月ヘ</span></a>
</label>

<div id = "container"></div>
</body>
</html>