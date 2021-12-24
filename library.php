<?php
/*htmlspecialcharsを短縮 */
function h($value) {
    return htmlspecialchars($value, ENT_QUOTES);
}

/*DBの接続 */
function dbconnect() {
    $db = new mysqli('localhost:', 'root', '', 'g4');
	if (!$db) {
		die($db->error);
	}
    return $db;
}
?>
