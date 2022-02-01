<?php
    session_start();
    require("to_login.php");
    require("get_type_names.php");

    require("connect_g4_db.php");
    //現在の公開設定を取得
    $sql="SELECT is_public, goal_minutes FROM users WHERE user_id=?";
    if ($stmt = $mysqli->prepare($sql)) {
        $user_id = $_SESSION['user_id'];
        $stmt->bind_param("s", $user_id);
        $stmt->execute();
        $stmt->bind_result($is_public, $goal_minutes);
        $stmt->fetch();
    }
    $stmt->close();
    $mysqli->close();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>各種設定</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="rename_types.css">
</head>

<body>
    <?php require("header.php");?>
    <div id="head">
            <h1>各種設定</h1>
    </div>
    <main>

        <!-- ジャンル名を列挙 -->
        <div class="box current">
            <table border="1"><tr><th>現在の設定</th><tr>
                <tr>
                    <td>
                        <?php
                            foreach ($type_names as $key => $value) {
                                echo $key."：".$value."<br>";
                            }
                            if($is_public){
                                echo '公開設定：<u>公開</u><br>';
                            } else {
                                echo '公開設定：<u>非公開</u><br>';
                            }
                            echo "目標作業時間：$goal_minutes(分/日)";
                        ?>
                    </td>
                </tr>
            </table>
        </div>

        <div class="settings">
            <div class="box settings-item">
                <b>ジャンル名変更</b><br>
                <form action="rename_types_confirm.php" method="POST">
                    ジャンル番号：
                    <select name="change_type_number" id="type_number">
                        <?php
                            foreach($type_names as $key => $value){
                                echo '<option value="'.$key.'">'.$key.'</option>';
                            }
                        ?>
                    </select><br>
                    ジャンル名　：<input type="text" id="type_name" name="new_type_name" maxlength="20" required><br>
                    <div class="settings-item-btn">
                        <input class="btn" type="submit" value="確認画面へ">
                    </div>
                </form>
            </div>
            <div class="box settings-item">
                <b>その他設定</b>
                <form action="switch_is_public.php" method="POST">
                    <span>
                        <input type="hidden" name="new_is_public" value="0">
                        作業を公開する：<input type="checkbox" name="new_is_public" id="is_public" value="1" <?php if($is_public){echo "checked";}?>><br>
                        目標作業時間　：<input type="number" name="new_goal_minutes" id="goal_minutes" size="4" min="1" max="1440" value="<?php echo $goal_minutes;?>">
                        (分/日)<br>
                        <div class="settings-item-btn">
                            <input class="btn settings-item-btn" type="submit" value="設定を保存">
                        </div>
                    </span>
                </form>
            </div>
        </div>
    </main>
</body>
</html>