<?php
session_start();
require('library.php');

// if (isset($_SESSION['id']) && isset($_SESSION['name'])) {
//     $id = $_SESSION['id'];
//     $name = $_SESSION['name'];
// } else {
//     header('Location: login.php');
//     exit();
// }

$user_id = 'user_example2';

$work_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
if (!$work_id) {
    header('Location: input_list.php');
    exit();
}
$db = dbconnect();
$stmt = $db->prepare('delete from work where work_id=? and user_id=? limit 1');
if (!$stmt) {
    die($db->error);
}
$stmt->bind_param('is', $work_id, $user_id);
$success = $stmt->execute();
if (!$success) {
    die($db->error);
}

header('Location: input_list.php'); 
exit();
?>
