<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<?php
	require_once("./session_control.php");
	require_once("./connection.php");
	
	// NELL'EVENTUALUTÀ IN CUI RISULTINO ANCORA IMPOSTATI, SI PROCEDE CON L'ELIMINAZIONE DEI COOKIE RELATIVI ALLA PRENOTAZIONE SELEZIONATA
	if(isset($_COOKIE["id_Prenotazione"])){
		unset($_COOKIE["id_Prenotazione"]);
	}
	
	// OTTENIMENTO DELLE PRENOTAZIONI, LE QUALI SARANNO POI OPPORTUNAMENTE FILTRATE IN BASE AL TIPO DI UTENTE CHE LE VISUALIZZERÀ
	$xmlStringPrenotazioni = "";
		
	foreach ( file("Prenotazioni.xml") as $nodoPrenotazioni ) 
	{
		$xmlStringPrenotazioni .= trim($nodoPrenotazioni);
	}
	
	$docPrenotazioni = new DOMDocument();
	$docPrenotazioni->loadXML($xmlStringPrenotazioni);
	$rootPrenotazioni = $docPrenotazioni->documentElement;
	$prenotazioni = $rootPrenotazioni->childNodes;
	
	// VERIFICA PRELIMINARE IN MERITO AL CONTENUTO DEL FILE APERTO
	// CONTATORE PER VALUTARE SE CI SONO EFFETTIVAMENTE DELLE PRENOTAZIONI CON LE SPECIFICHE DI CUI SOPRA
	$num_prenotazioni=0;
	
	for($i=0; $i<$prenotazioni->length; $i++){
		$prenotazione=$prenotazioni->item($i);
		
		// OTTENIMENTO DELLA DATA E DELL'ORA D'INIZIO RELATIVE ALLA PRENOTAZIONE
		$data=$prenotazione->firstChild->textContent;
		$ora_inizio=$prenotazione->firstChild->nextSibling->firstChild->textContent;
		
		// COSTRUZIONE DEL TIMESTAMP SPECIFICATO MEDIANTE CONCATENAZIONE DELLA DATA E DEL VALORE (SOLO ORA INIZIALE) DELLA FASCIA ORARIA SELEZIONATA
		$timestamp_indicato=$data." ".$ora_inizio;
		
		// OTTENIMENTO DELL'ISTANTE DI TEMPO ATTUALE (DIFFERENZA DI SECONDI DAL GIORNO 01/01/1990 00:00:00)
		$timestamp_attuale=time();
		
		// CONTROLLO PER VALUTARE SE BISOGNA CONSIDERARE LA PRENOTAZIONE O MENO. NELLO SPECIFICO, SARÀ POSSIBILE MODIFICARLE FINO AD UN'ORA PRIMA (-3600) DALL'INCONTRO
		if((strtotime($timestamp_indicato)-3600)>=$timestamp_attuale){
			// SE L'UTENTE È UN DIPENDENTE, SARÀ IN GRADO DI VISUALIZZARE LE PRENOTAZIONI A PRESCINDERE DAI CLIENTI CHE LE HANNO EFFETTUATE  
			if($_SESSION["tipo_Utente"]=="D"){
				$num_prenotazioni++;
			}
			else {
				// BISOGNA CONSIDERARE SOLTANTO QUELLE EFFETTUATE DAL SOGGETTO D'INTERESSE
				if($prenotazione->getAttribute("cliente")==$_SESSION["id_Utente"])
					$num_prenotazioni++;
			}
		}
	}
	
	// SE NON SI HANNO PRENOTAZIONI,
	if($num_prenotazioni==0){
		// VARIABILE UTILE PER LA STAMPA DEL RELATIVO MESSAGGIO D'ERRORE
		$_SESSION["nessuna_Prenotazione"]=true;
		header("Location: pagina_riservata.php");
	}
	
	// VERIFICA INERENTE ALLE SCELTE EFFETTUATE
	if(isset($_GET["confirm"])){
		if(isset($_GET["prenotazione"])){
			// DEFINIZIONE DEI COOKIE MEDIANTE CUI RIPORTARE LE INFORMAZIONI DELLA PRENOTAZIONE ALL'INTERNO DELLA SCHEMATA DEDICATA
			setcookie("id_Prenotazione", $_GET["prenotazione"]);
			
			// REINDIRIZZAMENTO ALLA PAGINA DI MODIFICA DELLA PRENOTAZIONE
			header("Location: modifica_prenotazione.php");
			
		}
		else {
			echo "<div class='error_message'>\n
				  <div class='container_message'>\n
				  <div class='container_img'>\n
				  <img src=\"Immagini/exclamation-solid.svg\" alt='Immagine non Disponibile...'>\n
				  </div>\n
				  <div class='message'>\n
				  <p class='err'>ERRORE!</p>\n
				  <p>NESSUNA PRENOTAZIONE SELEZIONATA...</p>\n
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
	<link rel="stylesheet" href="CSS/style_form.css" type="text/css" />
</head>
<body>
	<?php
		require_once("./menu_riservato.php");
	?>
	<div class="container_corpo">
		<div class="container_principale">
			<p class="spazio_link"></p>
		
			<h1 class="saluti">Gestione delle Prenotazioni!</h1>
			
			<!--NELLE COMPONENTI DEDICATE AL CONTENIMENTO DELL'INPUT FORNITO DALL'UTENTE, SI È DECISO DI PRESERVARE QUANTO SPECIFICATO ANCHE IN PRESENZA DI EVENTUALI ERRORI-->
			<form class="container_form" action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="get">
				<div class="form">
					<div class="intestazione">
						<h2>
							Spuntare la voce di interesse e confermare per modificare il relativo impegno
						</h2>
					</div>
					<div class="container_elenco_campi">
						<div class="intestazione_elenco_campi">
							<h3>Dettagli delle Attivit&agrave; (in programma)</h3>
						</div>
						<div class="corpo_elenco_campi">
							<div class="container_sezione">
								<div class="titolo_sezione"><p>Profilo Gestionale</p></div>
							</div>
							<div class="campo">
								<div class="contenuto" style="flex-direction: column;" >
									<div class="item" style="flex-direction:row; border:none;">
										<table>
											<thead>
												<tr>
													<?php
														if($_SESSION["tipo_Utente"]=="C")
															echo "<th class=\"td_item\">N. Prenotazione</th> \n";
														else {
															echo "<th class=\"td_item\">Cliente</th> \n";
															echo "<th class=\"td_item\">Recapito Telefonico</th> \n";
														}
														
														echo "<th class=\"td_item\">Data (anno-mm-gg)</th> \n";
														echo "<th class=\"td_item\">Campo</th> \n";
														echo "<th class=\"td_item\">Fascia Oraria</th> \n";
														echo "<th class=\"td_item\">Totale (&euro;)</th> \n";
														echo "<th class=\"td_box\">Azione</th> \n";
														
													?>
												</tr>
											</thead>
											<tbody>
												<?php 
													for($i=0; $i<$prenotazioni->length; $i++){
														$prenotazione=$prenotazioni->item($i);
														
														$data=$prenotazione->firstChild->textContent;
															
														$ora_inizio = $prenotazione->firstChild->nextSibling->firstChild->textContent;
														$ora_fine = $prenotazione->firstChild->nextSibling->lastChild->textContent;
														
														// COSTRUZIONE DEL TIMESTAMP SPECIFICATO MEDIANTE CONCATENAZIONE DELLA DATA E DEL VALORE (SOLO ORA INIZIALE) DELLA FASCIA ORARIA SELEZIONATA
														$timestamp_indicato=$data." ".$ora_inizio;
														
														// OTTENIMENTO DELL'ISTANTE DI TEMPO ATTUALE (DIFFERENZA DI SECONDI DAL GIORNO 01/01/1990 00:00:00)
														$timestamp_attuale=time();
														
														// CONTROLLO PER VALUTARE SE BISOGNA CONSIDERARE LA PRENOTAZIONE O MENO. NELLO SPECIFICO, SARÀ POSSIBILE MODIFICARLE FINO AD UN'ORA PRIMA (-3600) DALL'INCONTRO
														if((strtotime($timestamp_indicato)-3600)>=$timestamp_attuale){														
															$id_prenotazione=$prenotazione->getAttribute("bookingID");
															
															$disciplina = $prenotazione->getAttribute("disciplina");
														
															$campo = $prenotazione->firstChild->nextSibling->nextSibling->textContent;
																	
															$totale = $prenotazione->lastChild->textContent;
															
															// SE L'UTENTE È UN DIPENDENTE, SARÀ IN GRADO DI VISUALIZZARE LE PRENOTAZIONI A PRESCINDERE DAI CLIENTI CHE LE HANNO EFFETTUATE  
															if($_SESSION["tipo_Utente"]=="D"){
																$cliente=$prenotazione->getAttribute("cliente");
																// OTTENIMENTO DELLE INFORMAZIONI RELATIVE AI CLIENTI D'INTERESSE PER LE PRENOTAZIONI
																$sql="SELECT Nome, Cognome, Num_Telefono FROM Utenti WHERE ID=".$cliente; 
																$result=mysqli_query($conn, $sql);
		
																while($row=mysqli_fetch_array($result)){
																	$nome=$row["Nome"];
																	$cognome=$row["Cognome"];
																	$num_telefono=$row["Num_Telefono"];
																}
																
																echo "<tr> \n";
																echo "<td class=\"td_item\">".$nome." ".$cognome."</td> \n";
																echo "<td class=\"td_item\">".$num_telefono."</td> \n";
																
																echo "<td class=\"td_item\">".$data."</td> \n";
			
																echo "<td class=\"td_item\">".$campo."</td> \n";
																
																echo "<td class=\"td_item\">".substr($ora_inizio,0,5)."-".substr($ora_fine,0,5)."</td> \n";
																
																echo "<td class=\"td_item\">".$totale."</td> \n";
																echo "<td class=\"td_box\"><input type=\"radio\" name=\"prenotazione\" value='".$id_prenotazione."' /></td> \n";
																
																echo "</tr> \n";
															}
															else {
																// BISOGNA CONSIDERARE SOLTANTO QUELLE EFFETTUATE DAL SOGGETTO D'INTERESSE
																if($prenotazione->getAttribute("cliente")==$_SESSION["id_Utente"]){
																	echo "<tr> \n";
																	echo "<td class=\"td_item\">".$id_prenotazione."</td> \n";
																	
																	echo "<td class=\"td_item\">".$data."</td> \n";
																	
																	echo "<td class=\"td_item\">".$campo."</td> \n";
																	
																	echo "<td class=\"td_item\">".substr($ora_inizio,0,5)."-".substr($ora_fine,0,5)."</td> \n";
																		
																	echo "<td class=\"td_item\">".$totale."</td> \n";
																	echo "<td class=\"td_box\"><input type=\"radio\" name=\"prenotazione\" value='".$id_prenotazione."' /></td> \n";
																	
																	echo "</tr> \n";
																}
															}
														}
													}
												?>
											</tbody>
										</table>
									</div>
								</div>
							</div>
							<div class="container_button">
								<button type="submit" name="confirm" value="confirm" class="confirm">Conferma!</button>
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