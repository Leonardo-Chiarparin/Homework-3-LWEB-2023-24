<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<?php
	require_once("./session_control.php");
	require_once("./connection.php");
	
	// NELL'EVENTUALITÀ IN CUI CI SIA UN TENTATIVO DI ACCESSO DA PARTE DI UN DIPENDENTE, BISOGNA REINDERIZZARLO ALLA PAGINA INIZIALE DELL'AREA RISERVATA 
	if($_SESSION["tipo_Utente"]!="C")
		header ("Location: pagina_riservata.php");
	
	// INTERROGAZIONE ALLA BASE DI DATI AL FINE DI CREARE DINAMICAMENTE LA PAGINA IN FUNZIONE DELL'UTENTE CHE HA EFFETTUATO L'ACCESSO (LA PASSWORD, ESSENDO MD5 UNA FUNZIONE DI CODIFICA UNIDIREZIONALE, NON VIENE PRELEVATA, BENSÌ VENGONO STAMPATI 9 PUNTINI)
	$sql="SELECT CF, Num_Telefono, Email, Password FROM Utenti WHERE ID=".$_SESSION["id_Utente"]; 
	$result=mysqli_query($conn, $sql);
	
	// OTTENIMENTO DELLE INFORMAZIONI RICHIESTE
	while($row=mysqli_fetch_array($result)){
		$cf=$row["CF"];
		$num_telefono=$row["Num_Telefono"];
		$email=$row["Email"];
		$password=$row["Password"];
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
		require_once("./menu_riservato.php");
	?>
	<div class="container_corpo">
		<div class="container_principale">
			<p class="spazio_link"></p>
		
			<h1 class="saluti">Riepilogo dell'Utente!</h1>
			
			<div class="container_form">
				<div class="form">
					<div class="intestazione">
						<h2>
							Premere i pulsanti riportati in basso per aggiornarne i campi
						</h2>
					</div>
					<div class="container_elenco_campi">
						<div class="intestazione_elenco_campi">
							<h3>Dettagli del Cliente</h3>
						</div>
						<div class="corpo_elenco_campi">
							<div class="container_sezione">
        						<div class="titolo_sezione"><p>Scheda Soggetto</p></div>
							</div>
							<div class="campo">
								<div class="contenuto" style="flex-direction: column;" >
									<div class="item" style="flex-direction:row; border:none;">
										<div class="header_item">
											<div class="header_cliente">
												<div class="header_logo"><div class="circle"><img alt="Immagine non Disponibile..." src="Immagini/user-solid.svg" /></div></div>
												<div class="header_text">
													<p><strong><?php echo $_SESSION["nome_Utente"]." ".$_SESSION["cognome_Utente"]; ?></strong></p>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="container_sezione">
        						<div class="titolo_sezione"><p>Profilo Personale</p></div>
							</div>
							<div class="campo">
								<div class="contenuto" style="flex-direction: column;" >
									<div class="item" style="flex-direction:row; border:none;">
										<p class="nome_campo">
											Cod. Fiscale
										</p>
										<p class="dettagli_campo">
											<input type="text" name="cf" value="<?php echo $cf; ?>" disabled="disabled" />
										</p>	
									</div>
									<div class="item" style="flex-direction:row; border:none;">
										<p class="nome_campo">
											Nome
										</p>
										<p class="dettagli_campo">
											<input type="text" name="nome" value="<?php echo $_SESSION["nome_Utente"]; ?>" disabled="disabled" />
										</p>	
									</div>
									<div class="item" style="flex-direction:row; border:none;">
										<p class="nome_campo">
											Cognome
										</p>
										<p class="dettagli_campo">
											<input type="text" name="cognome" value="<?php echo $_SESSION["cognome_Utente"]; ?>" disabled="disabled" />
										</p>	
									</div>
									<div class="item" style="flex-direction:row; border:none;">
										<p class="nome_campo">
											Recapito Telefonico
										</p>
										<p class="dettagli_campo">
											<input type="text" name="num_telefono" value="<?php echo $num_telefono; ?>" disabled="disabled" />
										</p>	
									</div>
								</div>
							</div>
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
											<input type="text" name="email" value="<?php echo $email; ?>" disabled="disabled" />
										</p>	
									</div>
									<div class="item" style="flex-direction:row; border:none;">
										<p class="nome_campo">
											Password
										</p>
										<p class="dettagli_campo">
											<input type="password" name="password" value="<?php echo $password; ?>" disabled="disabled" />
										</p>	
									</div>
								</div>
							</div>
							<div class="container_sezione">
        						<div class="titolo_sezione"><p>Riepilogo Attivit&agrave;</p></div>
							</div>
							<div class="campo">
								<div class="contenuto" style="flex-direction: column;" >
									<div class="item" style="flex-direction:row; border:none;">
										<p class="nome_campo">
											Numero di Prenotazioni
										</p>
										<p class="dettagli_campo">
											<input type="text" name="num_prenotazioni" value="<?php echo $_COOKIE["Num_Prenotazioni"]; ?>" disabled="disabled" />
										</p>	
									</div>
									<div class="item" style="flex-direction:row; border:none;">
										<p class="nome_campo">
											Campo da Gioco (preferito)
										</p>
										<p class="dettagli_campo">
											<input type="text" name="campo_preferito" value="<?php echo $_COOKIE["Campo"]; ?>" disabled="disabled" />
										</p>	
									</div>
									<div class="item" style="flex-direction:row; border:none;">
										<p class="nome_campo">
											Disciplina (preferita)
										</p>
										<p class="dettagli_campo">
											<input type="text" name="disciplina_preferita" value="<?php echo $_COOKIE["Disciplina"]; ?>" disabled="disabled" />
										</p>	
									</div>
									<div class="item" style="flex-direction:row; border:none;">
										<p class="nome_campo">
											Fascia Oraria (preferita)
										</p>
										<p class="dettagli_campo">
											<input type="text" name="orario_preferito" value="<?php echo $_COOKIE["Fascia"]; ?>" disabled="disabled" />
										</p>	
									</div>
								</div>
							</div>
							<form class="container_button" action="modifica_account.php" method="post">
								<button type="submit" class="modify">Modifica!</button>
							</form>  
						</div>
					</div>
				</div>
			</div>
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