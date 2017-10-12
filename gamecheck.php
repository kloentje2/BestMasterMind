<?php
require "config.php";
/*

<img onclick="changeImage('i0');" id="i0" src="images/gray.png">
<img onclick="changeImage('i1');" id="i1" src="images/gray.png">
<img onclick="changeImage('i2');" id="i2" src="images/gray.png">
<img onclick="changeImage('i3');" id="i3" src="images/gray.png">
<img onclick="changeImage('i4');" id="i4" src="images/gray.png">

*/

//First, explode on every /n
$firstex = explode("<img ",trim($_POST['game'])); //This should give the same amount of array keys as nocs

//Second, trim everything BEFORE src="
foreach ($firstex as $i) {
	$secondex[] = explode("src=\"",$i); //This should create the same amount of array keys as nocs with only images/gray.png"> in it
}

//Third one
foreach ($secondex as $i) {
		@$thirdex[] = $i[1];
}
$thirdex = array_filter($thirdex, 'strlen'); //Remove the idiotic NULL
$thirdex = array_values($thirdex); //And 'cause the NULL is idiotic, let's reindex :'(

//Fourth one, seperate the last "> 
foreach ($thirdex as $i) {
	$fourthex[] = explode("\">",$i);
}

foreach  ($fourthex as $i) {
	$notfinal[] = $i[0];
}

//Alright, after a lot of code, lets get the GOOD answer from the database using the hash
$get = $con->query("SELECT colours FROM games WHERE hash = '".$con->real_escape_string($_POST['hash'])."'");
$row = $get->fetch_assoc();

$good = json_decode($row['colours']);

//Before saving the attempt lets filter only the colours
foreach ($notfinal as $i) {
	$zfirstex[] = explode("/",$i);
}

//Now, lets get rid of the .png >:(
foreach ($zfirstex as $i) {
	$zsecondex[] = explode(".",$i[5]);
}

foreach ($zsecondex as $i) {
	$final[] = $i[0];
}

//Before checking it, let's save the attempt
//First, we need to get the gameid from the hash
$gameidq = $con->query("SELECT id,noc FROM games WHERE hash = '".$con->real_escape_string($_POST['hash'])."'");
$gameid = $gameidq->fetch_assoc();

//Second, we can insert the attempt in the database

//Lets check - in array / not necessarily same place
$intersect = array_intersect($final,$good); //Look in keys
$intersectc = count($intersect); //Count of equal keys (in array, not necessarily same place

$count = 0;

foreach($final as $key => $value) {
	if ($good[$key] == $value) {
		$count++;
	}
}

$summary = "Er zitten ".$intersectc." kleuren in de combinatie waarvan ".$count." op dezelfde plek";
$insert = $con->query("INSERT INTO games_attempts (gameid,attempt,summary) VALUES ('".$con->real_escape_string($gameid['id'])."','".$con->real_escape_string(json_encode($final))."','".$con->real_escape_string($summary)."')");

if ($count == $gameid['noc'] && $intersectc == $gameid['noc']) { //Win
	$win = $con->query("UPDATE games SET win='yes', status='closed' WHERE id = '".$gameid['id']."'");
	
	//You can try max 20 times
	//0 attempt = 210xp (not used)
	//1 attempt? 200xp
	//2 attempts? 190
	//180
	//170
	//160
	//150
	//140
	//130
	//120
	//110
	//100
	//90
	//80
	//70
	//60
	//50
	//40
	//30
	//20
	//10
	
	$getattemptscount = $con->query("SELECT id FROM games_attempts WHERE gameid='".$gameid['id']."'");
	
	$xp = 210 - ($getattemptscount->num_rows*10);
	
	//$addxp = $con->query("INSERT INTO users_rewardxp (userid,xp_count) VALUES ('".$_SESSION['uid']."','".$xp."')");
	$addxp = $con->query("UPDATE users SET xp = xp + ".$xp." WHERE id = '".$_SESSION['uid']."'");
	
	exit ("win");
} else {
	echo "<script>alert('nowin!');</script>";
}

//var_dump($firstex);
//var_dump($secondex);
//var_dump($thirdex);
//var_dump($fourthex);

/*
foreach($thirdex[0] as $i) {
	echo $i;
}
*/
//var_dump($final); //Yeah!
//var_dump($zfirstex);


var_dump($final); //Finally! :'(
var_dump($good);
?>