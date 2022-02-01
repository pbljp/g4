<?php
    session_start();
    $_SESSION = array();

    header("refresh:5;url=login.php");
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>ログアウト</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="rename_types.css">
    <link rel="stylesheet" href="header.css">
</head>

<body>
    <div id="head">
        <h1>ログアウト</h1>
    </div>
    <div id="logout-complete" style="">
        <div class="box">
            ログアウトしました．<br>自動でログイン画面へ戻ります
        </div>
    </div>
</body>