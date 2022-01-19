<?php
    session_start();
?>

<head>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
    <link href="header.css" rel="stylesheet">
</head>

<header class="site-header">
        <ul class="menu">
            <li class="menu_item"><a href="homepage.php"><span class="material-icons-outlined header">insights</span></a></li>
            <li class="menu_item"><a href="day_charts.php"><span class="material-icons-outlined header">today</span></a></li>
            <li class="menu_item"><a href="input.html"><span class="material-icons-outlined header">edit</span></a></li>
            <li class="menu_item"><a href=""><span class="material-icons-outlined header">delete</span></a></li><!-- TODO:ファイル名を聞いて書いておく -->
            <li class="menu_item"><a href="rename_types.php"><span class="material-icons-outlined header">settings</span></a></li>
        </ul>
        <ul class="logout">
            <li class="logout_item">ユーザID：<?php echo htmlspecialchars($_SESSION['user_id']);?></li>
            <li class="logout_item"><a href="logout.php"><span class="material-icons-outlined header">logout</span></a></li>
        </ul>
</header>