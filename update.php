<?php
require('library.php');
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    header('Location: index.php');
    exit();
}


//ジャンル取得
$db = dbconnect();
$stmt = $db->prepare('SELECT type_number, type_name FROM types WHERE user_id=?');
if (!$stmt) {
  die($db->error);
}
$stmt->bind_param('s', $user_id);
$success = $stmt->execute();
    
$stmt->bind_result($type_number, $type_name);
$i=1;
while ($stmt->fetch()){
  // $genre_nuber[$i] = $type_number;
  // $genre[$i] = $type_name;
  // $i++;
  $genre[$type_number] = $type_name;
}

// 選択した入力済みを別の場所人表示しておく
$db =dbconnect();
$stmt = $db->prepare('select type_number, start_time, finish_time, working_minutes, comment, motivation from work where work_id=? and user_id=?');
if (!$stmt) {
    die($db->error);
}
$work_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
$stmt->bind_param('is', $work_id, $user_id);
$stmt->execute();

$stmt->bind_result($type_number, $start_time, $finish_time, $working_minutes, $comment, $motivation);
$result = $stmt->fetch();
if (!$result) {
    die('メモの指定が正しくありません');
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<link rel="stylesheet" href="style.css">
<title>編集</title>
<style>
  form {
    width: 400px;
    border: solid 1px black;
    padding: 10px;
    margin: 30px auto;
}
</style>
</head>
<body>
<?php require("header.php") ?>
<div id="whole">
  <div id="head">
    <h1>編集</h1>
  </div>
  <button class="btn" onclick="location.href='input_list.php'">戻る</button>
  <div id="content">
  <div class="msg">
        <p>入力済み内容<?php echo h($i); ?></p>
        ジャンル : <?php echo h($genre[$type_number]); ?> <br>
        日時 : <?php echo h($start_time); ?> ~ <?php echo h($finish_time); ?><br>
        作業時間 : <?php echo h($working_minutes); ?> 分<br>
        モチベーション : <?php echo h($motivation); ?>
        </p>
        <hr>
    </div>
    <form action="update_do.php" method="post">
      <div class="box1">
        <label class="box2">ジャンル</label>
        <select name="genre">
          <option value="<?php echo $genre[1]; ?>"><?php echo $genre[1]; ?></option>
          <option value="<?php echo $genre[2]; ?>"><?php echo $genre[2]; ?></option>
          <option value="<?php echo $genre[3]; ?>"><?php echo $genre[3]; ?></option>
        </select>
        <label class="box2">年月日</label>
        <input type="date" name="date">
        <label class="box2">作業時間</label>
        <input type="time" name="times">～<input type="time" name="timee">
        <label class="box2">モチベーション評価</label>
        <div style="text-align: left;">
          <input type="radio" name="motivation" value=5 id="moti5"><label for="moti5">集中して取り組めた</label><br>
          <input type="radio" name="motivation" value=4 id="moti4"><label for="moti4">そこそこ集中できた</label><br>
          <input type="radio" name="motivation" value=3 id="moti3"><label for="moti3">普通</label><br>
          <input type="radio" name="motivation" value=2 id="moti2"><label for="moti2">あまり集中できなかった</label><br>
          <input type="radio" name="motivation" value=1 id="moti1"><label for="moti1">全然集中できなかった</label>
        </div>
          <label>コメント</label>
        <textarea name="comment" cols="50" rows="10" placeholeder="コメントを入力してください"><?php echo h($comment); ?></textarea>
        <!-- <input type="file" name="" accept="image/jpeg, image/png, image/gif" multipe> -->
      </div>
      <p class="right">
        <button class="btn" type="reset">クリア</button>
        <button class="btn" type="submit">登録</button>
      </p>
      <input type="hidden" name="work_id" value="<?php echo $work_id; ?>">
      </form>
    </div>
</div>
  </body>
</html>
