<?php
require "config.php";
$q = $con->query("SELECT summary FROM games_attempts WHERE id='".$_GET['id']."'");
$r = $q->fetch_assoc();
echo $r['summary'];
?>