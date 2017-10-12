<?php
Class User {
	
	private $con;
	public $message;
	public $result;
	
	public function __construct($con) {
		$this->con = $con;
	}
	
	public function authenticateUser($remember = false, $token = "", $username = "", $password = "",$generate = false) {
		if ($remember == false) {
			if (trim($username) == "" || trim($username) == NULL) {
				$this->message = "Invalid username";
				$this->result = false;
				return $this;
			}
		}
		if ($remember == false) {
			if (trim($password) == "" || trim($password) == NULL) {
				$this->message = "Invalid password";
				$this->result = false;
				return $this;
			}
		}
		
		if ($remember == true && $username == "" && $password == "") { //Auth via remembertoken, remembertoken = username
			$check = $this->con->query("SELECT remember.id,users.username,users.role,users.id as uid FROM remember INNER JOIN users ON remember.userid = users.id WHERE remember.token = '".$this->con->real_escape_string($token)."'");
			if ($check->num_rows == 1) { //Found! :D
				$r = $check->fetch_assoc();
				$_SESSION['uid'] = $r['uid']; //UserID
				$_SESSION['role'] = $r['role']; //UserROle
				$_SESSION['loggedin'] = true; //Check if true
			
				$this->message = "success";
				$this->result = true;
				return $this;
			} else { // 0rows
				/*
				$r = $check->fetch_assoc();
				$_SESSION['uid'] = $r['uid']; //UserID
				$_SESSION['role'] = $r['role']; //UserROle
				$_SESSION['loggedin'] = true; //Check if true
				*/
				setcookie("REMEMBER","",time()-3600);
				$this->message = "Invalid remember token, removed from cookies";
				$this->result = false;
				return $this;
			}
		}
		
		$username = $this->con->real_escape_string($username);
		
		$pq = $this->con->query("SELECT id,password,role FROM users WHERE username='".$username."'"); //Get userid,password,role using username
		
		if ($pq->num_rows != 1) {
			$this->message = "Invalid username";
			$this->result = false;
			return $this;
		}
		
		$p = $pq->fetch_assoc();
		
		if (password_verify($password,$p['password'])) { //Password = valid
		
			$_SESSION['uid'] = $p['id']; //UserID
			$_SESSION['role'] = $p['role']; //UserRole
			$_SESSION['loggedin'] = true; //Check if true
			
			if ($generate == "true") {
				$this->newRememberToken($_SESSION['uid']);
			}
			
			$this->message = "success";
			$this->result = true;
			return $this;
		} else { //Password = invalid
			$this->message = "Invalid password";
			$this->result = false;
			return $this;
		}
	}
	
	public function createUser($username,$password,$role = "") {
		/*
		username = username of the user
		password = password of the user
		role = role of the user like user,pro,admin
		*/

		if (trim($role) != "user" && trim($role) != "pro" && trim($role) != "admin") {
			$this->message = "Invalid role";
			$this->result = false;
			return $this;
		}

		if (trim($username) == "" || trim($username) == NULL) {
			$this->message = "Invalid username";
			$this->result = false;
			return $this;
		}
		
		if (trim($password) == "" || trim($password) == NULL) {
			$this->message = "Invalid password";
			$this->result = false;
			return $this;
		}
		
		$username = $this->con->real_escape_string($username);
		$password = $this->con->real_escape_string(password_hash($password,PASSWORD_BCRYPT));
		$role = $this->con->real_escape_string($role);
		
		$add = $this->con->query("INSERT INTO users (username,password,role) VALUES ('".$username."','".$password."','".$role."')");
		
		if ($add) {
			$this->message = "Added";
			$this->result = true;
			return $this;
		} else {
			$this->message = $this->con->error;
			$this->result = false;
			return $this;
		}
	}
	
	public function getUsername($uid) {
		if (trim((int)$uid) == "" || trim((int)$uid) == NULL) {
			$this->message = "Invalid UserID";
			$this->result = false;
			return $this;
		}
		$get = $this->con->query("SELECT username FROM users WHERE id = '".$this->con->real_escape_string($uid)."'");
		if ($get) {
			$row = $get->fetch_assoc();
			$this->message = htmlspecialchars($row['username']);
			$this->result = true;
			return $this;
		} else {
			$this->message = $this->con->error;
			$this->result = false;
			return $this;
		}
	}
	
	public function getProfileImage($uid) {
		if (trim((int)$uid) == "" || trim((int)$uid) == NULL) {
			$this->message = "Invalid UserID";
			$this->result = false;
			return $this;
		}
		$get = $this->con->query("SELECT image FROM users WHERE id = '".$this->con->real_escape_string($uid)."'");
		if ($get) {
			$row = $get->fetch_assoc();
			$this->message = htmlspecialchars($row['image']);
			$this->result = true;
			return $this;
		} else {
			$this->message = $this->con->error;
			$this->result = false;
			return $this;
		}
	}
	
	private function newRememberToken($uid) {
		$token = md5(date("Y-m-d H:i:s").$uid.rand(1,999)); //Create a lot of rubbish
		$cookie = setcookie("REMEMBER",$token,time()+2592000,null,null,true,true); //time=1month in hours
		$add = $this->con->query("INSERT INTO remember (userid,token,ip) VALUES ('".$this->con->real_escape_string($uid)."','".$this->con->real_escape_string($token)."','".$this->con->real_escape_string($_SERVER['REMOTE_ADDR'])."')");
		
		if ($cookie) { //Cookie true?
			if ($add) { //Token added?
				$this->message = $token;
				$this->result = true;
				return $this;
			} else { //Token not added?
				$this->message = $this->con->error;
				$this->result = false;
				return $this;
			}
		} else { //Cookie false?
			$this->message = "Could'nt create cookie";
			$this->result = false;
			return $this;
		}
	}
	
	public function changePassword($uid,$newpassword) {
		
	}
	
	public function changeRole($uid,$role) {
		
	}
	
	public function blockUser() {
		
	}
	
	public function getRole() {
		
	}
}
?>