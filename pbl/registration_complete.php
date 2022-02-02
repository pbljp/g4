<!DOCTYPE html>
<html lang="ja">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <title>ユーザ登録完了</title>
  <link rel="stylesheet" href="style.css">
</head>

<body>
  <?php
    define('HOSTNAME', 's119.cs.ehime-u.ac.jp');
    define('USER', 'root');
    //define('PASSWORD', '');
    define('DBNAME', 'kintaidb');

    $mysqli = new mysqli(HOSTNAME, USER, '', DBNAME);
    if($mysqli->connect_error){
      echo $mysqli->connect_error;
      echo "DB接続失敗";
      exit();
    } else {
      $mysqli->set_charset("utf8");
      //デバッグ用
      //echo "DB接続成功";
    }

    $sql  = "INSERT IGNORE INTO users (user_id, encrypted_password)     VALUE (?, ?)";
    $sql2 = "INSERT        INTO types (user_id, type_number, type_name) VALUE (?, 1, '勉強'), (?, 2, '部活'), (?, 3, 'バイト')";
    if($stmt = $mysqli->prepare($sql)) {
      $user_id = $_POST['user_id'];
      $encrypted_password = hash('sha256', $_POST['password']);
      $stmt ->bind_param("ss", $user_id, $encrypted_password);
      //デバッグ用
      //echo $_POST['user_id'];
      //echo $_POST['password'];
      //echo $encrypted_password;
      $stmt ->execute();
      if($mysqli->affected_rows == 0){
        echo "既に存在するユーザです";
        exit();
      }
    } else {
      echo $stmt ->error;
      exit();
    }
    if($stmt2 = $mysqli->prepare($sql2)){
      $stmt2->bind_param("sss", $user_id, $user_id, $user_id);
      $stmt2->execute();
    } else {
      echo $stmt2->error;
      exit();
    }

  $mysqli->close();
  ?>

  <p>
    ユーザ登録が完了しました。
  </p>
  <br>
  <p>
    <a href="login.html">ログイン画面へ</a>
  </p>
</body>
</html>
