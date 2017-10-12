function refreshAttempts() {
	$.get( "getallattempts.php?koen&hash="+getUrlParameter('hash'), function( data ) {
		if (data != "false") {
			if (data == "") {
				document.getElementById('attempts').innerHTML = "<br><strong>Waar wacht je nog op? Probeer!</strong><br><br>";
			} else {
			document.getElementById('attempts').innerHTML = data;
			console.log("Game "+data+" created, loading...");
			}
		} else {
			//error
			console.error("Could'nt load game\n"+data);
			return false;
		}
	});
}

function checkWin() {
	console.log("Checking for win...");
	$.get("checkwin.php?hash="+getUrlParameter('hash'), function( data ) {
		console.log(data);
		if (data == "true") {
			//Win!!!!!!!!!!
			console.log("Win logged, return triggered");
			$("#winModal").modal();
			setTimeout(
			function () {
				document.location.href="index.php";
			}, 5000);
		} else {
			//Lose or error (I don't expect an error, because I am the best developer in the whole wide world!)
			console.error(data);
			return;
		}
	});
}

function checkLose() {
	$.get("checklose.php?hash="+getUrlParameter('hash'), function( data ) { //Lose? close game 
		console.log(data);
		if (data == "true") {
			//Lose!!!!!!!!!!!!
			console.log("Lose logged, return triggered");
			$("#loseModal").modal();
			setTimeout(
			function () {
				document.location.href="index.php";
			}, 5000);
		} else {
			//Nolose or error (I don't expect an error, because I am the best developer in the whole wide world!)
			return;
		}
	});
}

setInterval(refreshAttempts, 1000);
setInterval(checkWin, 1000);
setInterval(checkLose, 1000);