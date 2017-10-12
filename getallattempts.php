<?php
require "config.php";
$attempt = "";
$gameidq = $con->query("SELECT id FROM games WHERE hash = '".$con->real_escape_string($_GET['hash'])."'");
$gameid = $gameidq->fetch_assoc();

$getall = $con->query("SELECT id,attempt FROM games_attempts WHERE gameid = '".$con->real_escape_string($gameid['id'])."' ORDER BY id DESC");


while ($row = $getall->fetch_assoc()) {
	$attemptj = json_decode($row['attempt']);
	foreach($attemptj as $i) {
		$attempt .= "<img src=\"https://koenhollander.nl/mastermind/images/".$i.".png\">";
	}
	?>
		<a onclick="getAttemptComment(<?php echo $row['id']; ?>);" data-toggle="modal" data-target="#commentModal" class="list-group-item"><?php echo $attempt; ?></a>
	<?php
	$attempt = "";
}
?>