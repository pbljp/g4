<?php
    session_start();
    require("to_login.php");
    require("get_type_names.php");

    require("connect_g4_db.php");
    //現在の公開設定を取得
    $sql="SELECT is_public FROM users WHERE user_id=?";
    if ($stmt = $mysqli->prepare($sql)) {
        $user_id = $_SESSION['user_id'];
        $stmt->bind_param("s", $user_id);
        $stmt->execute();
        $stmt->bind_result($is_public);
        $stmt->fetch();
    }
    $stmt->close();
    $mysqli->close();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>ジャンル名変更</title>
</head>

<body>
    <?php require("header.php");?>
    <main>
        <!-- ジャンル名を列挙 -->
        <table border="1"><tr><th>変更前</th><tr>
            <tr>
                <td>
                    <?php
                        foreach ($type_names as $key => $value) {
                            echo $key."：".$value."<br>";
                        }
                        if($is_public){
                            echo '<u>公開</u><br>';
                        } else {
                            echo '<u>非公開</u><br>';
                        }
                    ?>
                </td>
            </tr>
        </table><br>
        <form action="rename_types_confirm.php" method="POST">
            ジャンル番号
            <select name="change_type_number">
                <?php
                    foreach($type_names as $key => $value){
                        echo '<option value="'.$key.'">'.$key.'</option>';
                    }
                ?>      
            </select>
            ：ジャンル名<input type="text" name="new_type_name" maxlength="20" required>
            <input type="submit" value="確認画面へ"><br>
        </form>
        <form action="switch_is_public.php" method="POST">
            <input type="hidden" name="new_is_public" value="0">
            作業を公開する<input type="checkbox" name="new_is_public" value="1" <?php if($is_public){echo "checked";}?>>
            <input type="submit" value="公開設定を保存"><br>
        </form>
    </main>
</body>
</html>