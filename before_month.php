<?php
session_start();

$date=$_SESSION['date'];
$date = (new DateTimeImmutable($date)) -> modify('first day of this month')->format('Y-m-d');
$date = (new DateTimeImmutable($date))->modify("-1 months")->format('Y-m-d');
$_SESSION['date']=$date;
header("Location:homepage.php");
exit();
?>