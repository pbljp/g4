<?php
    session_start();
    require("to_login.php");
    require("connect_g4_db.php");

    //現在の公開設定を取得
    $sql = "UPDATE users SET is_public=? WHERE user_id=?";
    if ($stmt = $mysqli->prepare($sql)) {
        $is_public = $_POST['new_is_public'];
        $user_id = $_SESSION['user_id'];
        $stmt->bind_param("is", $is_public, $user_id);
        $stmt->execute();
    }
    $stmt->close();
    $mysqli->close();

    header("location: rename_types.php");