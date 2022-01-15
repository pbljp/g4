<?php
    session_start();
    require("to_login.php");

    $change_type_number = $_POST['change_type_number'];// 名前を変更するジャンル番号
    $new_type_name      = $_POST['new_type_name'];

    // 変更前の作業を全て論理削除，ジャンル名更新
    require("connect_g4_db.php");
    $sql  = "UPDATE work  SET is_deleted=b'1' WHERE user_id=? AND type_number=?";
    $sql2 = "UPDATE types SET type_name=?     WHERE user_id=? AND type_number=?";
    if(($stmt = $mysqli->prepare($sql)) && ($stmt2 = $mysqli->prepare($sql2))) {
        $user_id = $_SESSION['user_id'];
        $user_id = "user_example2"; //デバッグ用

        $stmt->bind_param("si", $user_id, $change_type_number);
        $stmt->execute();
        $stmt->close();

        $stmt2->bind_param("ssi", $new_type_name, $user_id, $change_type_number);
        $stmt2->execute();
        $stmt2->close();

        $mysqli->close();
    }
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>ジャンル名変更-完了</title>
</head>

<body>
<?php

    // ジャンル名を列挙
    require("get_type_names.php");
    echo '<table border="1"><tr><th>変更後</th><tr>';
    echo "<tr><td>";
    foreach ($type_names as $key => $value) {
        echo $key."：".$value."<br>";
    }
    echo "</td></tr></table><br>";    
?>

<p>
    ジャンル名が変更されました．<br>
    <a href="homepage.php">ホームへ戻る</a>
</p>

</body>
</html>