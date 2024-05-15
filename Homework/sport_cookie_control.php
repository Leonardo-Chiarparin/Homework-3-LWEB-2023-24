<?php
	// CONTROLLO INERENTE ALLA PRESENZA DEL COOKIE RELATIVO ALLO SPORT
    if(!isset($_COOKIE["Disciplina_Scelta"]))
		header("Location: sport_selection.php");
?>