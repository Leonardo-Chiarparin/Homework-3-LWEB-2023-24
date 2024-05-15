<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<?php
	require_once("./sport_cookie_control.php");
	require_once("./session_control.php");
	
	// NELL'EVENTUALITÀ IN CUI CI SIA UN TENTATIVO DI ACCESSO DA PARTE DI UN DIPENDENTE, BISOGNA REINDERIZZARLO ALLA PAGINA INIZIALE DELL'AREA RISERVATA 
	if($_SESSION["tipo_Utente"]!="C")
		header ("Location: pagina_riservata.php");
	
	// APERTURA DEL FILE INERENTE AI CAMPI DA GIOCO SU CUI È POSSIBILE DISPUTARE UNA PARTITA LA CUI DISCIPLINA COINCIDE CON QUELLA SELEZIONATA
	$xmlStringCampi = "";
	
	foreach ( file("Campi.xml") as $nodoCampi ) 
	{
		$xmlStringCampi .= trim($nodoCampi);
	}
	
	$docCampi = new DOMDocument();
	$docCampi->loadXML($xmlStringCampi);
	$rootCampi = $docCampi->documentElement;
	$campi = $rootCampi->childNodes;
	
    // APERTURA DEL FILE INERENTE ALLE FASCE ORARIE
	$xmlStringFasce = "";
	
	foreach ( file("Fasce_Orarie.xml") as $nodoFasce ) 
	{
		$xmlStringFasce .= trim($nodoFasce);
	}
	
	$docFasce = new DOMDocument();
	$docFasce->loadXML($xmlStringFasce);
	$rootFasce = $docFasce->documentElement;
	$fasce = $rootFasce->childNodes;
	
	// UNA VOLTA PREMUTO IL PULSANTE DI CONFERMA, DOVRANNO ESSERE EFFETTUATI DEI CONTROLLI IN MERITO ALLA CORRETTEZZA DEI VALORI INSERITI
	if(isset($_GET["confirm"])){
		if(isset($_GET["campo"]) && isset($_GET["fascia"])){
			// VERIFICA DEL FORMATO INERENTE ALLA DATA INSERITA (ANNO-MESE-GIORNO) 
			if(preg_match("/(\d{4,4}-([[:digit:]][[:digit:]])-([[:digit:]][[:digit:]]))/",$_GET["date"], $matches))
			{
				// SE QUANTO INSERITO NON RISPETTA LA GRAMMATICA INDICATA DALL'ESPRESSIONE REGOLARE, BISOGNA STAMPARE UN MESSAGGIO COSÌ DA NOTIFICARLO ALL'UTENTE
				if($matches[0]!=$_GET["date"])
				{
					echo "<div class='error_message'>\n
					      <div class='container_message'>\n
						  <div class='container_img'>\n
						  <img src=\"Immagini/exclamation-solid.svg\" alt='Immagine non Disponibile...'>\n
					      </div>\n
					      <div class='message'>\n
					      <p class='err'>ERRORE!</p>\n
					      <p>LA DATA NON RISPETTA IL FORMATO INDICATO...</p>\n
					      </div>\n
					      </div>\n
					      </div>\n";
				}
				else {
					// VERIFICA IN MERITO ALLA VALIDITÀ (ESISTENZA) DELLA DATA INSERITA
					if(checkdate(substr($_GET["date"],5,2), substr($_GET["date"],8,2), substr($_GET["date"],0,4))){
						// COSTRUZIONE DEL TIMESTAMP SPECIFICATO MEDIANTE CONCATENAZIONE DELLA DATA E DEL VALORE TRONCATO (SOLO ORA INIZIALE) DELLA FASCIA ORARIA SELEZIONATA
						$timestamp_indicato=$_GET["date"]." ".substr($_GET["fascia"],0,8);
						
						// OTTENIMENTO DELL'ISTANTE DI TEMPO ATTUALE (DIFFERENZA DI SECONDI DAL GIORNO 01/01/1990 00:00:00)
						$timestamp_attuale=time();
						
						// CONTROLLO ,MEDIANTE CONVERSIONE IN TEMPO E CONFRONTO CON LA PRECEDENTE, IN MERITO ALLA VALIDITÀ TEMPORALE DELLA DATA E DELL'ORARIO FORNITI
						if(strtotime($timestamp_indicato)>$timestamp_attuale) {
							// PROCEDIAMO CON IL TENTATIVO DI INSERIMENTO DELLA PRENOTAZIONE ALL'INTERNO DEL DATABASE
							
							// OTTENIMENTO DELL'ORA INIZIALE E FINALE
							$ora_inizio=substr($_GET["fascia"],0,8);
							$ora_fine=substr($_GET["fascia"],-9,-1);
							
							// PRELEVIAMO, MEDIANTE LA "LETTURA" A RITROSO DEI CARATTERI, LA TARIFFA DALL'ELEMENTO RELATIVO AL NOME DEL CAMPO
							$tariffa=substr($_GET["campo"], -5, -1);
							$campo=substr($_GET["campo"], 0, -6);
							
							// CALCOLO DEL TOTALE A SECONDA DELLA DISCIPLINA
							if($_COOKIE["Disciplina_Scelta"]=="Calcio a 5")
								$totale=2*5*$tariffa;
							
							if($_COOKIE["Disciplina_Scelta"]=="Calcio a 6")
								$totale=2*6*$tariffa;
							
							if($_COOKIE["Disciplina_Scelta"]=="Calcio a 8")
								$totale=2*8*$tariffa;
							
							if($_COOKIE["Disciplina_Scelta"]=="Basket")
								$totale=2*5*$tariffa;
							
							if($_COOKIE["Disciplina_Scelta"]=="Tennis")
								$totale=2*$tariffa;
							
							// APERTURA DEL FILE INERENTE ALLE PRENOTAZIONI PER PROCEDERE CON L'EFFETTIVO INSERIMENTO
							$xmlStringPrenotazioni = "";
		
							foreach ( file("Prenotazioni.xml") as $nodoPrenotazioni ) 
							{
								$xmlStringPrenotazioni .= trim($nodoPrenotazioni);
							}
							
							$docPrenotazioni = new DOMDocument();
							$docPrenotazioni->loadXML($xmlStringPrenotazioni);
							$rootPrenotazioni = $docPrenotazioni->documentElement;
							$prenotazioni = $rootPrenotazioni->childNodes;
							
							for ($i=0; $i<$prenotazioni->length; $i++) 
							{
								$prenotazione = $prenotazioni->item($i);
								
								// OTTENIMENTO DEI VALORI (DATA, FASCIA ORARIA E CAMPO) MEDIANTE CUI VALUTARE L'UNICITÀ DELLE SCELTE EFFETTUATE
								$data_prenotazione = $prenotazione->firstChild->textContent;
								
								$ora_inizio_prenotazione = $prenotazione->firstChild->nextSibling->firstChild->textContent;
								$ora_fine_prenotazione = $prenotazione->firstChild->nextSibling->lastChild->textContent;
								
								$campo_prenotazione = $prenotazione->firstChild->nextSibling->nextSibling->textContent;
								
								// SE QUANTO INSERITO COINCIDE CON I DETTAGLI DI UNA PRENOTAZIONE GIÀ PRESENTE,
								if($_GET["date"]==$data_prenotazione && $ora_inizio==$ora_inizio_prenotazione && $ora_fine==$ora_fine_prenotazione && $campo==$campo_prenotazione){
									$duplicazioneRiscontrata=true;
									break;
								}
							}
							
							// SE È STATA INDIVIDUATA UNA DUPLICAZIONE,
							if(isset($duplicazioneRiscontrata) && $duplicazioneRiscontrata){
								echo "<div class='error_message'>\n
									  <div class='container_message'>\n
									  <div class='container_img'>\n
									  <img src=\"Immagini/exclamation-solid.svg\" alt='Immagine non Disponibile...'>\n
									  </div>\n
									  <div class='message'>\n
									  <p class='err'>ERRORE!</p>\n
									  <p>CAMPO GI&Agrave; PRENOTATO AL GIORNO E ALL'ORARIO INDICATO...</p>\n
									  </div>\n
									  </div>\n
									  </div>\n";
							}
							else {
								// SI PROCEDE ALLA CREAZIONE E ALL'INSERIMENTO DELLA NUOVA PRENOTAZIONE, IL CUI ID SARÀ DATO DALLA SOMMA DI QUELLO DELL'ULTIMO ELEMENTO E 1 => "LUNGHEZZA" DEL FILE + 1 
								$nuova_prenotazione=$docPrenotazioni->createElement("prenotazione");
								$nuova_prenotazione->setAttribute("bookingID", $prenotazioni->length+1);
								$nuova_prenotazione->setAttribute("pagamento", "N");
								$nuova_prenotazione->setAttribute("cliente", $_SESSION["id_Utente"]);
								
								// I DETTAGLI (DATA, FASCIA ORARIA E CAMPO DA GIOCO) E IL TOTALE DELLA PRENOTAZIONE, COME FIGLI DIRETTI DI PRENOTAZIONI 
								$nuova_data=$docPrenotazioni->createElement("data", $_GET["date"]);
								$nuova_prenotazione->appendChild($nuova_data);
								
								$nuova_fascia=$docPrenotazioni->createElement("fascia");
								
								$nuova_ora_inizio=$docPrenotazioni->createElement("ora_inizio", $ora_inizio);
								$nuova_fascia->appendChild($nuova_ora_inizio);
								
								$nuova_ora_fine=$docPrenotazioni->createElement("ora_fine", $ora_fine);
								$nuova_fascia->appendChild($nuova_ora_fine);
								
								$nuova_prenotazione->appendChild($nuova_fascia);
								
								$nuovo_campo=$docPrenotazioni->createElement("campo", $campo);
								$nuovo_campo->setAttribute("disciplina", $_COOKIE["Disciplina_Scelta"]);
								$nuova_prenotazione->appendChild($nuovo_campo);
								
								// IL TOTALE VIENE FORMATTATO CON number_format(...) COSÌ DA AVERE DUE CIFRE DECIMALI DOPO LA VIRGOLA
								$nuovo_totale=$docPrenotazioni->createElement("totale", number_format($totale, 2));
								$nuova_prenotazione->appendChild($nuovo_totale);
								
								// COLLOCAZIONE DELL'ELEMENTO COME ULTIMO FIGLIO
								$rootPrenotazioni->appendChild($nuova_prenotazione);
								
								if($docPrenotazioni->schemaValidate("Prenotazioni.xsd")){
									// SALVATAGGIO DEL FILE
									$docPrenotazioni->preserveWhiteSpace = false;
									$docPrenotazioni->formatOutput = true;
									$docPrenotazioni->save("./Prenotazioni.xml");
									
									// VARIABILE UTILE AL FINE DI STAMPARE UN MESSAGGIO DI CONFERMA
									$_SESSION["modifica_Effettuata"]=true;
									
									header("Location: pagina_riservata.php");
								}
								else {
									echo "<div class='error_message'>\n
										  <div class='container_message'>\n
										  <div class='container_img'>\n
										  <img src=\"Immagini/exclamation-solid.svg\" alt='Immagine non Disponibile...'>\n
										  </div>\n
										  <div class='message'>\n
										  <p class='err'>ERRORE!</p>\n
										  <p>VALIDAZIONE NON RIUSCITA...</p>\n
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
								  <p>LA DATA E L'ORARIO INDICATI NON RISULTANO VALIDI...</p>\n
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
							  <p>LA DATA INSERITA NON RISULTA VALIDA...</p>\n
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
					  <p>LA DATA NON RISPETTA IL FORMATO INDICATO...</p>\n
					  </div>\n
					  </div>\n
					  </div>\n";
			}
		}
		else {
			// STAMPA DEL RELATIVO MESSAGGIO D'ERRORE
			echo "<div class='error_message'>\n
				   <div class='container_message'>\n
				   <div class='container_img'>\n
                   <img src=\"Immagini/exclamation-solid.svg\" alt='Immagine non Disponibile...'>\n
				   </div>\n
                   <div class='message'>\n
                   <p class='err'>ERRORE!</p>\n
                   <p>BISOGNA COMPILARE TUTTI I CAMPI...</p>\n
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
					<a href="sport_selection.php" title="Selezione della Disciplina">Annulla</a>
				</span>
				
				<span class="voce_menu">
					<a href="login.php" title="Esci">Esci</a>
				</span>
			</div>
		</div>
	</div>
	<div class="container_corpo">
		<div class="container_principale">
			<p class="spazio_link"></p>
		
			<h1 class="saluti">Inserimento della Prenotazione!</h1>
			
			<form class="container_form" action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="get">
				<div class="form">
					<div class="intestazione">
						<h2>
							Compilare i seguenti campi con le informazioni richieste
						</h2>
					</div>
					<div class="container_elenco_campi">
						<div class="intestazione_elenco_campi">
							<h3>Dettagli della Prenotazione</h3>
						</div>
						<div class="corpo_elenco_campi">
							<div class="campo">
								<div class="contenuto" style="flex-direction: column;" >
									<div class="item" style="flex-direction:row; border:none;">
										<p class="nome_campo">
											Data
										</p>
										<p class="dettagli_campo">
											<input type="text" name="date" value="<?php if(isset($_GET['date'])) echo $_GET['date']; else echo '';?>"  />
										</p>	
									</div>
									<p style="font-size: 1em; color: red; width: 100%; margin-left: 1em;"><strong style="text-decoration: underline;">N.B.</strong> La data dovr&agrave; rispettare il formato anno-mese-giorno, con gli ultimi due caratterizzati da due cifre</p>
								</div>
							</div>
							<div class="campo_radio">
								<div class="contenuto">
									<div class="item">
										<div class="titolo">
											<p>Campo da Gioco</p>
										</div>
										<div class="voci">
											<?php
												for ($i=0; $i<$campi->length; $i++) 
												{
													$campo = $campi->item($i);
													
													// OTTENIMENTO DELL'ATTRIBUTO RELATIVO ALLA DISCIPLINA COSÌ DA EFFETTUARE UN FILTRO PER ESCLUDERE GLI ELEMENTI NON PERTINENTI
													$disciplina=$campo->getAttribute("disciplina");
													
													if($disciplina==$_COOKIE["Disciplina_Scelta"]){
														
														// INDIVIDUAZIONE DEL NOME E DELLA TARIFFA DEL CAMPO 
														$nome = $campo->firstChild->textContent;
														$tariffa = $campo->lastChild->textContent;
													
														echo "<div class=\"voce\"> \n
															  <p style=\"padding-right: 0.5%;\"> \n
															  <input type=\"radio\" name=\"campo\" value='".$nome." ".$tariffa." '";
														
														// LA VOCE INERENTE AL CAMPO SARÀ SPUNTATA NEL CASO IN CUI CORRISPONDA ALLA PREFERENZA (PRIMA INDIVIDUATA) DEL CLIENTE 	  
														if(isset($_COOKIE["Campo"]) && $_COOKIE["Campo"]==$nome)
															echo  " checked=\"checked\" "; 
													
														
														echo "/> \n
															  </p> \n 
															  <p style=\"margin-top: -0.5%;\"> \n ".$nome." (".$tariffa."&euro; orari) \n
															  </p> \n
															  </div> \n ";
													}						
												}
											?>
										</div>
									</div>
								</div>
							</div>
							<div class="campo_radio">
								<div class="contenuto">
									<div class="item">
										<div class="titolo">
											<p>Fascia Oraria</p>
										</div>
										<div class="voci">
											<?php 
												// NELL'INTERROGAZIONE EFFETTUATA AL FILE XML, SI È RICHIESTO UN FORMATO CHE PRIVASSE L'ORARIO DEI SECONDI. PROPRIO PER QUESTO, NEL VALORE REALE DI CIASCUN CAMPO È STATO NECESSARIO INSERIRE NUOVAMENTE GLI ELEMENTI MANCANTI
												for ($i=0; $i<$fasce->length; $i++) 
												{
													$fascia = $fasce->item($i);	
													
													// INDIVIDUAZIONE DEGLI ESTREMI INERENTI ALLA FASCIA ORARIA SELEZIONATA
													$ora_inizio= $fascia->firstChild->textContent;
													$ora_fine = $fascia->lastChild->textContent;
												
													echo "<div class=\"voce\"> \n
														  <p style=\"padding-right: 0.5%;\"> \n
														  <input type=\"radio\" name=\"fascia\" value='".$ora_inizio."-".$ora_fine." '";
													
													// LA VOCE INERENTE ALL'ORARIO SARÀ SPUNTATA NEL CASO IN CUI CORRISPONDA ALLA PREFERENZA (PRIMA INDIVIDUATA) DEL CLIENTE 	  
													if(isset($_COOKIE["Fascia"]) && $_COOKIE["Fascia"]==(substr($ora_inizio,0,5)."-".substr($ora_fine,0,5)))
														echo  " checked=\"checked\" "; 
													
													
													echo "/> \n
														  </p> \n 
														  <p style=\"margin-top: -0.5%;\"> \n ".substr($ora_inizio,0,5)." - ".substr($ora_fine,0,5)." \n
														  </p> \n
														  </div> \n ";					
												}
											?>
										</div>
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