<?php
require "config.php";
if ($_SERVER['REQUEST_METHOD'] == "POST") {
	if (trim($_POST['username']) != NULL && trim($_POST['password']) != NULL) {
		$auth = $user->authenticateUser(false,"",$_POST['username'],$_POST['password'], (isset($_POST['remember'])) ? true : false);
		if ($auth->result == true) { //True = session already created
			Header("Location:index.php");
			exit;
		} else {
			echo $auth->message;
		}
	}
}
if (isset($_COOKIE['REMEMBER'])) {
	$auth = $user->authenticateUser(true,$_COOKIE['REMEMBER']);
	if ($auth->result == true) { //True = session already created
		Header("Location:index.php");
		exit;
	} else {
		echo $auth->message;
	}
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">

    <title>MasterMind | Login</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/signin.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

    <div class="container">

      <form method="POST" class="form-signin">
        <h2 class="form-signin-heading">Inloggen</h2>
        <label for="inputEmail" class="sr-only">Gebruikersnaam</label>
        <input type="text" name="username" id="inputEmail" class="form-control" placeholder="Gebruikersnaam" required autofocus>
        <label for="inputPassword" class="sr-only">Wachtwoord</label>
        <input type="password" name="password" id="inputPassword" class="form-control" placeholder="Wachtwoord" required>
		<div class="checkbox">
			<label>
				<input type="checkbox" name="remember" checked> Onthoud mij
			</label>
		</div>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Inloggen</button><br>
        <a href="register.php" class="btn btn-lg btn-success btn-block">Registreren</a>
      </form>

    </div> <!-- /container -->
  </body>
</html>
