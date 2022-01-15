<?php
    session_start();
    require("to_login.php");

    require("get_type_names.php");
    require("connect_g4_db.php");
    
    //どの日付のグラフを描画するか．day_charts.php内のフォームから日付が送られてくる
    if(isset($_POST['date'])) {
        $date = new DateTime($_POST['date']);
    } else {
        $date = new DateTime();
    }
    $year  = intval($date->format('Y'));
    $month = intval($date->format('m'));
    $day   = intval($date->format('d'));

    $user_id = $_SESSION['user_id'];

    //指定された日付の全部の作業のジャンル番号・開始時間・終了時間を取得
    $sql  = "SELECT type_number, start_time, finish_time FROM work WHERE user_id=? AND YEAR(start_time)=? AND MONTH(start_time)=? AND DAY(start_time)=? AND is_deleted=b'0'";
    if($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("siii", $user_id, $year, $month, $day);
        $stmt->execute();
        $stmt->bind_result($type_number, $start_time, $finish_time);
    } else {
        echo "DB接続失敗";
    }

    //json形式にエンコード
    while ($stmt->fetch()) {
        $x  = new DateTime($start_time,  new DateTimeZone('UTC'));
        $x2 = new DateTime($finish_time, new DateTimeZone('UTC'));
        $data[] = [
            "x"  => $x->getTimestamp()  * 1000,
            "x2" => $x2->getTimestamp() * 1000,
            "y"  => $type_number-1
        ];
    }
    $data_json = json_encode($data);
    /*
    デバッグ用
    foreach($data[0] as $key => $value){
        echo $key.":".$value;
    }
    echo $data_json;
    */
    
    $stmt->close();
    $mysqli->close();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8"/>
    <title>日毎の記録</title>

    <script type="text/javascript">
        const year  = <?php echo $year;?>;
        const month = <?php echo $month;?>;
        const day   = <?php echo $day;?>;
        const type1_name = '<?php echo $type_names[1];?>';
        const type2_name = '<?php echo $type_names[2];?>';
        const type3_name = '<?php echo $type_names[3];?>';
        const data_json  =  <?php echo $data_json;?>;

        console.log(data_json);
    </script>

    <link href="day_charts.css" rel="stylesheet">
    <script type="text/javascript" src="http://zeptojs.com/zepto.min.js"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/xrange.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>
    <script src="day_charts.js"></script>
</head>

<body>
    <div id="container"></div>

    <form action="" method="POST">
        表示する日付：<input type="date" name="date" value="<?php echo $date->format('Y-m-d');?>">&emsp;
        <input type="submit" value="グラフ更新"><br>
    </form>
</body>
</html>