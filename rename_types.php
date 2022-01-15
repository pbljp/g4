<?php
    session_start();
    require("to_login.php");
    require("get_type_names.php");
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>ジャンル名変更</title>
</head>

<body>
    <?php
        //ジャンル名を列挙
        echo '<table border="1"><tr><th>変更前</th><tr>';
        echo "<tr><td>";
        foreach ($type_names as $key => $value) {
            echo $key."：".$value."<br>";
        }
        echo "</td></tr></table><br>";
    ?>
    <form action="rename_types_confirm.php" method="POST">
        ジャンル番号
        <select name="change_type_number">
            <?php
                foreach($type_names as $key => $value){
                    echo '<option value="'.$key.'">'.$key.'</option>';
                }
            ?>      
        </select>
        ：ジャンル名<input type="text" name="new_type_name" maxlength="20" required><br>
        <input type="submit" value="確認画面へ">
    </form>
</body>
</html>