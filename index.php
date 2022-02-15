<?php
session_start();
require('library.php');
$error = [];
$user_id = '';
$password = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = filter_input(INPUT_POST, 'user_id', FILTER_SANITIZE_EMAIL);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
    if ($user_id === '' || $password === '') {
        $error['login'] = 'blank';
    } else {
        $db = dbconnect();
        $stmt = $db->prepare('select user_id, encrypted_password from users where user_id=? limit 1');
        if (!$stmt) {
            die($db->error);
        }

        $stmt->bind_param('s', $user_id);
        $success = $stmt->execute();
        if (!$success) {
            die($db->error);
        }

        $stmt->bind_result($user_id, $hash);
        $stmt->fetch();

        $encrypted_password = hash('sha256', $password);

        if ($hash === $encrypted_password) {
            //ログイン成功
            session_regenerate_id();
            $_SESSION['user_id'] = $user_id;
            header('Location: homepage.php');    //リンク先かえる
        } else {
            $error['login'] = 'failed';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css"/>
    <title>ログイン</title>
</head>

<body>
<div id="whole">
    <div id="head">
        <h1>ログイン</h1>
    </div>
    <div id="content">
        <div>
            <p>IDとパスワードを記入してログインしてください。</p>
            <p>新規登録がまだの方はこちらからどうぞ。</p>
            <p>&raquo;<a href="register.php">新規登録をする</a></p>
        </div>
        <form action="" method="post">
            <dl>
                <dt>ユーザーID</dt>
                <dd>
                    <input type="text" name="user_id" size="35" maxlength="255" value="<?php echo h($user_id); ?>"/>
                    <?php if (isset($error['login']) && $error['login'] === 'blank'): ?>
                        <p>* ユーザーIDとパスワードをご記入ください</p>
                    <?php endif; ?>
                    <?php if (isset($error['login']) && $error['login'] === 'failed'): ?>
                        <p>* ログインに失敗しました。正しくご記入ください。</p>
                    <?php endif; ?>
                </dd>
                <dt>パスワード</dt>
                <dd>
                    <input type="password" name="password" size="35" maxlength="255" value="<?php echo h($password); ?>"/>
                </dd>
            </dl>
            <div>
                <button class="btn">ログイン</button>
            </div>
        </form>
    </div>
</div>
</body>
</html>
