<?php
	session_start();
	
	// CONTROLLO UTILE PER VALUTARE SE LA SESSIONE È STATA AVVIATA CORRETTAMENTE
    if(!isset($_SESSION["id_Utente"]))
		header ("Location: login.php");
?>