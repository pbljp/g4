<?php
    //各ページにて，非ログイン時にログインを促す．
    //事前にsession_start()の呼び出しが必要
    
    if(empty($_SESSION['user_id'])){
        header( "refresh:5;url=index.php" );
        echo "ログインしてください．5秒後にログイン画面へ遷移します<br>";
        exit();
    }