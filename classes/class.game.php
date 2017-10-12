<?php

/*
MasterMind
*/

Class Game {
	
	public $result;
	private $con;
	
	public function __construct($con) {
		if ($con !== null) {
			$this->con = $con;
		} else {
			return false;
		}
	}
	
	public function create($noc) {
		/*
		noc = number of colours (like ($noc=)4 colours)
		*/
		
		$colours = array('green','blue','yellow','red','purple','pink','brown','black','white','gray');
		$this->result = array();
		
		//Used mathemetical "factorial" method to ensure unique/random values/positions
		for($i = 0; $i < $noc; $i++) {
			$rand = array_rand($colours); //Create random key from $colours
			$this->result[$i] = $colours[$rand]; //Place the random key in the result array
			unset($colours[$rand]); //Delete the mf, so it can't be used anymore in the randomizer.
		}
		
		return $this->result;
	}
	
	public function save($id = null,$colours,$uid = null) {
		if ($id === null) {
			//New game
			$q = $con->query("INSERT INTO games (id,colours,uid) VALUES ('','".$colours."','".$uid."')");
		} else {
			//Update current game
			$q = $con->query("UPDATE games SET colours='".$colours."' WHERE id='".$id."'");
		}
		if ($q) {
			return $q->insert_id;
		} else {
			return false;
		}
		return false;
	}
	
	public function colourImg($colour) {
		$fullpath = "https://koenhollander.nl/mastermind/images/".$colour.".png";
		return $fullpath;
	}
}
?>