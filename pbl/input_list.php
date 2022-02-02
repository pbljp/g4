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

$db = dbconnect();

$stmt = $db->prepare('select work_id, type_number from work where user_id=? order by work_id desc');
if (!$stmt) {
    die($db->error);
}

$stmt->bind_param('s', $user_id);
$success = $stmt->execute();
if(!$success) {
    die($db->error);
}

$stmt->bind_result($work_id, $type_number);
while ($stmt->fetch()):
?>
    <div class="msg">
    <p>作業番号<?php echo h($work_id); ?>    ユーザーID<span class="name">（<?php echo h($user_id); ?>)</span></p>
    <p class="day">ジャンル<a href="update.php?id=<?php echo h($work_id); ?>"><?php echo h($type_number); ?></a>
        [<a href="delete.php?id=<?php echo h($work_id) ?>" style="color: #F33;">削除</a>]
    </p>
    </div>
<?php endwhile; ?>

