<?php
require "config.php";
$array = array(); //Create it already, we gonna need it
if (isset($_GET['koen'])) {
	/*
	GET PARAMS
	id=
		table levels
		column id
	*/
	if (isset($_GET['levelid'])) {
		//Insert magic...
		//First, get number of colours (difficilty) by levelid
		$select = $con->query("SELECT noc FROM levels WHERE id = '".$con->real_escape_string($_GET['levelid'])."' LIMIT 1");
		$sr = $select->fetch_assoc();
		
		//Second, create an array with the above difficulty
		
		/*
		for ($i = 0; $i < $sr['noc']; $i++) {
			$array[] = $game->colourImg();
		}
		*/
		$result = json_encode($game->create($sr['noc'])); //Make JSON, because I love JSON
		
		//Third, create game with above result
		$hash = md5($_SERVER['REMOTE_ADDR']."-".date("d m y h i s u")); //To return
		$create = $con->query("INSERT INTO games (uid,colours,hash,noc) VALUES ('".$con->real_escape_string($_SESSION['uid'])."','".$con->real_escape_string($result)."','".$hash."','".$sr['noc']."')");
		
		//Last, but not lease... Return the gamehash when game is created
		if ($create) {
			echo $hash;
		} else {
			echo "false";
		}
	} else {
		//LevelID does not exist, WTF this is not legal! Whaaaaaa!
		http_response_code(405);//Method not Allowed
		echo "false - no levelid";
	}
} else {
	//Yeah.... Nice try, action not allowed
	http_response_code(405);//Method not Allowed
	echo "false - nokoen";
}1
?>