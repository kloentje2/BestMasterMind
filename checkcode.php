<?php
require "config.php";
if (isset($_GET['code'])) {
	$check = $con->query("SELECT * FROM pro_codes WHERE code = '".$con->real_escape_string($_GET['code'])."' LIMIT 1");
	if ($check->num_rows == 1) { //Yeah it exists!
		$row = $check->fetch_assoc();
		if ($row['uses_to_go'] != 0) {
			$update = $con->query("UPDATE pro_codes SET uses_to_go = uses_to_go-1 WHERE code = '".$con->real_escape_string($_GET['code'])."'");
			if ($update) {
				$promote = $con->query("UPDATE users SET role='pro' WHERE id='".$con->real_escape_string($_SESSION['uid'])."'");
				if ($promote) {
					echo "true";
				} else {
					echo "Er heeft zich een technische fout voorgedaan, contacteer de beheerder als dit vaker gebeurd. <br>(Foutcode: #14)";
				}
			} else {
				echo "Er heeft zich een technische fout voorgedaan, contacteer de beheerder als dit vaker gebeurd. <br>(Foutcode: #17)";
			}
		} else {
			echo "De code heeft zijn gebruikerslimiet bereikt.";
		}
	} else {
		echo "De ingevoerde code is niet gevonden.";
	}
} else {
	echo "Onjuiste aanvraag, contacteer de beheerder als dit vaker gebeurd.";
}
?>