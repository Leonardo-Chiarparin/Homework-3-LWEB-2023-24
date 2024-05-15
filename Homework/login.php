<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<?php
	// INSERIMENTO DEGLI SCRIPT UTILI ALLA RIMOZIONE DI EVENTUALI SESSIONI APERTE E ALLA CONNESSIONE CON LA BASE DI DATI 
	require_once("./session_destruction.php");
	require_once("./connection.php");
	
	// ELIMINAZIONE DEL COOKIE INERENTE ALLA DISCIPLINA SCELTA
	if(isset($_COOKIE["Disciplina_Scelta"]))
		setcookie("Disciplina_Scelta", " ", time()-60);
		
	
	if(isset($_POST["confirm"])){
		
		// PER CIASCUNO DEI CAMPI D'INTERESSE, È NECESSARIO PROCEDERE CON L'ELIMINAZIONE DI EVENTUALI SPAZI BIANCHI ALL'INZIO (trim(...)) E AL TERMINE (rtrim(...)) DEI VARI VALORI INSERITI
		$_POST["email"]=trim($_POST["email"]);
		$_POST["email"]=rtrim($_POST["email"]);
		
		$_POST["password"]=trim($_POST["password"]);
		$_POST["password"]=rtrim($_POST["password"]);
		
		$email=stripslashes($_POST["email"]); // RIMOZIONE DEI BACKSLASH \ ONDE EVITARE LA MySQL Injection
		$password=stripslashes($_POST["password"]);    // ***
		$email=mysqli_real_escape_string($conn, $email); // AGGIUNTA DELLA SEQUENZA DI ESCAPE AI CARATTERI SPECIALI COSÌ CHE LA STRINGA SIA USATA IN MODO SICURO NEI COMANDI mysqli_query 
		$password=mysqli_real_escape_string($conn, $password); // ***
		
		// CONTROLLO PER PERMETTERE LA PROFILAZIONE (INDIVIDUAZIONE DELL'ID, UTILE PER LE FUTURE OPERAZIONI, IL NOME, IL COGNOME E IL TIPO DI UTENTE IN MODO TALE DA REINDIRIZZARLO CORRETTAMENTE)
		$sql="SELECT ID, Nome, Cognome, Tipo_Utente FROM Utenti WHERE Email='$email' AND Password='$password'";
		$result=mysqli_query($conn, $sql);
		$conta=mysqli_num_rows($result);
		if($conta==1){ // DEVE ESSERE PRESENTE UNA SOLA CORRISPONDENZA
		   while($row = mysqli_fetch_array($result)){ // MALGRADO TALE COSTRUTTO SI USI QUALORA CI SIANO PIÙ "RIGHE" È POSSIBILE ADOTTARLO ANCHE NEL CASO DI SINGOLE "ISTANZE"
				$id=$row["ID"];
				$nome=$row["Nome"];
				$cognome=$row["Cognome"];
				$tipo=$row["Tipo_Utente"];
			}
			// AVVIO DELLA SESSIONE DI LAVORO, CON PREDISPOSIZIONE DELLE VARIABILI (INIZIALI) UTILI PER I VARI SCOPI
			session_start();
			$_SESSION["id_Utente"]=$id;
			$_SESSION["nome_Utente"]=$nome;
			$_SESSION["cognome_Utente"]=$cognome;
			$_SESSION["tipo_Utente"]=$tipo;
			
			// CONSEGUENTE REINDIRIZZAMENTO DELL'UTENTE ALLA PAGINA PRINCIPALE DELL'AREA RISERVATA A QUEST'ULTIMO
			header("Location: pagina_riservata.php");
			
		}
		else
		{
			// STAMPA DEL RELATIVO MESSAGGIO D'ERRORE
			echo "<div class='error_message'>\n
				  <div class='container_message'>\n
				  <div class='container_img'>\n
                  <img src=\"Immagini/exclamation-solid.svg\" alt='Immagine non Disponibile...' />\n
				  </div>\n
                  <div class='message'>\n
                  <p class='err'>ERRORE!</p>\n
                  <p>USERNAME E/O PASSWORD ERRATI...</p>\n
                  </div>\n
                  </div>\n
                  </div>\n";	
		}	
	}        	        
?>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>CSS: Campo Sportivo dei Sogni</title>
	<link rel="icon" href="Immagini/Logo.png" />
	<link rel="stylesheet" href="CSS/style_login.css" type="text/css" />
</head>
<body>
	<div class="container_login">
		<div class="login">
			<div class="barra_navigazione">
				<div class="container_logo">
					<a href="index.php">
						<img class="logo" src="Immagini/Barra.png" title="Pagina Iniziale" alt="Logo non Disponibile..." />
					</a>
				</div>
			</div>
			<!--NELLE COMPONENTI DEDICATE AL CONTENIMENTO DELL'INPUT FORNITO DALL'UTENTE, SI È DECISO DI PRESERVARE QUANTO SPECIFICATO ANCHE IN PRESENZA DI EVENTUALI ERRORI-->
			<form class="login_form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
 				<div class="main_content">
 					<div class="row" style="margin-top: 1.5%;">
 						<div class="item">
							<div class="container_immagine" title="Email">
								<img src="Immagini/envelope-solid.svg" alt="Immagine non Disponibile..." />
							</div>
							<input type="text" name="email" value="<?php if(isset($_POST['email'])) echo $_POST['email']; else echo '';?>" />
						</div>
 					</div>
					<div class="row" style="margin-top: 2.5%;">
 						<div class="item">
							<div class="container_immagine" title="Password">
								<img src="Immagini/lock-solid.svg" alt="Immagine non Disponibile..." />
							</div>
							<input type="password" name="password" value="<?php if(isset($_POST['password'])) echo $_POST['password']; else echo '';?>" />
						</div>
 					</div>
					<div class="row">
 						<div class="paragrafo">
							<p>
								<a href="resume_password.php">Password Dimenticata?</a>
							</p>
						</div>
 					</div>
 					<div class="row_button" style="margin-top: 1%;">
						<button type="submit" class="confirm" name="confirm">Conferma!</button>
 					</div>
					<div class="row" style="padding-bottom: 1%;border-bottom-style: solid; border-bottom-width: 1px; border-color: rgb(157,212,246)">
 						<div class="paragrafo" style="margin-top: 1%;">
							<p>
								Non hai un account?
								<a href="registrazione.php">Registrati!</a>
							</p>
						</div>
 					</div>
					<div class="row">
						<div class="footer">
							<p>
								Ettore Cantile e Leonardo Chiarparin, Linguaggi per il Web  a.a. 2023-2024
							</p>
						</div>
					</div>
 				</div>	
        	</form>
		</div>
	</div>
</body>
</html>