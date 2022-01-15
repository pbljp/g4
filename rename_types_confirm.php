<?php
    session_start();
    require("to_login.php");

    $change_type_number = $_POST['change_type_number'];// 名前を変更するジャンル番号
    $new_type_name      = $_POST['new_type_name'];

    require("get_type_names.php");
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>ジャンル名変更-確認</title>
</head>

<body>
<?php

    echo '<table border="1"><tr><th>変更前</th><th>→</th><th>変更後</th><tr>';
    echo "<tr><td>";
    //変更前のジャンル名を列挙
    foreach ($type_names as $key => $value) {
        echo $key."：".$value."<br>";
    }
    echo "</td><td>→</td><td>";
    //変更後のジャンル名を列挙，変更点は赤文字
    foreach ($type_names as $key => $value) {
        if($key == $change_type_number){
            echo '<font color = "red">'.$key."：".$new_type_name."</font><br>";
        }
        else{
            echo $key."：".$value."<br>";
        }
    }
    echo "</td></tr></table><br>";
?>

ジャンル名を変更すると，「<?php echo $type_names[$change_type_number];?>」のこれまでの入力データは消去されます！<br>

<form action="rename_types_complete.php" method="POST">
    <input type="hidden" name="change_type_number" value="<?php echo $change_type_number;?>">
    <input type="hidden" name="new_type_name"      value="<?php echo $new_type_name;?>">
    <input type="submit" value="変更を確定">
</form>
</body>
</html>