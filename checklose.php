<?php
//game_attempts 20+? Lose
require "config.php";
$check = $con->query("SELECT COUNT(ga.id) AS c FROM games_attempts AS ga INNER JOIN games AS g ON ga.gameid=g.id WHERE g.hash = '".$con->real_escape_string($_GET['hash'])."'");
$row = $check->fetch_assoc();
if ($row['c'] > 19) {
	echo "true";
} else {
	echo "false";
}
?>