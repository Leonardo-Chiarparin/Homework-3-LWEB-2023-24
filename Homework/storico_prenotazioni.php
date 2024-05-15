<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<?php
	require_once("./session_control.php");
	require_once("./connection.php");
	
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
	
	// SE NON SI HANNO PRENOTAZIONI,
	if($num_prenotazioni==0){
		// VARIABILE UTILE PER LA STAMPA DEL RELATIVO MESSAGGIO D'ERRORE
		$_SESSION["nessuna_Prenotazione"]=true;
		header("Location: pagina_riservata.php");
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
		
			<h1 class="saluti">Riepilogo delle Prenotazioni!</h1>
			
			<!--NELLE COMPONENTI DEDICATE AL CONTENIMENTO DELL'INPUT FORNITO DALL'UTENTE, SI È DECISO DI PRESERVARE QUANTO SPECIFICATO ANCHE IN PRESENZA DI EVENTUALI ERRORI-->
			<form class="container_form" action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="get">
				<div class="form">
					<div class="intestazione">
						<h2>
							Premere una delle voci del men&ugrave; per tornare ad una delle pagine precedenti
						</h2>
					</div>
					<div class="container_elenco_campi">
						<div class="intestazione_elenco_campi">
							<h3>Dettagli dello Storico</h3>
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
														echo "<th class=\"td_item\">Saldata? (Y/N)</th> \n";
		
													?>
												</tr>
											</thead>
											<tbody>
												<?php 
													for($i=0; $i<$prenotazioni->length; $i++){
														$prenotazione=$prenotazioni->item($i);
													
														$pagamento=$prenotazione->getAttribute("pagamento");
														
														$data=$prenotazione->firstChild->textContent;
															
														$ora_inizio = $prenotazione->firstChild->nextSibling->firstChild->textContent;
														$ora_fine = $prenotazione->firstChild->nextSibling->lastChild->textContent;
														
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
															echo "<td class=\"td_item\">".$pagamento."</td> \n";
															echo "</tr> \n";
														}
														else {
															// BISOGNA CONSIDERARE SOLTANTO QUELLE EFFETTUATE DAL SOGGETTO D'INTERESSE
															if($prenotazione->getAttribute("cliente")==$_SESSION["id_Utente"]){
																$id_prenotazione=$prenotazione->getAttribute("bookingID");
																
																echo "<tr> \n";
																echo "<td class=\"td_item\">".$id_prenotazione."</td> \n";
																echo "<td class=\"td_item\">".$data."</td> \n";
																echo "<td class=\"td_item\">".$campo."</td> \n";
																echo "<td class=\"td_item\">".substr($ora_inizio,0,5)."-".substr($ora_fine,0,5)."</td> \n";
																echo "<td class=\"td_item\">".$totale."</td> \n";
																echo "<td class=\"td_item\">".$pagamento."</td> \n";
																echo "</tr> \n";
															}
														}
													}
													
												?>
											</tbody>
										</table>
									</div>
								</div>
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