<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<?php
	require_once("./session_control.php");
	require_once("./connection.php");
	
	// CONTROLLO PER VERIFICARE SE SI È STATI REINDIRIZZATI ALLA PAGINA A SEGUITO DELLA TERMINAZIONE DI UNA CERTA OPERAZIONE (AVVENUTA CON SUCCESSO)
	if(isset($_SESSION["modifica_Effettuata"])){
		unset($_SESSION["modifica_Effettuata"]);
		
		echo "<div class='confirm_message'>\n
			  <div class='container_message'>\n
			  <div class='container_img'>\n
			  <img src=\"Immagini/check-solid.svg\" alt='Immagine non Disponibile...'>\n
			  </div>\n
			  <div class='message'>\n
			  <p class='con'>OTTIMO!</p>\n
			  <p>OPERAZIONE EFFETTUATA CON SUCCESSO!</p>\n
			  </div>\n
			  </div>\n
			  </div>\n";
		
	}
	
	// SE NON SI HANNO PRENOTAZIONI ATTIVE ALL'INTERNO DELLA BASE DI DATI E IL GENERICO UTENTE CERCASSE DI ACCEDERE ALLA PAGINA DEDICATA ALLA LORO GESTIONE, SARÀ NECESSARIO STAMPARE UN MESSAGGIO D'ERRORE
	if(isset($_SESSION["nessuna_Prenotazione"])){
		unset($_SESSION["nessuna_Prenotazione"]);
		
		echo "<div class='error_message'>\n
			  <div class='container_message'>\n
			  <div class='container_img'>\n
			  <img src=\"Immagini/exclamation-solid.svg\" alt='Immagine non Disponibile...'>\n
			  </div>\n
			  <div class='message'>\n
			  <p class='err'>ERRORE!</p>\n
			  <p>NON &Egrave; PRESENTE ALCUNA PRENOTAZIONE...</p>\n
			  </div>\n
			  </div>\n
			  </div>\n";
		
	}
	
	// SE SI TRATTA DI UN CLIENTE, SI PROCEDE CON L'OTTENIMENTO DELLE INFORMAZIONI RELATIVE ALLE PREFERENZE DI QUEST'ULTIMO. TALI ASPETTI TORNERANNO UTILI SIA PER LA GESTIONE DELLE FUTURE PRENOTAZIONI CHE PER LA DEFINIZIONE DELLA RELAZIONE CHE IL SOGGETTO D'INTERESSE HA TENUTO NEI CONFRONTI DEL CAMPO SPORTIVO
	if($_SESSION["tipo_Utente"]=="C"){
		// AL FINE DI POTER DETERMINARE OPPORTUNAMENTE I VARI DETTAGLI, RISULTA NECESSARIO CREARE A PRIORI DEGLI ELEMENTI, TRA CUI VETTORI ASSOCIATIVI, MEDIANTE CUI POTER CONFRONTARE LE VARIE ISTANZE E OTTENERE QUANTO DESIDERATO
		// APERTURA DEL FILE XML INERENTE AI CAMPI DA GIOCO
		$xmlStringCampi = "";
	
		foreach ( file("Campi.xml") as $nodoCampi ) 
		{
			$xmlStringCampi .= trim($nodoCampi);
		}
		
		$docCampi = new DOMDocument();
		$docCampi->loadXML($xmlStringCampi);
		$rootCampi = $docCampi->documentElement;
		$campi = $rootCampi->childNodes;
		
		// CREAZIONE E POPOLAMENTO DELL'ARRAY ASSOCIATIVO INERENTE AI PRECEDENTI (LE CHIAVI CORRISPONDERANNO AI NOMI DEI CAMPI)
		$arrayCampi=array();
	
		for($i=0; $i<$campi->length; $i++){
			$arrayCampi[$campi->item($i)->firstChild->textContent]=0;
		}
		
		// APERTURA DEL FILE XML INERENTE ALLE FASCE ORARIE
		$xmlStringFasce = "";
		
		foreach ( file("Fasce_Orarie.xml") as $nodoFasce ) 
		{
			$xmlStringFasce .= trim($nodoFasce);
		}
		
		$docFasce = new DOMDocument();
		$docFasce->loadXML($xmlStringFasce);
		$rootFasce = $docFasce->documentElement;
		$fasce = $rootFasce->childNodes;
		
		// CREAZIONE E POPOLAMENTO DELL'ARRAY ASSOCIATIVO INERENTE ALLE PRECEDENTI (LE CHIAVI CORRISPONDERANNO ALLA CONCATENAZIONE TRA L'ORA D'INIZIO E DI FINE, SEPARATE DA UN TRATTINO)
		$arrayFasce=array();
	
		for($i=0; $i<$fasce->length; $i++){
			$arrayFasce[$fasce->item($i)->firstChild->textContent."-".$fasce->item($i)->lastChild->textContent]=0;
		}
		
		// DICHIARAZIONE E INIZIALIZZAZIONE DELLA VARIABILE RELATIVE AL NUMERO DI PRENOTAZIONI EFFETTUATE DALL'UTENTE E DELL'ARRAY ASSOCITIVO PREPOSTO AL CONTENIMENTO DEL NUMERO DI VOLTE IN CUI IL SOGGETTO D'INTERESSE HA SELEZIONATO LA CORRISPONDENTE DISCIPLINA
		$num_prenotazioni=0;
		
		// AVENDO A DISPOSIZIONE SOLTANTO CINQUE SPORT, È SUFFICIENTE DEFINIRE STATICAMENTE UN ARRAY ASSOCIATIVO CHE SIA IN GRADO DI GESTIRLE
		$arrayDiscipline["Calcio a 5"]=0;
		$arrayDiscipline["Calcio a 6"]=0;
		$arrayDiscipline["Calcio a 8"]=0;
		$arrayDiscipline["Basket"]=0;
		$arrayDiscipline["Tennis"]=0;
		
		// APERTURA DEL FILE XML INERENTE ALLE PRENOTAZIONI 
		$xmlStringPrenotazioni = "";
		
		foreach ( file("Prenotazioni.xml") as $nodoPrenotazioni ) 
		{
			$xmlStringPrenotazioni .= trim($nodoPrenotazioni);
		}
		
		$docPrenotazioni = new DOMDocument();
		$docPrenotazioni->loadXML($xmlStringPrenotazioni);
		$rootPrenotazioni = $docPrenotazioni->documentElement;
		$prenotazioni = $rootPrenotazioni->childNodes;
		
		for($i=0; $i<$prenotazioni->length; $i++){
			// SE L'ELEMENTO CHE SI STA ANALIZZANDO È UNA PRENOTAZIONE EFFETTUATA DEL CLIENTE IN QUESTIONE,
			if($prenotazioni->item($i)->getAttribute("cliente")==$_SESSION["id_Utente"]){
				// SI PROCEDE CON L'INCREMENTO DEI VARI ELEMENTI D'INTERESSE SEGUENDO UNA METODOLOGIA (PROPRIA) CARATTERIZZATA IN FUNZIONE DI CIASCUNO DI ESSI
				$num_prenotazioni++; 
				
				// OTTENIMENTO DELLA FASCIA ORARIA INDICATA PER POI AGGIORNARNE IL CORRISPONDENTE CONTATORE ALL'INTERNO DEL VETTORE $arrayFasce
				$fascia=$prenotazioni->item($i)->firstChild->nextSibling->firstChild->textContent."-".$prenotazioni->item($i)->firstChild->nextSibling->lastChild->textContent;
				$arrayFasce[$fascia]++;
				
				// OTTENIMENTO DEL CAMPO INDICATO PER POI AGGIORNARNE IL CORRISPONDENTE CONTATORE ALL'INTERNO DEL VETTORE $arrayFasce
				$campo=$prenotazioni->item($i)->firstChild->nextSibling->nextSibling->textContent;
				$arrayCampi[$campo]++;
				
				// OTTENIMENTO DELLA DISCIPLINA INERENTE AL PRECEDENTE ELEMENTO PER POI AGGIORNARNE IL CORRISPONDENTE CONTATORE ALL'INTERNO DEL VETTORE $arrayFasce
				$disciplina=$prenotazioni->item($i)->firstChild->nextSibling->nextSibling->getAttribute("disciplina");
				$arrayDiscipline[$disciplina]++;
			}
		}
		
		// SE IL CLIENTE HA EFFETTUATO ALMENO UNA PRENOTAZIONE,
		if($num_prenotazioni>0){
			// INDIVIDUAZIONE DEL MASSIMO ALL'INTERNO DEI VARI VETTORI MEDIANTE LA FUNZIONE max(...). A SEGUITO DI CIÒ, È POSSIBILE OTTENERE LA CHIAVE DI INTERESSE GRAZIE A array_search(...), LA QUALE RESTITUIRÀ LA PRIMA ISTANZA UTILE AVENTE IL VALORE SPECIFICATO (POICHÈ SI TRATTA DI MASSIMI, NELL'EVENTUALITÀ IN CUI CI SIANO PIÙ ELEMENTI CON LO STESSO VALORE, SARÀ SUFFICINETE CONSIDERARNE UNO SOLO)
			$campo_preferito=array_search(max($arrayCampi),$arrayCampi);
			
			$fascia_preferita=array_search(max($arrayFasce),$arrayFasce);
			
			// FORMATTAZIONE DELLA FASCIA ORARIA AL FINE DI GARANTIRE UNA STAMPA PIÙ PIACEVOLE
			$fascia_preferita=substr($fascia_preferita,0,5)."".substr($fascia_preferita,8,6);
			
			$disciplina_preferita=array_search(max($arrayDiscipline),$arrayDiscipline);
		}
		else {
			$campo_preferito="Nessuno";
			$fascia_preferita="Nessuna";
			$disciplina_preferita="Nessuna";
		}
		
		// IMPOSTAZIONE DEI COOKIE IN MERITO AI RISULTATI OTTENUTI
		setcookie("Num_Prenotazioni", $num_prenotazioni);
		setcookie("Campo", $campo_preferito);
		setcookie("Fascia", $fascia_preferita);
		setcookie("Disciplina", $disciplina_preferita);
		
		// ELIMINAZIONE, PER QUESTIONI DI "EFFICIENZA", DELLE VARIABILI (VETTORI) NON PIÙ IN USO
		unset($arrayCampi);
		unset($arrayFasce);
		unset($arrayDiscipline);
	}
