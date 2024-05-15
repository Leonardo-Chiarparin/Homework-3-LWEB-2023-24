<?php
	// VARIABILI UTILI PER EFFETTUARE LA CONNESSIONE CON IL DBMS
    $host="localhost";
    $user="root";
    $pass="AB12cd34E@cPo";
    
    $conn = new mysqli($host,$user,$pass);
    if(mysqli_connect_errno()){
		printf("ERRORE DI CONNESSIONE CON IL DBMS: %s\n", mysqli_connect_error());
		exit();
    }
?>