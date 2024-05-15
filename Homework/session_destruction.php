<?php
	// LA SESSIONE RIMASTA APERTA A SEGUITO DELLA DISCONNESSIONE DEVE ESSERE RIMOSSA
    session_start();
    $_SESSION = array();
    session_destroy();
?>