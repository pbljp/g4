<?php
    session_start();
    require("to_login.php");

    $change_type_number = $_POST['change_type_number'];// 名前を変更するジャンル番号
    $new_type_name      = $_POST['new_type_name'];

    require("get_type_names.php");
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>ジャンル名変更-確認</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="rename_types.css">
</head>

<body>
    <?php require("header.php");?>
    <div id="head">
            <h1>ジャンル名変更-確認</h1>
    </div>
    <main>
        <div class="box current">
            <table border="1"><tr><th>変更前</th><td rowspan="2">→</td><th>変更後</th></tr>
                <tr><td>
                    <?php
                        //変更前のジャンル名を列挙
                        foreach ($type_names as $key => $value) {
                            echo $key."：".$value."<br>";
                        }
                        echo "</td><td>";
                        //変更後のジャンル名を列挙，変更点は赤文字
                        foreach ($type_names as $key => $value) {
                            if($key == $change_type_number){
                                echo '<font color = "red">'.$key."：".$new_type_name."</font><br>";
                            }
                            else{
                                echo $key."：".$value."<br>";
                            }
                        }
                    ?>
                </td></tr>
            </table>
            <div class="underline">
                ジャンル名を変更すると，「<?php echo $type_names[$change_type_number];?>」のこれまでの入力データは消去されます！
            </div>
            <form action="rename_types_complete.php" method="POST">
                <input type="hidden" name="change_type_number" value="<?php echo $change_type_number;?>">
                <input type="hidden" name="new_type_name"      value="<?php echo $new_type_name;?>">
                <div class="settings-item-btn">
                    <input type="submit" class="btn" value="変更を確定">
                </div>
            </form>
        </div>
    </main>
</body>
</html>