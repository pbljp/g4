<?php
    //データベースに接続する

    //本番環境で使う時は，事前に↓の4項目を埋めておく
    define('HOSTNAME', '');
    define('USER', '');
    define('PASSWORD', '');
    define('DBNAME', '');
    
    $mysqli = new mysqli(HOSTNAME, USER, '', DBNAME);
    if($mysqli->connect_error){
        echo $mysqli->connect_error;
        echo "DB接続失敗";
    exit();
    } else {
        $mysqli->set_charset("utf8");
    }