<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<?php
	require_once("./connection.php");
	
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
		
		$_POST["password"]=trim($_POST["password"]);
		$_POST["password"]=rtrim($_POST["password"]);
		
		// GIUNTI A QUESTO PUNTO, È NECESSARIO EFFETTUARE UN ULTERIORE CONTROLLO PER VERIFICARE SE SI È EFFETTIVAMENTE INSERITO QUALCOSA 
		if((strlen($_POST["cf"])==0)||(strlen($_POST["nome"])==0)||(strlen($_POST["cognome"])==0)||(strlen($_POST["num_telefono"])==0)||(strlen($_POST["email"])==0)||(strlen($_POST["password"])==0))
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
			if(preg_match("/(([[:alpha:]]|[[:digit:]]){16,16})/",$_POST["cf"],$matches))
			{
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
							if (preg_match("/((([[:alpha:]]|(\d)|.|_)+)@([[:alpha:]]+).([[:alpha:]]{2,3}))/",$_POST["email"],$matches_email) && preg_match("/(((?=.*[A-Z])).{5,9}$)/",$_POST["password"],$matches_password)) {
								if($matches_email[0]!=$_POST["email"] || $matches_password[0]!=$_POST["password"])
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
									// VERIFICA DELLE ECCEZIONI CHE LA QUERY PUÒ PRODURRE 
									try {
										// SE LE ANALISI PRECEDENTI NON HANNO EVIDENZIATO ALCUNA SORTA DI PROBLEMATICA, È POSSIBILE EFFETTUARE L'INSERIMENTO DEI DATI ALL'INTERNO DEL DATABASE
										$sql="INSERT INTO Utenti VALUES(NULL,'".$_POST["cf"]."','".$_POST["nome"]."','".$_POST["cognome"]."','".$_POST["num_telefono"]."','".$_POST["email"]."','".$_POST["password"]."','C')";
										
										// VERIFICA IN MERITO ALL'ESITO EFFETTIVO DELL'ESECUZIONE DEL PRECEDENTE COMANDO SQL
										if(mysqli_query($conn,$sql)){
											// PER INDIVIDUARE L'ID, IL NOME E IL COGNOME DELL'UTENTE APPENA MEMORIZZATO, È POSSIBILE FAR USO DI UN'ULTERIORE INTERROGAZIONE TENENDO CONTO DEL SUO CODICE FISCALE (UNICO A LIVELLO DI ENTRY). UN SIMILE RAGIONAMENTO È STATO CONCEPITO E REALIZZATO A FRONTE DI POSSIBILI INSERIMENTI SIMULTANEI DA PARTE DI PIÙ SOGGETTI, COSÌ DA OTTENERE L'ELEMENTO CORRETTO 
											$sql="SELECT ID, Nome, Cognome FROM Utenti WHERE CF='".$_POST["cf"]."'";
											$result=mysqli_query($conn,$sql);
											
											// OTTENIMENTO E SUCCESSIVA MEMORIZZAZIONE DEI DATI RICERCATI
											while($row=mysqli_fetch_array($result)){
												$id_Utente=$row["ID"];
												$nome_Utente=$row["Nome"];
												$cognome_Utente=$row["Cognome"];
											}
											
											// CREAZIONE DELLA SESSIONE CON LA SPECIFICA DEI VARI CAMPI UTILI ALLO SVOLGIMENTO DELLE VARIE OPERAZIONE
											session_start();
											$_SESSION["id_Utente"]=$id_Utente;
											$_SESSION["nome_Utente"]=$nome_Utente;
											$_SESSION["cognome_Utente"]=$cognome_Utente;
											$_SESSION["tipo_Utente"]="C";
											
											header("Location: pagina_riservata.php");
											
										}
										else
										{ 
											throw new mysqli_sql_exception;	   
										}
									}
									catch (mysqli_sql_exception $e){
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
	<?php 
		require_once("./menu_esterno.php");
	?>
	<div class="container_corpo">
		<div class="container_principale">
			<p class="spazio_link"></p>
		
			<h1 class="saluti">Inserimento dell'Utente!</h1>
			
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
							<h3>Dettagli della Registrazione</h3>
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
											Password
										</p>
										<p class="dettagli_campo">
											<input type="text" name="password" value="<?php if(isset($_POST['password'])) echo $_POST['password']; else echo '';?>" />
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