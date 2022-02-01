<?php
//session['date']を消すファイル
//homepage.phpでアクセスるたびにポップアップ表示をさせないようにするため

/* 2022/01/11
change_today.phpと機能統合
それぞれのファイルに番号を割り振り、その番号をGETで本ファイルに受け渡す(変数名は'page_no')
※ファイル番号はgithubのREADMEを参照←番号削除しました
*/

session_start();
if(isset($_SESSION['date']) && isset($_GET['filename'])){
  # unset($_SESSION['date']);
  $_SESSION['date'] = "";//最初にホームページにアクセスしたときだけポップアップが表示されるための処理
}
else{
   //そもそもセットされていればなにも行わない
}

$filename = $_GET['filename'];

header("Location:$filename");
exit();
?>