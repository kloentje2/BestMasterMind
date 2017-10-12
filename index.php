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
	<div id="startModal" class="modal fade" role="dialog">
	  <div class="modal-dialog">

		<!-- Modal content-->
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="modal-title">Level</h4>
		  </div>
		  <div class="modal-body">
			<p>
			<?php 
		    $levels = $con->query("SELECT id,level,pro FROM levels");
			while ($row = $levels->fetch_assoc()) {	
		    ?>
			<button <?php if ($row['pro'] == "1" AND $_SESSION['role'] != "pro" AND $_SESSION['role'] != "admin") { echo "disabled"; } else { echo ""; }?> onclick="javascript:gamecreator(<?php echo $row['id']; ?>);" id="level<?php echo $row['level']; ?>" <?php if ($row['pro'] == "0") { ?>class="btn btn-lg btn-block btn-primary"<?php } else { ?>class="btn btn-lg btn-block btn-primary"<?php } ?>>Level <?php echo $row['level']; ?></button>
			<?php } ?>
			</p>
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
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
            <li class="active"><a href="index.php">Home</a></li>
            <li><a href="account.php">Account</a></li>
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
        <button data-toggle="modal" data-target="#startModal" class="btn btn-lg btn-block btn-success">Nieuw spel!</button><hr>
		<div class="panel panel-primary">
            <div class="panel-heading">
              <h3 class="panel-title">Openstaand<div class="pull-right"><a class="whitea" onclick="deleteGames(<?php echo $_SESSION['uid']; ?>)"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a></div></h3>
            </div>
            <div class="panel-body">
              <div class="list-group">
            	<?php
				$games = $con->query("SELECT id,hash,create_date FROM games WHERE (uid = '".$con->real_escape_string($_SESSION['uid'])."' AND status = 'open')");
				if ($games->num_rows == 0) {
					echo "<strong>Je hebt geen openstaande spellen. Waar wacht je nog op?</strong>";
				} else {
					while ($row = $games->fetch_assoc()) { 
					?>
						<a href="<?php echo "game.php?hash=".$row['hash']; ?>" class="list-group-item">Speel #<?php echo $row['id']; ?> (<?php echo $row['create_date']; ?>)</a>
					<?php
					}
				}
				?>
          </div>
            </div>
          </div>
      </div>

    </div><!-- /.container -->



  </body>
</html>
