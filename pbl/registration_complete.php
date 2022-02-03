<!DOCTYPE html>
<html lang="ja">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <title>ユーザ登録完了</title>
  <link rel="stylesheet" href="style.css">
</head>

<body>
  <div id="whole">
    <div id="head">
      <h1>ユーザ登録完了</h1>
    </div>
    <div id="content">
      <?php
        if(!isset($_POST['user_id']) || !isset($_POST['password'])){
          header('location: register.php');
        }

        require("connect_g4_db.php");

        $sql  = "INSERT IGNORE INTO users (user_id, encrypted_password)     VALUE (?, ?)";
        $sql2 = "INSERT        INTO types (user_id, type_number, type_name) VALUE (?, 1, '勉強'), (?, 2, '部活'), (?, 3, 'バイト')";
        if($stmt = $mysqli->prepare($sql)) {
          $user_id = $_POST['user_id'];
          $encrypted_password = hash('sha256', $_POST['password']);
          $stmt ->bind_param("ss", $user_id, $encrypted_password);
          $stmt ->execute();
          if($mysqli->affected_rows == 0){
            echo "既に存在するユーザです<br>";
            echo '&raquo<a href="register.php">新規登録画面へ</a>';
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
        &raquo<a href="login.php">ログイン画面へ</a>
      </p>
    </div>
  </div>
</body>
</html>
