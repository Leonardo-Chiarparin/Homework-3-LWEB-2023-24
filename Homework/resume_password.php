<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<?php
	require_once("./connection.php");
	
	// SE IL PULSANTE DI CONFERMA VENISSE PREMUTO, 
	if(isset($_POST["confirm"]))
	{
		// PER CIASCUNO DEI CAMPI D'INTERESSE, È NECESSARIO PROCEDERE CON L'ELIMINAZIONE DI EVENTUALI SPAZI BIANCHI ALL'INZIO (trim(...)) E AL TERMINE (rtrim(...)) DEI VARI VALORI INSERITI
		
		$_POST["email"]=trim($_POST["email"]);
		$_POST["email"]=rtrim($_POST["email"]);
		
		$_POST["new_password"]=trim($_POST["new_password"]);
		$_POST["new_password"]=rtrim($_POST["new_password"]);
		
		$_POST["password_con"]=trim($_POST["password_con"]);
		$_POST["password_con"]=rtrim($_POST["password_con"]);
		
		// GIUNTI A QUESTO PUNTO, È NECESSARIO EFFETTUARE UN ULTERIORE CONTROLLO PER VERIFICARE SE SI È EFFETTIVAMENTE INSERITO QUALCOSA 
		if((strlen($_POST["email"])==0)||(strlen($_POST["new_password"])==0)||(strlen($_POST["password_con"])==0))
		{
			// STAMPA DEL RELATIVO MESSAGGIO D'ERRORE
			echo "<div class='error_message'>\n
				   <div class='container_message'>\n
				   <div class='container_img'>\n
                   <img src=\"Immagini/exclamation-solid.svg\" alt='Immagine non Disponibile...'>\n
				   </div>\n
                   <div class='message'>\n
                   <p class='err'>ERRORE!</p>\n
                   <p>COMPILARE TUTTI I CAMPI OBBLIGATORI...</p>\n
                   </div>\n
                   </div>\n
                   </div>\n";
		}
		else
		{
			// CONTROLLO IN MERITO AL FORMATO DELL'INDIRIZZO DI POSTA ELETTRONICA (ESEMPIO: example@example.es, CON IL DOMINIO AVENTE UNA LUNGHEZZA PARI A 2 O A 3) E DELLA PASSWORD (AVENTE UNA LUNGHEZZA DA 5 FINO A 9 ELEMENTI, I QUALI, COME ANCHE RIPORTATO SOTTO, DOVRANNO CONTENERE ALMENO UNA LETTERA MAIUSCOLA)
			if (preg_match("/((([[:alpha:]]|(\d)|.|_)+)@([[:alpha:]]+).([[:alpha:]]{2,3}))/",$_POST["email"],$matches_email) && preg_match("/(((?=.*[A-Z])).{5,9}$)/",$_POST["new_password"],$matches_new_password)) {
				if($matches_email[0]!=$_POST["email"] || $matches_new_password[0]!=$_POST["new_password"])
				{
					echo "<div class='error_message'>\n
						  <div class='container_message'>\n
						  <div class='container_img'>\n
						  <img src=\"Immagini/exclamation-solid.svg\" alt='Immagine non Disponibile...'>\n
						  </div>\n
						  <div class='message'>\n
						  <p class='err'>ERRORE!</p>\n
						  <p>FORMATO EMAIL E/O PASSWORD NON RISPETTATI...</p>\n
						  </div>\n
						  </div>\n
						  </div>\n";
				}
				else
				{
					if($_POST["new_password"]==$_POST["password_con"])
					{
						try {
							// SE LE ANALISI PRECEDENTI NON HANNO EVIDENZIATO ALCUNA SORTA DI PROBLEMATICA, È POSSIBILE EFFETTUARE L'INSERIMENTO DEI DATI ALL'INTERNO DEL DATABASE
							$sql="UPDATE Utenti SET Password='".$_POST["new_password"]."' WHERE Email='".$_POST["email"]."'";
							
							// VERIFICA IN MERITO ALL'ESITO EFFETTIVO DELL'ESECUZIONE DEL PRECEDENTE COMANDO SQL
							if(mysqli_query($conn,$sql)){
								// PER INDIVIDUARE L'ID, IL NOME E IL COGNOME DELL'UTENTE APPENA MEMORIZZATO, È POSSIBILE FAR USO DI UN'ULTERIORE INTERROGAZIONE TENENDO CONTO DEL SUO CODICE FISCALE (UNICO A LIVELLO DI ENTRY). UN SIMILE RAGIONAMENTO È STATO CONCEPITO E REALIZZATO A FRONTE DI POSSIBILI INSERIMENTI SIMULTANEI DA PARTE DI PIÙ SOGGETTI, COSÌ DA OTTENERE L'ELEMENTO CORRETTO 
								$sql="SELECT ID, Nome, Cognome, Tipo_Utente FROM Utenti WHERE Email='".$_POST["email"]."'";
								$result=mysqli_query($conn,$sql);
								
								// OTTENIMENTO E SUCCESSIVA MEMORIZZAZIONE DEI DATI RICERCATI
								while($row=mysqli_fetch_array($result)){
									$id_Utente=$row["ID"];
									$nome_Utente=$row["Nome"];
									$cognome_Utente=$row["Cognome"];
									$tipo_Utente=$row["Tipo_Utente"];
								}
								
								// CREAZIONE DELLA SESSIONE CON LA SPECIFICA DEI VARI CAMPI UTILI ALLO SVOLGIMENTO DELLE VARIE OPERAZIONE
								session_start();
								$_SESSION["id_Utente"]=$id_Utente;
								$_SESSION["nome_Utente"]=$nome_Utente;
								$_SESSION["cognome_Utente"]=$cognome_Utente;
								$_SESSION["tipo_Utente"]=$tipo_Utente;
								$_SESSION["modifica_Effettuata"]=true;
								
								header("Location: pagina_riservata.php");
							}
							else
							{
								throw new mysqli_sql_exception;
							}
						}
						catch (mysqli_sql_exception $e){
							echo "<div class='error_message'>\n
									  <div class='container_message'>\n
									  <div class='container_img'>\n
									  <img src=\"Immagini/exclamation-solid.svg\" alt='Immagine non Disponibile...'>\n
									  </div>\n
									  <div class='message'>\n
									  <p class='err'>ERRORE!</p>\n
									  <p>DIMENSIONE DEI CAMPI ECCEDUTA, RIDONDANZA DEI DATI(EMAIL) O EMAIL ERRATA...</p>\n
									  </div>\n
									  </div>\n
									  </div>\n";
						}
					}
					else
					{
					    echo "<div class='error_message'>\n
					   <div class='container_message'>\n
					   <div class='container_img'>\n
					   <img src=\"Immagini/exclamation-solid.svg\" alt='Immagine non Disponibile...'>\n
					   </div>\n
					   <div class='message'>\n
					   <p class='err'>ERRORE!</p>\n
					   <p>LE PASSWORD INSERITE NON COMBACIANO...</p>\n
					   </div>\n
					   </div>\n
					   </div>\n";
					}
				}	
			}
			else {
				echo "<div class='error_message'>\n
					  <div class='container_message'>\n
					  <div class='container_img'>\n
					  <img src=\"Immagini/exclamation-solid.svg\" alt='Immagine non Disponibile...'>\n
					  </div>\n
					  <div class='message'>\n
					  <p class='err'>ERRORE!</p>\n
					  <p>FORMATO EMAIL E/O PASSWORD NON RISPETTATI...</p>\n
					  </div>\n
					  </div>\n
					  </div>\n";
			}
		}
	}
