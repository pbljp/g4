<?php
//session['date']を消すファイル
//homepage.phpでアクセスるたびにポップアップ表示をさせないようにするため

/* 2022/01/11
change_today.phpと機能統合
それぞれのファイルに番号を割り振り、その番号をGETで本ファイルに受け渡す(変数名は'page_no')
※ファイル番号はgithubのREADMEを参照
*/

session_start();
if(isset($_SESSION['date']) && isset($_GET['page_no'])){
   unset($_SESSION['date']);
}
else{
   //そもそもセットされていればなにも行わない
}

$page_no = $_GET['page_no'];
if($page_no == 1){
   $filename="homepage.php";
}
else if($page_no == 6){
   $filename="ranking.php";
}
else if($page_no == 14){
   $filename="motivation.php";
}
else{
   //もし該当する番号でなければhomepage.phpに戻す
   $filename="homepage.php";
}

header("Location:$filename");
exit();
?>