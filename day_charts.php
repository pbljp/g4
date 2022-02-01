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

    //昨日・明日のグラフへ移動する際に使う
    $yesterday = clone $date;
    $yesterday->modify('-1 day');
    $tomorrow = clone $date;
    $tomorrow->modify('+1 day');

    $user_id = $_SESSION['user_id'];

    //指定された日付の全部の作業のジャンル番号・開始時間・終了時間を取得
    $sql  = "SELECT type_number, start_time, finish_time, motivation, comment FROM work WHERE user_id=? AND YEAR(start_time)=? AND MONTH(start_time)=? AND DAY(start_time)=? AND is_deleted=b'0'";
    if($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("siii", $user_id, $year, $month, $day);
        $stmt->execute();
        $stmt->bind_result($type_number, $start_time, $finish_time, $motivation, $comment);
    } else {
        echo "DB接続失敗";
    }

    //json形式にエンコード
    $number = 0; //SELECTで何番目に取得したデータか．
    while ($stmt->fetch()) {
        $x  = new DateTime($start_time,  new DateTimeZone('UTC'));
        $x2 = new DateTime($finish_time, new DateTimeZone('UTC'));
        $data[] = [
            "x"  => $x->getTimestamp()  * 1000,
            "x2" => $x2->getTimestamp() * 1000,
            "y"  => $type_number-1,
            //point.nameが文字列型でないとだめそう
            //面倒だが文字列から整数に変換して用いる
            "name" => "$number",
        ];
        $motivation_array[] = $motivation;
        $comment_array[] = $comment;
        $number++;
    }
    $data_json = json_encode($data);
    $motivation_json = json_encode($motivation_array);
    $comment_json = json_encode($comment_array);
    $stmt->close();

    //合計目標時間と一日の合計作業時間の取得
    $sql2 = "SELECT users.goal_minutes, SUM(work.working_minutes) FROM users JOIN work ON users.user_id=work.user_id WHERE users.user_id=? AND YEAR(start_time)=? AND MONTH(start_time)=? AND DAY(start_time)=? AND work.is_deleted=b'0'";
    if($stmt2 = $mysqli->prepare($sql2)){
        $stmt2->bind_param("siii", $user_id, $year, $month, $day);
        $stmt2->execute();
        $stmt2->bind_result($goal_minutes, $sum_working_minutes);
        $stmt2->fetch();
    }
    $stmt2->close();

    if(!isset($goal_minutes)) {
        $goal_minutes = 1441;
    }
    //作業していない日の合計作業時間は0とする
    if(!isset($sum_working_minutes)){
        $sum_working_minutes = 0;
    }

    $mysqli->close();
?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8"/>
        <title>日毎の記録</title>
        <link rel="stylesheet" href="style.css">

        <script type="text/javascript">
            const year  = <?php echo $year;?>;
            const month = <?php echo $month;?>;
            const day   = <?php echo $day;?>;
            const type1_name = '<?php echo $type_names[1];?>';
            const type2_name = '<?php echo $type_names[2];?>';
            const type3_name = '<?php echo $type_names[3];?>';
            const data_json  =  <?php echo $data_json;?>;
            const motivation_array = <?php echo $motivation_json;?>;
            const comment_array = <?php echo $comment_json;?>;

            const goal_minutes = <?php echo $goal_minutes;?>;
            const sum_working_minutes = <?php echo $sum_working_minutes;?>;
        </script>

        <link href="day_charts.css" rel="stylesheet">
        <script type="text/javascript" src="http://zeptojs.com/zepto.min.js"></script>
        <script src="https://code.highcharts.com/highcharts.js"></script>
        <script src="https://code.highcharts.com/modules/xrange.js"></script>
        <script src="https://code.highcharts.com/modules/exporting.js"></script>
        <script src="https://code.highcharts.com/modules/accessibility.js"></script>
        <script src="day_charts.js"></script>

    </head>

    <body id="hanabi">
        <?php require("header.php");?>
        <main id="whole">
            <div id="head">
                <h1>日毎の記録</h1>
            </div>
            <div id="chart" style="text-align: center; background-color: white; border: solid; padding: 20px 5px;">
                <form action="" method="POST">
                    <button type="submit" name="date" value="<?php echo $yesterday->format('Y-m-d');?>">
                        <span class="material-icons-outlined header">today</span>
                    </button>
                    <button type="submit" name="date" value="">
                        <span class="material-icons-outlined header">today</span>
                    </button>
                    <button type="submit" name="date" value="<?php echo $tomorrow->format('Y-m-d');?>">
                        <span class="material-icons-outlined header">today</span>
                    </button>
                </form>
                <div id="container"></div>
                <form action="" method="POST">
                    <span>表示する日付：</span>
                    <input type="date" name="date" value="<?php echo $date->format('Y-m-d');?>">&emsp;
                    <input type="submit" class="btn" value="グラフ更新"><br>
                </form>
            </div>
            <span id="" style="text-align: right; background-color: white;">
                目標作業時間：<?php echo $goal_minutes;?>分<br>
                この日の作業時間：<?php echo $sum_working_minutes;?>分<br>
                <b id="goal">目標まであと：<?php echo ($goal_minutes-$sum_working_minutes);?>分です</b>
            </span>
        </main>

        <script>
            function launchHanabi(){
                var pObj = document.getElementById("hanabi");
                pObj.style.backgroundImage = 'url("hanabi.gif")';
                pObj.style.backgroundRepeat = "no-repeat";
                pObj.style.backgroundSize = "cover";
            }
            var goalText = document.getElementById("goal");
            if(sum_working_minutes >= goal_minutes){
                goalText.innerHTML = "目標達成<br>";
                window.onload = launchHanabi();
            }
        </script>
    </body>
</html>