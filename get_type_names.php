<?php
   session_start();
    // データベースに接続して，ログイン中ユーザのジャンル番号とジャンル名を配列に代入する
    // 出力例)
    // $type_names[1]："勉強"
    // $type_names[2]："部活"
    // $type_names[3]："バイト"

    require("connect_g4_db.php");

    $sql  = "SELECT type_number, type_name FROM types WHERE user_id=?";
    if($stmt = $mysqli->prepare($sql)) {
        $user_id = $_SESSION['user_id'];
        $stmt->bind_param("s", $user_id);
        $stmt->execute();
        $stmt->bind_result($type_number, $type_name);
    }

    while ($stmt->fetch()) {
        $type_names[$type_number] = $type_name;
    }

    $stmt->close();
    $mysqli->close();
?>