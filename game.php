<?php
require "config.php";

if (@$_SESSION['loggedin'] != TRUE) {
	Header("Location:login.php");
}

$sel = $con->query("SELECT * FROM games WHERE hash = '".$con->real_escape_string($_GET['hash'])."' AND status = 'open'");
if ($sel->num_rows != 1) {
	Header("Location:index.php");
	exit;
}
$game_noc = $sel->fetch_assoc();
$game_colours = json_decode($game_noc['colours']);
$noc = count($game_colours);
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
	<script src="js/refreshattempts.js"></script>
  </head>

  <body onload="refreshAttempts();">
  	
	<!-- Modal -->
	<div id="commentModal" class="modal fade" role="dialog">
	  <div class="modal-dialog">

		<!-- Modal content-->
		<div class="modal-content">
		  <div class="modal-body">
			<p id="summaryp">
				
			</p>
			<button type="button" class="btn btn-default" data-dismiss="modal">Ga verder</button>
		  </div>
		</div>

	  </div>
	</div>
	<!-- /Modal -->
	
		<!-- Modal -->
	<div id="winModal" class="modal fade" role="dialog">
	  <div class="modal-dialog">

		<!-- Modal content-->
		<div class="modal-content">
		  <div class="modal-body">
			<p id="summaryp">
				Gefeliciteerd, je hebt gewonnen!
			</p>
			<button type="button" class="btn btn-default" onclick="document.location.href='index.php'">Ga verder!</button>
		  </div>
		</div>

	  </div>
	</div>
	<!-- /Modal -->
	
			<!-- Modal -->
	<div id="loseModal" class="modal fade" role="dialog">
	  <div class="modal-dialog">

		<!-- Modal content-->
		<div class="modal-content">
		  <div class="modal-body">
			<p id="summaryp">
				Je hebt helaas verloren. Kon dat niet beter?
			</p>
			<button type="button" class="btn btn-default" onclick="document.location.href='index.php'">Ga terug</button>
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
            <li><a href="index.php">Home</a></li>
            <li class="active"><a href="#">Game</a></li>
            <li><a href="account.php">Account</a></li>
			<li><a href="exit.php">Uitloggen</a></li>
            <?php if ($_SESSION['role'] == "admin") { ?> 
			<li><a href="admin/index.php">Beheer</a></li>
			<?php } ?>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>

    <div class="container">

      <div class="starter-template">
	  <!--Guess-->
	  <div class="text-center">
	  <button onclick="checkGame(document.getElementById('guess').innerHTML);" class="text-center btn btn-lg btn-primary">Raden!</button><br><br>
		  <div id="guess">
			  <?php
				  for ($i = 0; $i < $noc; $i++) {
				  ?>
				  <img onclick="changeImage('<?php echo "i".$i; ?>');" id="<?php echo "i".$i; ?>" src="https://koenhollander.nl/mastermind/images/gray.png">
				  <?php
				  }
			  ?>
		  </div>
	  <hr>
	  </div>
	  <!--`/Guess-->

	  <div class="panel panel-primary">
		<div class="panel-heading">
		  <h3 class="panel-title">Pogingen</h3>
		</div>
			<div id="attempts" class="list-group">
				<!--<a href="#" class="list-group-item">Dapibus ac facilisis in</a>-->
		</div>
      </div>
          </div>

    </div><!-- /.container -->



  </body>
</html>
