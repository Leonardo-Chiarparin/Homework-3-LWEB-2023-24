<?php
	// VARIABILI UTILI PER EFFETTUARE LA CONNESSIONE CON IL DATABASE
    $host="localhost";
    $user="root";
    $pass="AB12cd34E@cPo";
    $db="cantile_chiarparin_homework_3";
    
    $conn = new mysqli($host,$user,$pass,$db);
    if(mysqli_connect_errno()){
		printf("ERRORE DI CONNESSIONE CON IL DATABASE: %s\n", mysqli_connect_error());
		exit();
    }
?>