?>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>CSS: Campo Sportivo dei Sogni</title>
	<link rel="icon" href="Immagini/Logo.png" />
	<link rel="stylesheet" href="CSS/style_form.css" type="text/css" />
</head>
<body>
	<?php 
		require_once("./menu_esterno.php");
	?>
	<div class="container_corpo">
		<div class="container_principale">
			<p class="spazio_link"></p>
		
			<h1 class="saluti">Modifica della Password!</h1>
			
			<!--NELLE COMPONENTI DEDICATE AL CONTENIMENTO DELL'INPUT FORNITO DALL'UTENTE, SI È DECISO DI PRESERVARE QUANTO SPECIFICATO ANCHE IN PRESENZA DI EVENTUALI ERRORI-->
			<form class="container_form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
				<div class="form">
					<div class="intestazione">
						<h2>
							Compilare i seguenti campi con le informazioni richieste
						</h2>
					</div>
					<div class="container_elenco_campi">
						<div class="intestazione_elenco_campi">
							<h3>Dettagli dell'Aggiornamento</h3>
						</div>
						<div class="corpo_elenco_campi">
							<div class="container_sezione">
        						<div class="titolo_sezione"><p>Profilo Utente</p></div>
							</div>
							<div class="campo">
								<div class="contenuto" style="flex-direction: column;" >
									<div class="item" style="flex-direction:row; border:none;">
										<p class="nome_campo">
											Email
										</p>
										<p class="dettagli_campo">
											<input type="text" name="email" value="<?php if(isset($_POST['email'])) echo $_POST['email']; else echo '';?>" />
										</p>	
									</div>
									<div class="item" style="flex-direction:row; border:none;">
										<p class="nome_campo">
											Nuova Password
										</p>
										<p class="dettagli_campo">
											<input type="text" name="new_password" value="<?php if(isset($_POST['new_password'])) echo $_POST['new_password']; else echo '';?>" />
										</p>	
									</div>
									<div class="item" style="flex-direction:row; border:none;">
										<p class="nome_campo">
											 Confermare Nuova Password
										</p>
										<p class="dettagli_campo">
											<input type="text" name="password_con" value="<?php if(isset($_POST['password_con'])) echo $_POST['password_con']; else echo '';?>" />
										</p>	
									</div>
									<p style="font-size: 1em; color: red; width: 100%; margin-left: 1em;"><strong style="text-decoration: underline;">N.B.</strong> La parola chiave dovr&agrave; contenere tra i 5 e i 9 elementi, di cui (almeno) una lettera maiuscola</p>
								</div>
							</div>
							<div class="container_button">
								<button type="submit" name="confirm" class="confirm">Conferma!</button>
							</div>  
						</div>
					</div>
				</div>
			</form>
			<div class="blank_space"></div>
		</div>
		<div class="footer">
			<p>
				Ettore Cantile e Leonardo Chiarparin, Linguaggi per il Web  a.a. 2023-2024
			</p>
		</div>
	</div>
</body>
</html>