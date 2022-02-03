<?php
require('library.php');
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <title>ユーザ登録</title>
  <link rel="stylesheet" href="style.css">
</head>

<body>
<div id="whole">
    <div id="head">
        <h1>新規登録</h1>
    </div>
    <button class="btn" onclick="location.href='login.php'">ログイン</button>
    
    <div id="content">
        <form action="registration_complete.php" method="POST">
            <dl>
                <dt>ユーザーID</dt>
                <dd>
                    <input type="text" pattern="^[0-9A-Za-z]+$" name="user_id" size="35" maxlength="20" value="" required/>
                </dd>
                <dt>パスワード</dt>
                <dd>
                    <input type="password" name="password" size="35" maxlength="255" value="" required/>
                </dd>
            </dl>
            <div>
                <button class="btn">登録</button>
            </div>
        </form>
    </div>
</div>
</body>
</html>
