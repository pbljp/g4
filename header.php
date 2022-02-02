<?php
    session_start();
?>

<head>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
    <link href="header.css" rel="stylesheet">
</head>

<header class="site-header">
        <ul class="menu">
            <li class="menu_item">
                <a href="default_date.php?filename=homepage.php">
                    <span class="material-icons-outlined header">stacked_bar_chart</span><br>
                    <span>Home</span>
                </a>
            </li>
            <li class="menu_item">
                <a href="default_date.php?filename=motivation.php">
                    <span class="material-icons-outlined header">insights</span><br>
                    <span>Motivation</span>
                </a>
            </li>
            <li class="menu_item">
                <a href="day_charts.php">
                    <span class="material-icons-outlined header">today</span><br>
                    <span>Daily</span>
                </a>
            </li>
            <li class="menu_item">
                <a href="default_date.php?filename=ranking.php">
                    <span class="material-icons-outlined header">emoji_events</span><br>
                    <span>Ranking</span>
                </a>
            </li>
            <li class="menu_item">
                <a href="input.html">
                    <span class="material-icons-outlined header">edit</span><br>
                    <span>Input</span>
                </a>
            </li>
            <li class="menu_item">
                <a href="input_list.php">
                    <span class="material-icons-outlined header">delete</span><br>
                    <span>Edit</span>
                </a>
            </li>
            <li class="menu_item"><a href="rename_types.php"><span class="material-icons-outlined header">settings</span><br><span>Settings</span></a></li>
        </ul>
        <ul class="logout">
            <li class="logout_item">ユーザID：<?php echo htmlspecialchars($_SESSION['user_id']);?></li>
            <li class="logout_item">
                <a href="logout.php">
                    <span class="material-icons-outlined header">logout</span><br>
                    <span>Logout</span>
                </a>
            </li>
        </ul>
</header>