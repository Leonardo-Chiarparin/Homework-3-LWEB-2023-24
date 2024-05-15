<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<?php
	require_once("./session_control.php");
	require_once("./connection.php");
	
	// NELL'EVENTUALITÀ IN CUI CI SIA UN TENTATIVO DI ACCESSO DA PARTE DI UN DIPENDENTE, BISOGNA REINDERIZZARLO ALLA PAGINA INIZIALE DELL'AREA RISERVATA 
	if($_SESSION["tipo_Utente"]!="C")
		header ("Location: pagina_riservata.php");
	
	// SE IL PULSANTE DI CONFERMA VENISSE PREMUTO, 
	if(isset($_POST["confirm"]))
	{
		// PER CIASCUNO DEI CAMPI D'INTERESSE, È NECESSARIO PROCEDERE CON L'ELIMINAZIONE DI EVENTUALI SPAZI BIANCHI ALL'INZIO (trim(...)) E AL TERMINE (rtrim(...)) DEI VARI VALORI INSERITI
		$_POST["cf"]=trim($_POST["cf"]);
		$_POST["cf"]=rtrim($_POST["cf"]);
		
		$_POST["nome"]=trim($_POST["nome"]);
		$_POST["nome"]=rtrim($_POST["nome"]);
		
		$_POST["cognome"]=trim($_POST["cognome"]);
		$_POST["cognome"]=rtrim($_POST["cognome"]);
		
		$_POST["num_telefono"]=trim($_POST["num_telefono"]);
		$_POST["num_telefono"]=rtrim($_POST["num_telefono"]);
		
		$_POST["email"]=trim($_POST["email"]);
		$_POST["email"]=rtrim($_POST["email"]);
		
		$_POST["old_password"]=trim($_POST["old_password"]);
		$_POST["old_password"]=rtrim($_POST["old_password"]);
		
		$_POST["new_password"]=trim($_POST["new_password"]);
		$_POST["new_password"]=rtrim($_POST["new_password"]);
		
		// GIUNTI A QUESTO PUNTO, È NECESSARIO EFFETTUARE UN ULTERIORE CONTROLLO PER VERIFICARE SE SI È EFFETTIVAMENTE INSERITO QUALCOSA 
		if((strlen($_POST["cf"])==0)||(strlen($_POST["nome"])==0)||(strlen($_POST["cognome"])==0)||(strlen($_POST["num_telefono"])==0)||(strlen($_POST["email"])==0)||(strlen($_POST["old_password"])==0)||(strlen($_POST["new_password"])==0))
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
			// IN PREVISIONE DEL SUCCESSIVO CONTROLLO, PROCEDIAMO CON LA SCRITTURA DEL CODICE FISCALE FACENDO USO DI CARATTERI IN MAIUSCOLO 
			$_POST["cf"]=strtoupper($_POST["cf"]);
			
			// SOLO PER I CAMPI PRIVI DI CONTROLLI INERENTI AD ESPRESSIONI REGOLARI, SI APPLICA UNA SERIE DI FUNZIONI ONDE EVITARE POSSIBILI PROBLEMATCHE DI SICUREZZA
			$_POST["nome"]=stripslashes($_POST["nome"]); // RIMOZIONE DEI BACKSLASH \ ONDE EVITARE LA MySQL Injection
			$_POST["cognome"]=stripslashes($_POST["cognome"]);    // ***
			
			// OPPORTUNA FORMATTAZIONE DEL NOME E DEL COGNOME (SOLO LA PRIMA LETTERA DOVRÀ ESSERE MAIUSCOLA)
			$_POST["nome"]=strtolower($_POST["nome"]);
			$_POST["cognome"]=strtolower($_POST["cognome"]);
			
			$_POST["nome"]=ucfirst($_POST["nome"]);
			$_POST["cognome"]=ucfirst($_POST["cognome"]);
			
			// VERIFICA DEL FORMATO INERENTE AL CODICE FISCALE (16 ELEMENTI TRA CIFRE E CARATTERI)
			if(preg_match("/(([[:alpha:]]|[[:digit:]]){16,16})/",$_POST["cf"],$matches)){
				// SE QUANTO INSERITO NON RISPETTA LA GRAMMATICA INDICATA DALL'ESPRESSIONE REGOLARE, BISOGNA STAMPARE UN MESSAGGIO COSÌ DA NOTIFICARLO ALL'UTENTE
				if($matches[0]!=$_POST["cf"])
				{
					echo "<div class='error_message'>\n
					   <div class='container_message'>\n
					   <div class='container_img'>\n
					   <img src=\"Immagini/exclamation-solid.svg\" alt='Immagine non Disponibile...'>\n
					   </div>\n
					   <div class='message'>\n
					   <p class='err'>ERRORE!</p>\n
					   <p>CODICE FISCALE NON CORRETTO...</p>\n
					   </div>\n
					   </div>\n
					   </div>\n";
				}
				else {					
					// VERIFICA RELATIVA ALLA COMPOSIZIONE DEL RECAPITO TELEFONICO FORNITO DALL'UTENTE 
					if(preg_match("/([[:digit:]]{10,10})/",$_POST["num_telefono"],$matches)){
						if($matches[0]!=$_POST["num_telefono"])
						{
							echo "<div class='error_message'>\n
							   <div class='container_message'>\n
							   <div class='container_img'>\n
							   <img src=\"Immagini/exclamation-solid.svg\" alt='Immagine non Disponibile...'>\n
							   </div>\n
							   <div class='message'>\n
							   <p class='err'>ERRORE!</p>\n
							   <p>RECAPITO TELEFONICO NON VALIDO...</p>\n
							   </div>\n
							   </div>\n
							   </div>\n";
						}
						
						else {
							// CONTROLLO IN MERITO AL FORMATO DELL'INDIRIZZO DI POSTA ELETTRONICA (ESEMPIO: example@example.es, CON IL DOMINIO AVENTE UNA LUNGHEZZA PARI A 2 O A 3) E DELLA PASSWORD (AVENTE UNA LUNGHEZZA DA 5 FINO A 9 ELEMENTI, I QUALI, COME ANCHE RIPORTATO SOTTO, DOVRANNO CONTENERE ALMENO UNA LETTERA MAIUSCOLA)
							// PER LA PASSWORD, SARÀ SUFFICIENTE VALUTARE QUELLA NUOVA
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
								else {
									// OTTENIMENTO DELLA VECCHIA PASSWORD
									$sql="SELECT Password FROM Utenti WHERE ID=".$_SESSION["id_Utente"];
									$result=mysqli_query($conn, $sql);
									
									while($row=mysqli_fetch_array($result))
										$old_password=$row["Password"];
									
									// CONTROLLO INERENTE ALLA CORRISPONDENZA TRA QUANTO INSERITO DALL'UTENTE E CIÒ CHE È PRESENTE ALL'INTERNO DEL DATABASE
									if($_POST["old_password"]==$old_password){
										try {
											// SE LE ANALISI PRECEDENTI NON HANNO EVIDENZIATO ALCUNA SORTA DI PROBLEMATICA, È POSSIBILE EFFETTUARE LA MODIFICA DEI DATI ALL'INTERNO DEL DATABASE
											$sql="UPDATE Utenti SET CF='".$_POST["cf"]."', Nome='".$_POST["nome"]."', Cognome='".$_POST["cognome"]."', Num_Telefono='".$_POST["num_telefono"]."', Email='".$_POST["email"]."', Password='".$_POST["new_password"]."' WHERE ID=".$_SESSION["id_Utente"];
											
											// VERIFICA IN MERITO ALL'ESITO EFFETTIVO DELL'ESECUZIONE DEL PRECEDENTE COMANDO SQL
											if(mysqli_query($conn,$sql)){ 
												// AGGIORNAMENTO DEL CONTENUTO DEGLI ELEMENTI DI SESSIONE INERENTI ALL'UTENTE
												$_SESSION["nome_Utente"]=$_POST["nome"];
												$_SESSION["cognome_Utente"]=$_POST["cognome"];
												
												// INSERIMENTO NELL'ARRAY DI SESSIONE DI UN ELEMENTO UTILE AL FINE DI STAMPARE UN MESSAGGIO PER NOTIFICARE ALL'UTENTE IL SUCCESSO DELL'OPERAZIONE APPENA EFFETTUATA
												$_SESSION["modifica_Effettuata"]=true;
												
												header("Location: pagina_riservata.php");
												
											}
											else {
												throw new mysqli_sql_exception;
											}
										}
										catch(mysqli_sql_exception $e){
											// NEL CASO IN CUI CI SIA LA RIPETIZIONE DI QUALCHE VALORE (CF, EMAIL, PASSWORD) O IL SUPERAMENTO DEL LIMITE DI CARATTERI AMMISSIBILI DAI CAMPI DELLA TABELLA RELAZIONE DEDICATA AGLI UTENTI, 
											echo "<div class='error_message'>\n
												   <div class='container_message'>\n
												   <div class='container_img'>\n
												   <img src=\"Immagini/exclamation-solid.svg\" alt='Immagine non Disponibile...'>\n
												   </div>\n
												   <div class='message'>\n
												   <p class='err'>ERRORE!</p>\n
												   <p>DIMENSIONE ECCEDUTA O RIDONDANZA DEI DATI(CF,EMAIL)...</p>\n
												   </div>\n
												   </div>\n
												   </div>\n";
											
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
											  <p>LA VECCHIA PASSWORD NON COINCIDE CON QUANTO INSERITO...</p>\n
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
					else {
						echo "<div class='error_message'>\n
							   <div class='container_message'>\n
							   <div class='container_img'>\n
							   <img src=\"Immagini/exclamation-solid.svg\" alt='Immagine non Disponibile...'>\n
							   </div>\n
							   <div class='message'>\n
							   <p class='err'>ERRORE!</p>\n
							   <p>RECAPITO TELEFONICO NON VALIDO...</p>\n
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
					   <p>CODICE FISCALE NON CORRETTO...</p>\n
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
	<div class="barra_navigazione">
		<div class="container_logo">
			<img class="logo" src="Immagini/Barra.png" alt="Logo non Disponibile..." />
		</div>
		<div class="container_menu">
			<div class="menu">
			
				<span class="voce_menu">
					<p title="Utente"><?php echo $_SESSION["nome_Utente"]." ".$_SESSION["cognome_Utente"]; ?></p>
				</span>
			
				<span class="voce_menu">
					<a href="account.php" title="Account">Annulla</a>
				</span>
				
				<span class="voce_menu">
					<a href="login.php" title="Pagina di Login">Esci</a>
				</span>
			</div>
		</div>
	</div>
	<div class="container_corpo">
		<div class="container_principale">
			<p class="spazio_link"></p>
		
			<h1 class="saluti">Modifica dell'Utente!</h1>
			
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
        						<div class="titolo_sezione"><p>Profilo Personale (Obbligatorio)</p></div>
							</div>
							<div class="campo">
								<div class="contenuto" style="flex-direction: column;" >
									<div class="item" style="flex-direction:row; border:none;">
										<p class="nome_campo">
											Cod. Fiscale
										</p>
										<p class="dettagli_campo">
											<input type="text" name="cf" value="<?php if(isset($_POST['cf'])) echo $_POST['cf']; else echo '';?>"  />
										</p>	
									</div>
									<div class="item" style="flex-direction:row; border:none;">
										<p class="nome_campo">
											Nome (max. 30 caratteri)
										</p>
										<p class="dettagli_campo">
											<input type="text" name="nome" value="<?php if(isset($_POST['nome'])) echo $_POST['nome']; else echo '';?>"  />
										</p>	
									</div>
									<div class="item" style="flex-direction:row; border:none;">
										<p class="nome_campo">
											Cognome (max. 35 caratteri)
										</p>
										<p class="dettagli_campo">
											<input type="text" name="cognome" value="<?php if(isset($_POST['cognome'])) echo $_POST['cognome']; else echo '';?>" />
										</p>	
									</div>
									<div class="item" style="flex-direction:row; border:none;">
										<p class="nome_campo">
											Recapito Telefonico
										</p>
										<p class="dettagli_campo">
											<input type="text" name="num_telefono" value="<?php if(isset($_POST['num_telefono'])) echo $_POST['num_telefono']; else echo '';?>" />
										</p>	
									</div>
									<p style="font-size: 1em; color: red; width: 100%; margin-left: 1em;"><strong style="text-decoration: underline;">N.B.</strong> Il numero di telefono deve essere formato da una sequenza di 10 cifre</p>
								</div>
							</div>
							<div class="container_sezione">
        						<div class="titolo_sezione"><p>Profilo Utente (Obbligatorio)</p></div>
							</div>
							<div class="campo">
								<div class="contenuto" style="flex-direction: column;" >
									<div class="item" style="flex-direction:row; border:none;">
										<p class="nome_campo">
											Email
										</p>
										<p class="dettagli_campo">
											<input type="text" name="email" value="<?php if(isset($_POST['email'])) echo $_POST['email']; else echo '';?>"  />
										</p>	
									</div>
									<p style="font-size: 1em; color: red; width: 100%; margin-left: 1em;"><strong style="text-decoration: underline;">N.B.</strong> La lunghezza complessiva dell'indirizzo di posta elettronica non pu&ograve; essere superiore a 30 caratteri</p>
									<div class="item" style="flex-direction:row; border:none;">
										<p class="nome_campo">
											Vecchia Password
										</p>
										<p class="dettagli_campo">
											<input type="text" name="old_password" value="<?php if(isset($_POST['old_password'])) echo $_POST['old_password']; else echo '';?>" />
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