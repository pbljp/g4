<?php
//選択した入力済みを別の場所人表示しておく
// require('dbconnect.php');
// $stmt = $db->prepare('select * from memos where work_id=? and user_id=?');
// if (!$stmt) {
//     die($db->error);
// }
$work_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
// $stmt->bind_param('is', $id, $user_id);
// $stmt->execute();

// $stmt->bind_result($work_id, $user_id, $type_number,);
// $result = $stmt->fetch();
// if (!$result) {
//     die('メモの指定が正しくありません');
// }
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>入力フォーム</title>
<style>
form {
     width: 400px;
     border: solid 1px black;
     padding: 10px;
     margin: 30px auto;
}

.box1 {
     text-align: center;
}

p.right {
     text-align: right;
}

label {
    display: block;
   }

</style>
</head>
<body>
 <input type="button" onclick="location.href='input_list.php'" value="戻る">

 <form action="update_do.php" method="post">
   <div class="box1">
     <label>ジャンル</label>
     <select name="genre">
       <option value="勉強">勉強</option>
       <option value="バイト">バイト</option>
       <option value="部活">部活</option>
     </select>
     <label>年月日</label>
     <input type="date" name="date">
     <label>作業時間</label>
     <input type="time" name="times">～<input type="time" name="timee">
     <label>コメント</label>
     <textarea name="comment" cols="50" rows="10" placeholeder="コメントを入力してください"></textarea>
     <input type="file" name="" accept="image/jpeg, image/png, image/gif" multipe>
   </div>
     <p class="right">
       <input type="reset" value="クリア">
       <input type="submit" value="登録">
     </p>
      <input type="hidden" name="work_id" value="<?php echo $work_id; ?>">
 </form>
</body>
</html>