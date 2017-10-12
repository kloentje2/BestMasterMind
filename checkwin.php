<?php
require "config.php";
if (isset($_GET['hash'])) {
	$q = $con->query("SELECT id FROM games WHERE hash='".$con->real_escape_string($_GET['hash'])."' AND win='yes' AND status='closed'");
	if ($q->num_rows == 1) {
		echo "true";
	} else {
		echo "false";
	}
}
?>