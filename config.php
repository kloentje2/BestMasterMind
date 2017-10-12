<?php
ini_set("session.hash_function","sha512");
session_start();

error_reporting(E_ALL);
ini_set("display_errors","On");

$db_host = "localhost";
$db_user = "khollander_mm";
$db_pass = "mp8js7wz";
$db_data = "khollander_mm";

//$con = new mysqli($db_host,$db_user,$db_pass,$db_data);
$con = new mysqli($db_host,$db_user,$db_pass,$db_data);

require "classes/class.game.php";
$game = new Game($con);

require "classes/class.user.php";
$user = new User($con);

$molliekey['test'] = "test_kdVTdbwctPBxKxr9pcsmrjsAueNN2H";
$molliekey['live'] = "live_Q3k294EmPmWH2ah3DDWdsqfESpnCW7";

if (!session_id()) {
	echo "Jouw browser ondersteund geen cookies of je hebt ze geblokkeerd. Zodoende kan je het spel niet spelen";
	exit;
}
?>