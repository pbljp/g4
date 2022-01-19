<?php
    session_start();
    $_SESSION = array();

    echo "ログアウトしました．自動でログイン画面へ戻ります";
    header("refresh:5;url=login.php");