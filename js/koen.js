var getUrlParameter = function getUrlParameter(sParam) {
    var sPageURL = decodeURIComponent(window.location.search.substring(1)),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : sParameterName[1];
        }
    }
};
//DO !-!NOT!-! DELETE ABOVE! 
//USED IN refreshattempts.js!

function deleteGames(uid) { 
	console.log(uid);
	$.get( "deletegames.php?uid="+uid, function( data ) {
		if (data != "false") {
			//if
			//Data is not false 
			console.log("Games deleted\n"+data);
			window.location.href = "index.php";
		} else {
			//error
			console.error("Could'nt delete games\n"+data);
			return false;
		}
	});
}


function gamecreator(id) {
	$.get( "creategame.php?koen&levelid="+id, function( data ) {
		if (data != "false") {
			//if
			//Data is not false 
			//Data is a number (NaN = Not a Number)
			window.location.assign("game.php?hash="+data);
			console.log("Game "+data+" created, loading...");
		} else {
			//error
			console.error("Could'nt load game\n"+data);
			return false;
		}
	});
}
function changeImage(id) {
	/*
	1. black
	2. white
	3. blue
	4. brown
	5. gray
	6. green
	7. pink
	8. purple
	9. red
	10. yellow
	*/
	if (document.getElementById(id).src == "https://koenhollander.nl/mastermind/images/black.png") {
		console.log("Switch black to white: "+id);
		document.getElementById(id).src = "https://koenhollander.nl/mastermind/images/white.png";
		return;
	}
	if (document.getElementById(id).src == "https://koenhollander.nl/mastermind/images/white.png") {
		console.log("Switch white to blue: "+id);
		document.getElementById(id).src = "https://koenhollander.nl/mastermind/images/blue.png";
		return;
	}
	if (document.getElementById(id).src == "https://koenhollander.nl/mastermind/images/blue.png") {
		console.log("Switch blue to brown: "+id);
		document.getElementById(id).src = "https://koenhollander.nl/mastermind/images/brown.png";
		return;
	}
	if (document.getElementById(id).src == "https://koenhollander.nl/mastermind/images/brown.png") {
		console.log("Switch brown to gray: "+id);
		document.getElementById(id).src = "https://koenhollander.nl/mastermind/images/gray.png";
		return;
	}
	if (document.getElementById(id).src == "https://koenhollander.nl/mastermind/images/gray.png") {
		console.log("Switch gray to green: "+id);
		document.getElementById(id).src = "https://koenhollander.nl/mastermind/images/green.png";
		return;
	}
	if (document.getElementById(id).src == "https://koenhollander.nl/mastermind/images/green.png") {
		console.log("Switch green to pink: "+id);
		document.getElementById(id).src = "https://koenhollander.nl/mastermind/images/pink.png";
		return;
	}
	if (document.getElementById(id).src == "https://koenhollander.nl/mastermind/images/pink.png") {
		console.log("Switch pink to purple: "+id);
		document.getElementById(id).src = "https://koenhollander.nl/mastermind/images/purple.png";
		return;
	}
	if (document.getElementById(id).src == "https://koenhollander.nl/mastermind/images/purple.png") {
		console.log("Switch purple to red: "+id);
		document.getElementById(id).src = "https://koenhollander.nl/mastermind/images/red.png";
		return;
	}
	if (document.getElementById(id).src == "https://koenhollander.nl/mastermind/images/red.png") {
		console.log("Switch red to yellow: "+id);
		document.getElementById(id).src = "https://koenhollander.nl/mastermind/images/yellow.png";
		return;
	}
	if (document.getElementById(id).src == "https://koenhollander.nl/mastermind/images/yellow.png") {
		console.log("Switch yellow to black: "+id);
		document.getElementById(id).src = "https://koenhollander.nl/mastermind/images/black.png";
		return;
	}
}
function checkGame(ans) {
	//document.write(getUrlParameter('hash'));
	$.post( "gamecheck.php", { game: ans, hash: getUrlParameter('hash') })
  .done(function( data ) {
	  if (data == "win") {
		  alert("Gefeliciteerd! Je hebt het geraden :)");
		  document.location.href = "index.php";
		  console.log("WIN!");
	  }
    console.log(data);
  });
}

function getAttemptComment(id) {
	$.get( "getattemptcomment.php?id="+id, function( data ) {
		if (data != "false") {
			document.getElementById('summaryp').innerHTML=data;
		} else {
			//error
			console.error("Could'nt delete games\n"+data);
			return false;
		}
	});
}

function checkCode() {
	$.get( "checkcode.php?code="+document.getElementById('thecode').value, function( data ) {
		if (data == "true") {
			document.getElementById('statusmess').innerHTML = "Je code is succesvol geactiveerd! Log opnieuw in.";
			document.getElementById('exit_button').style.display="initial";
			document.getElementById('cancel_button').style.display="none";
			document.getElementById('go_button').style.display="none";
		} else {
			document.getElementById('statusmess').innerHTML = data;
		}
	});
}

function messReset() {
	document.getElementById('statusmess').innerHTML = "Voer een code in en klik op \"Ga verder\"";
	document.getElementById('thecode').value="";
	document.getElementById('exit_button').style.display="none";
	document.getElementById('cancel_button').style.display="initial";
	document.getElementById('go_button').style.display="initial";
}