?>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>CSS: Campo Sportivo dei Sogni</title>
	<link rel="icon" href="Immagini/Logo.png" />
	<link rel="stylesheet" href="CSS/style_pagina_riservata.css" type="text/css" />
</head>
<body>
	<div class="barra_navigazione">
		<div class="container_logo">
			<img class="logo" src="Immagini/Barra.png" alt="Logo non Disponibile..." />
		</div>
		<div class="container_menu">
			<div class="menu">
				<?php
					// SE SI TRATTA DI UN CLIENTE, BISOGNA RIPORTARE LA VOCE ASSOCIATA ALLA VISUALIZZAZIONE DEL PROPRIO PROFILO
					if($_SESSION["tipo_Utente"]=="C"){
						echo "<span class=\"voce_menu\"> \n
							  <a href=\"account.php\" title=\"Account\">Account</a> \n
							  </span> \n";
					}
				?>
				<span class="voce_menu">
					<a href="login.php" title="Pagina di Login">Esci</a>
				</span>
			</div>
		</div>
	</div>
	<div class="container_corpo">
		<div class="container_principale">
			<p class="spazio_link"></p>
		
			<h1 class="saluti">Salve, <?php echo $_SESSION["nome_Utente"]." ".$_SESSION["cognome_Utente"]."!"; ?></h1>
			
			<div class="container_operazioni">
				<div class="operazione">
					<div class="anteprima">
						<div class="immagine" style="background-image: url('Immagini/Background_Prenotazione-Registrazione.jpg');">"></div>
					</div>
					<div class="paragrafo">
						<?php
							if($_SESSION["tipo_Utente"]=="C"){
								echo "<h2>Prenota il Campo!</h2> \n
									  <p> \n
									  Inserisci i dettagli della prenotazione, selezionando il <strong>campo</strong>, il <strong>giorno</strong>, la <strong>fascia oraria</strong> e soprattutto lo <strong>sport</strong> che preferisci tra quelli disponibili. Non avere pensieri prima del grande incontro, in quanto, stando alle nostre politiche, puoi benissimo pagare di persona dopo aver giocato! Inoltre, giunti sul posto, lo staff sar&agrave; ben lieto di aiutarti. \n 
									  </p> \n
									  <form action=\"sport_selection.php\" method=\"post\"> \n
									  <p><button type=\"submit\" class=\"dettagli\">Prenota!</button></p> \n
									  </form> \n";
							}
							else {
								echo "<h2>Registra il Pagamento!</h2> \n
									  <p> \n
									  Inserisci i dettagli del pagamento, selezionando la <strong>prenotazione</strong> tra quelle non ancora saldate. \n 
									  </p> \n
									  <form action=\"registra_pagamento.php\" method=\"post\"> \n
									  <p><button type=\"submit\" class=\"dettagli\">Registra!</button></p> \n
									  </form> \n";
							}
						?>
					</div>
				</div>
				<div class="operazione">
					<div class="anteprima">
						<div class="immagine" style="background-image: url('Immagini/Background_Modifiche.jpg');"></div>
					</div>
					<div class="paragrafo">
						<h2>Modifica le Richieste!</h2>
						<?php
							if($_SESSION["tipo_Utente"]=="C"){
								echo "<p> \n
									  <strong>Visualizza</strong> le prenotazioni effettuate, apportando, in base alla disponibilit&agrave; del momento, alcune <strong>modifiche</strong> relative al loro contenuto. Inoltre, qualora non ne abbiate pi&ugrave; bisogno, sentitevi liberi di <strong>disdire</strong> la vostre richieste, non &egrave; prevista alcuna penale. Vi chiediamo solo di agire per tempo, in quanto altri potrebbero desiderare di giocare! \n   
									  </p> \n";
							}
							else {
								echo "<p> \n
									  <strong>Visualizza</strong> le prenotazioni effettuate dai clienti, apportando alcune <strong>modifiche</strong> al loro contenuto. \n   
									  </p> \n";
							}
							
						?>
						<form action="gestione_prenotazioni.php" method="post">
							<p><button type="submit" class="dettagli">Modifica!</button></p>
						</form>
					</div>
				</div>
				<div class="operazione">
					<div class="anteprima">
						<div class="immagine" style="background-image: url('Immagini/Background_Riepilogo.jpg');"></div>
					</div>
					<div class="paragrafo">
						<h2>Visualizza lo Storico!</h2>
						<?php
							if($_SESSION["tipo_Utente"]=="C"){
								echo "<p> \n
									  <strong>Consulta</strong> l'elenco delle richieste fatte nel corso del tempo! Oltre che da un punto di vista <strong>nostalgico</strong>, potrebbero tornare utili al fine di determinare i vincitori dei premi che siamo soliti donare ai clienti pi&ugrave; fedeli ad ogni stagione! Non perderti l'occasione di entrare in possesso di divise o accessori autografati direttamente dai tuoi idoli! \n 
									  </p> \n";
							}
							else {
								echo "<p> \n
									  <strong>Consulta</strong> l'elenco delle richieste fatte dai clienti per determinare gli eventuali elementi di interesse. \n 
									  </p> \n";
							}
						?>
						<form action="storico_prenotazioni.php" method="post">
							<p><button type="submit" class="dettagli">Visualizza!</button></p>
						</form>
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