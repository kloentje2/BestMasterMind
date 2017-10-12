<?php
require "config.php";
$dq = $con->query("UPDATE games SET status = 'closed' WHERE uid='".$_GET['uid']."'");
?>