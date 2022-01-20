<?php
#データベース接続
$name_array=[]; #ジャンルの名前を格納
$con_junre = mysqli_connect('localhost', 'root', '');
mysqli_select_db($con_junre, "g4");
mysqli_set_charset($con_junre, "utf8");
$junre_name=mysqli_query($con_junre, "SELECT * FROM types WHERE user_id='$login_user_id'");
while($name=mysqli_fetch_array($junre_name)){
   array_push($name_array, $name['type_name']);
}

mysqli_close($con_junre);

$j1_name=json_encode($name_array[0]);
$j2_name=json_encode($name_array[1]);
$j3_name=json_encode($name_array[2]);
?>