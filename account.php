<?php
require "config.php";
if (@$_SESSION['loggedin'] != TRUE) {
	Header("Location:login.php");
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

    <title>MasterMind</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/koen.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/starter-template.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
	
	
	    <!-- Bootstrap core JavaScript
    ================================================== -->

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
	<script src="js/koen.js"></script>
	
	
  </head>
  <body>
  
	<!-- Modal -->
	<div id="codeModal" class="modal fade" role="dialog">
	  <div class="modal-dialog">

		<!-- Modal content-->
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="modal-title">Activatiescherm</h4>
		  </div>
		  <div class="modal-body">
			<p id="codeContent">
				<em>Activatiecode</em>
				<input type="text" class="form-control" name="thecode" id="thecode" placeholder="5522GA12"><br>
				<b id="statusmess">Voer een code in en klik op "Ga verder"</b>
			</p>
		  </div>
		  <div class="modal-footer">
			<button type="button" id="exit_button" onclick="document.location.href='exit.php'" class="btn btn-success" data-dismiss="modal">Uitloggen</button>
			<button type="button" onclick="messReset();" id="cancel_button" class="btn btn-default" data-dismiss="modal">Annuleren</button>
			<button type="button" class="btn btn-primary" id="go_button" onclick="checkCode();">Ga verder</button>
		  </div>
		</div>

	  </div>
	</div>
	<!-- /Modal -->
	
	
    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">MasterMind</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
                      <ul class="nav navbar-nav">
            <li><a href="index.php">Home</a></li>
            <li class="active"><a href="account.php">Account</a></li>
            <li><a href="exit.php">Uitloggen</a></li>
            <?php if ($_SESSION['role'] == "admin") { ?> 
			<li><a href="admin/index.php">Beheer</a></li>
			<?php } ?>
          </ul>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>

    <div class="container">

      <div class="starter-template">
	  <?php
	  if ($_SESSION['role'] == "user") {
	  ?>
		<h4 class="whitewow">Je account is momenteel nog beperkt, ontgrendel alle spelopties voor slechts &euro;5,00</h4>
        <button onclick="document.location.href='order.php'" class="btn btn-lg btn-block btn-success">Ontgrendel mijn account</button><hr>
	  <?php } ?>
		
		<div class="panel panel-primary">
            <div class="panel-heading">
              <h3 class="panel-title">Accountgegevens</h3>
            </div>
            <div class="panel-body">
				<div class="pull-right"><img height="100" width="100" src="<?php if ($user->getProfileImage($_SESSION['uid'])->message == NULL) { echo "images/profile/default.jpg"; } else { echo $user->getProfileImage($_SESSION['uid'])->message; } ?>"></div><br>
				
				<div class="pull-left">Gebruikersnaam</div>
				<div class="text-center"><?php echo $user->getUsername($_SESSION['uid'])->message; ?></div>
				
				<div class="pull-left">Accountstatus</div>
				<div class="text-center">&nbsp;&nbsp;&nbsp;&nbsp<?php if ($_SESSION['role'] == "user") { echo "Gebruiker"; } if ($_SESSION['role'] == "pro") { echo "Pro-gebruiker"; } if ($_SESSION['role'] == "admin") { echo "Beheerder"; } ?></div>
				
				<p class="pull-left">Accountacties</p>
				<div class="text-center"><a>Wachtwoord aanpassen</a> - <a onclick="messReset();" data-toggle="modal" data-target="#codeModal">Activatiecode invoeren</a></div>
            </div>
          </div>
		  
		  <div class="panel panel-success">
            <div class="panel-heading">
              <h3 class="panel-title">Gewonnen spellen</h3>
            </div>
            <div class="panel-body">
				<?php
					$games = $con->query("SELECT id,hash,create_date FROM games WHERE uid = '".$con->real_escape_string($_SESSION['uid'])."' AND status = 'closed' AND win='yes'");
					if ($games->num_rows == 0) {
						echo "<strong>Je hebt nog niks gewonnen. Dat valt tegen! </strong>";
					} else {
						while ($row = $games->fetch_assoc()) { 
						?>
							<div class="list-group-item">Spel #<?php echo $row['id']; ?> (<?php echo $row['create_date']; ?>)</div>
						<?php
						}
					}
				?>
            </div>
          </div>
		  
		  <div class="panel panel-danger">
            <div class="panel-heading">
              <h3 class="panel-title">Verloren spellen</h3>
            </div>
            <div class="panel-body">
				<?php
					$games = $con->query("SELECT id,hash,create_date FROM games WHERE uid = '".$con->real_escape_string($_SESSION['uid'])."' AND status = 'closed' AND win='no'");
					if ($games->num_rows == 0) {
						echo "<strong>Je hebt nog niks verloren. Lekker bezig!</strong>";
					} else {
						while ($row = $games->fetch_assoc()) { 
						?>
							<div class="list-group-item">Spel #<?php echo $row['id']; ?> (<?php echo $row['create_date']; ?>)</div>
						<?php
						}
					}
				?>
            </div>
          </div>
		  
		  <div class="panel panel-warning">
            <div class="panel-heading">
              <h3 class="panel-title">Top 10 beste spelers</h3>
            </div>
            <div class="panel-body">
				<?php
					$counter = 0;
					$best = $con->query("SELECT username,xp FROM users ORDER BY xp DESC LIMIT 10");
					if ($best->num_rows == 0) {
						echo "<strong>Je hebt nog niks verloren. Lekker bezig!</strong>";
					} else {
						while ($row = $best->fetch_assoc()) { 
						$counter++;
						?>
							<div class="list-group-item">
							<div class="pull-left">#<?php echo $counter; ?></div>
							<div class="text-center"><?php echo $row['username']; ?> (<?php echo $row['xp']; ?>XP)</div>
							</div>
						<?php
						}
					}
				?>
            </div>
          </div>
		  
		  
      </div>

    </div><!-- /.container -->



  </body>
</html>
