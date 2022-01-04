<?php
//session['date']を消すファイル
//homepage.phpでアクセスるたびにポップアップ表示をさせないようにするため
session_start();
if(isset($_SESSION['date'])){
   unset($_SESSION['date']);
}
else{
   //そもそもセットされていればなにも行わない
}

header("Location: homepage.php");
exit();
?>