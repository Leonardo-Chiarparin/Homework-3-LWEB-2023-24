<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>CSS: Campo Sportivo dei Sogni</title>
	<link rel="icon" href="Immagini/Logo.png" />
	<link rel="stylesheet" href="CSS/style_form.css" type="text/css" />
</head>
<body>
	<?php
		require_once("./dbms_connection.php");
		
		mysqli_query($conn,"DROP DATABASE IF EXISTS cantile_chiarparin_homework_3");
		
		// CREAZIONE DEL DATABASE 
		mysqli_query($conn,"CREATE DATABASE cantile_chiarparin_homework_3");
		mysqli_query($conn,"USE cantile_chiarparin_homework_3");
		
		
		// CREAZIONE DELLE TABELLE E SUCCESSIVO POPOLAMENTO DELLE STESSE
		mysqli_query($conn, "DROP TABLE IF EXISTS Campi");
		$sql="CREATE TABLE Campi (ID int NOT NULL AUTO_INCREMENT, Nome varchar(35) NOT NULL, Tariffa decimal(4,2) NOT NULL, Disciplina varchar(10) NOT NULL, PRIMARY KEY (ID), UNIQUE (Nome) )";
		mysqli_query($conn,$sql);
		
		$sql="INSERT INTO Campi VALUES (1,'Olimpico',5.00,'Calcio a 5'),(3,'Barbera',5.00,'Calcio a 5'),(4,'San Nicola',5.00,'Calcio a 5'),(5,'Mapei',5.00,'Calcio a 5'),(6,'San Siro',6.00,'Calcio a 6'),(7,'Bernabeu',6.00,'Calcio a 6'),(8,'Camp Nou',8.00,'Calcio a 8'),(9,'Maradona',8.00,'Calcio a 8'),(10,'United Center',6.50,'Basket'),(11,'American Airlines Center',6.50,'Basket'),(12,'Arthur Ashe Stadium',8.50,'Tennis'),(13,'Beogradska Arena',8.50,'Tennis'),(14,'Indian Wells Tennis Garden',8.50,'Tennis'),(15,'Inalpi Arena',8.50,'Tennis'),(16,'Court Philippe Chatrier',8.50,'Tennis'),(17,'Rod Laver Arena',8.50,'Tennis'),(18,'Centre Court',8.50,'Tennis'),(19,'Connecticut Tennis Center',8.50,'Tennis')";
		mysqli_query($conn,$sql);
		
		mysqli_query($conn, "DROP TABLE IF EXISTS Fasce_Orarie");
		$sql="CREATE TABLE Fasce_Orarie (ID int NOT NULL AUTO_INCREMENT, Ora_Inizio time NOT NULL, Ora_Fine time NOT NULL, PRIMARY KEY (ID), UNIQUE(Ora_Inizio, Ora_Fine))";
		mysqli_query($conn,$sql);
		
		$sql="INSERT INTO Fasce_Orarie VALUES (1,'10:00:00','11:00:00'),(2,'11:00:00','12:00:00'),(3,'12:00:00','13:00:00'),(4,'13:00:00','14:00:00'),(5,'14:00:00','15:00:00'),(7,'15:00:00','16:00:00'),(8,'16:00:00','17:00:00'),(9,'17:00:00','18:00:00'),(10,'18:00:00','19:00:00'),(11,'19:00:00','20:00:00'),(12,'20:00:00','21:00:00'),(13,'21:00:00','22:00:00')";
		mysqli_query($conn,$sql);
		
		mysqli_query($conn, "DROP TABLE IF EXISTS Utenti");
		$sql="CREATE TABLE Utenti (ID int NOT NULL AUTO_INCREMENT, CF char(16) NOT NULL, Nome varchar(30) NOT NULL, Cognome varchar(35) NOT NULL, Num_Telefono varchar(10) NOT NULL, Email varchar(35) NOT NULL, Password varchar(32) NOT NULL, Tipo_Utente char(1) NOT NULL, PRIMARY KEY (ID), UNIQUE (CF), UNIQUE (Email))";
		mysqli_query($conn,$sql);
		
		// LE PASSWORD ALL'INTERNO DELLA FUNZIONE MD5 SARANNO QUELLE DA INSERIRE DURANTE L'AUTENTICAZIONE
		$sql="INSERT INTO Utenti VALUES (1,'TTLSNT80D01D810O','Sante','Attalle','3497654234','attallesante@gmail.com','aaaaaA1!a','C'),(2,'NPCRSO72E43E472R','Rosa','Napucci','3279864356','rosanapucci@libero.it','bbbbbB2?b','C'),(3,'CNTTTR02L09E472F','Ettore','Cantile','3451772123','ettore.cantile@css.it','EttoreC9!','D'),(4,'CHRLRD02E28E472W','Leonardo','Chiarparin','3337279141','leonardo.chiarparin@css.it','LeonarC6?','D')";
		mysqli_query($conn,$sql);
		
		mysqli_query($conn, "DROP TABLE IF EXISTS Prenotazioni");
		$sql="CREATE TABLE Prenotazioni (ID int NOT NULL AUTO_INCREMENT, Data date NOT NULL, Totale decimal(8,2) NOT NULL, Pagamento char(1) NOT NULL, ID_Cliente int NOT NULL, ID_Campo int NOT NULL, ID_Fascia_Oraria int NOT NULL, PRIMARY KEY (ID), UNIQUE (Data,ID_Campo,ID_Fascia_Oraria), CONSTRAINT Effettuata_Da FOREIGN KEY (ID_Cliente) REFERENCES Utenti(ID), CONSTRAINT Specificato_In FOREIGN KEY (ID_Campo) REFERENCES Campi(ID), CONSTRAINT Indicata_In FOREIGN KEY (ID_Fascia_Oraria) REFERENCES Fasce_Orarie(ID))";
		mysqli_query($conn,$sql);
		
		$sql="INSERT INTO Prenotazioni VALUES (1,'2024-05-30',65.00,'N',2,10,12);";
		mysqli_query($conn,$sql);
		
		// CREAZIONE DEI FILE XML UTILI AL FINE DI REALIZZARE LE VARIE FUNZIONALITÀ DEL SITO. LE INFORMAZIONI VENGONO ESTRAPOLATE DIRETTAMENTE DALLA BASE DI DATI.
		// PER I CAMPI DA GIOCO
		$sql = "SELECT ID, Nome, Tariffa, Disciplina FROM Campi";
		$result=mysqli_query($conn, $sql);
		
		// SPECIFICA DELLA VERSIONE DI XML, DELL'ENCODING UTILIZZATO E DEL FORMATO DA RISPETTARE
		$dom = new DOMDocument("1.0", "UTF-8");
		$dom->preserveWhiteSpace = false;
		$dom->formatOutput = true;
		
		// INTRODUZIONE DELLA GRAMMATICA CHE IL FILE DOVRÀ RISPETTARE
		$implementation = new DOMImplementation();
		$dom->appendChild($implementation->createDocumentType("campi","","Campi.dtd"));
		
		// CREAZIONE DELL'ELEMENTO RADICE
		$root=$dom->createElement("campi");
		
		while($row=mysqli_fetch_array($result)){
			// POPOLAMENTO DEL DOCUMENTO MEDIANTE L'INSERIMENTO DI NUOVI ELEMENTI 
			// CAMPO AVENTE DUE ATTRIBUTI: ID E DISCIPLINA
			$campo=$dom->createElement("campo");
			$campo->setAttribute("fieldID", $row["ID"]);
			$campo->setAttribute("disciplina", $row["Disciplina"]);
			
			// NOME E TARIFFA, COME FIGLI DIRETTI DI CAMPO 
			$nome=$dom->createElement("nome", $row["Nome"]);
			$campo->appendChild($nome);
			
			$tariffa=$dom->createElement("tariffa", $row["Tariffa"]);
			$campo->appendChild($tariffa);
			
			// PONIAMO CAMPO COME FIGLIO DELLA RADICE
			$root->appendChild($campo);			
		}
		
		$dom->appendChild($root);
		
		// SI PROCEDE CON IL SALVATAGGIO DEL FILE COME "Campi.xml"
		$dom->save("./Campi.xml");
		
		// CARICAMENTO DEL FILE APPENA CREATO PER PROCEDERE CON IL RELATIVO CONTROLLO
		$docCampi=new DOMDocument();
		$docCampi->load("Campi.xml");
		
		// VERIFICA INERENTE ALLA VALIDITÀ DEL FILE XML PRODOTTO
		if($docCampi->validate()){
			// CREAZIONE DEL FILE XML DEDICATO ALLE FASCE ORARIE MEDIANTE UN RAGIONAMENTO ANALOGO AL PRECEDENTE
			$sql = "SELECT ID, Ora_Inizio, Ora_Fine FROM Fasce_Orarie";
			$result=mysqli_query($conn, $sql);
			
			// SPECIFICA DELLA VERSIONE DI XML, DELL'ENCODING UTILIZZATO E DEL FORMATO DA RISPETTARE
			$dom = new DOMDocument("1.0", "UTF-8");
			$dom->preserveWhiteSpace = false;
			$dom->formatOutput = true;
			
			// INTRODUZIONE DELLA GRAMMATICA CHE IL FILE DOVRÀ RISPETTARE
			$implementation = new DOMImplementation();
			$dom->appendChild($implementation->createDocumentType("fasce","","Fasce_Orarie.dtd"));
			
			// CREAZIONE DELL'ELEMENTO RADICE
			$root=$dom->createElement("fasce");
			
			while($row=mysqli_fetch_array($result)){
				// POPOLAMENTO DEL DOCUMENTO MEDIANTE L'INSERIMENTO DI NUOVI ELEMENTI 
				// FASCIA AVENTE UN UNICO ATTRIBUTO: ID 
				$fascia=$dom->createElement("fascia");
				$fascia->setAttribute("timeID", $row["ID"]);
				
				// L'ORA D'INIZIO E FINE, COME FIGLIE DIRETTE DI FASCIA 
				$ora_inizio=$dom->createElement("ora_inizio", $row["Ora_Inizio"]);
				$fascia->appendChild($ora_inizio);
				
				$ora_fine=$dom->createElement("ora_fine", $row["Ora_Fine"]);
				$fascia->appendChild($ora_fine);
				
				// PONIAMO FASCIA COME FIGLIA DELLA RADICE
				$root->appendChild($fascia);			
			}
			
			$dom->appendChild($root);
			
			// SI PROCEDE CON IL SALVATAGGIO DEL FILE COME "Fasce_Orarie.xml"
			$dom->save("./Fasce_Orarie.xml");
			
			// CARICAMENTO DEL FILE APPENA CREATO PER PROCEDERE CON IL RELATIVO CONTROLLO
			$docFasce=new DOMDocument();
			$docFasce->load("Fasce_Orarie.xml");
			
			// VERIFICA INERENTE ALLA VALIDITÀ DEL FILE XML PRODOTTO
			if($docFasce->validate()){
				// CREAZIONE DEL FILE XML DEDICATO ALLE PRENOTAZIONI MEDIANTE UN RAGIONAMENTO ANALOGO AL PRECEDENTE
				$sql = "SELECT P.ID, P.Data, P.Totale, P.Pagamento, P.ID_Cliente, C.Nome, C.Disciplina, F.Ora_Inizio, F.Ora_Fine FROM Prenotazioni P, Campi C, Fasce_Orarie F WHERE P.ID_Campo=C.ID AND F.ID=P.ID_Fascia_Oraria";
				$result=mysqli_query($conn, $sql);
				
				// SPECIFICA DELLA VERSIONE DI XML, DELL'ENCODING UTILIZZATO E DEL FORMATO DA RISPETTARE
				$dom = new DOMDocument("1.0", "UTF-8");
				$dom->preserveWhiteSpace = false;
				$dom->formatOutput = true;
				
				// CREAZIONE DELL'ELEMENTO RADICE E SPECIFICA DELLO SCHEMA DI RIFERIMENTO
				$root=$dom->createElement("prenotazioni");
				$root->setAttribute("xmlns:xsi", "http://www.w3.org/2001/XMLSchema-instance");
				$root->setAttribute("xsi:noNamespaceSchemaLocation", "Prenotazioni.xsd");
				
				while($row=mysqli_fetch_array($result)){
					// POPOLAMENTO DEL DOCUMENTO MEDIANTE L'INSERIMENTO DI NUOVI ELEMENTI 
					// PRENOTAZIONE AVENTE TRE ATTRIBUTI: ID (DELLA PRENOTAZIONE), PAGAMENTO (SE È STATO EFFETTUATO O MENO) E L'ID DEL CLIENTE    
					$prenotazione=$dom->createElement("prenotazione");
					$prenotazione->setAttribute("bookingID", $row["ID"]);
					$prenotazione->setAttribute("pagamento", $row["Pagamento"]);
					$prenotazione->setAttribute("cliente", $row["ID_Cliente"]);
					
					// I DETTAGLI (DATA, FASCIA ORARIA E CAMPO DA GIOCO) E IL TOTALE DELLA PRENOTAZIONE, COME FIGLI DIRETTI DI PRENOTAZIONI 
					$data=$dom->createElement("data", $row["Data"]);
					$prenotazione->appendChild($data);
					
					$fascia=$dom->createElement("fascia");
					
					$ora_inizio=$dom->createElement("ora_inizio", $row["Ora_Inizio"]);
					$fascia->appendChild($ora_inizio);
					
					$ora_fine=$dom->createElement("ora_fine", $row["Ora_Fine"]);
					$fascia->appendChild($ora_fine);
					
					$prenotazione->appendChild($fascia);
					
					$campo=$dom->createElement("campo", $row["Nome"]);
					$campo->setAttribute("disciplina", $row["Disciplina"]);
					$prenotazione->appendChild($campo);
					
					$totale=$dom->createElement("totale", $row["Totale"]);
					$prenotazione->appendChild($totale);
					
					// PONIAMO PRENOTAZIONE COME FIGLIA DELLA RADICE
					$root->appendChild($prenotazione);			
				}
				
				$dom->appendChild($root);
				
				// SI PROCEDE CON IL SALVATAGGIO DEL FILE COME "Prenotazioni.xml"
				$dom->save("./Prenotazioni.xml");
				
				// CARICAMENTO DEL FILE APPENA CREATO PER PROCEDERE CON IL RELATIVO CONTROLLO
				$docPrenotazioni=new DOMDocument();
				$docPrenotazioni->load("Prenotazioni.xml");
				
				if($docPrenotazioni->schemaValidate("Prenotazioni.xsd")){
					// ELIMINAZIONE DELLE TABELLE SUPERFLUE
					mysqli_query($conn, "DROP TABLE IF EXISTS Prenotazioni");
					mysqli_query($conn, "DROP TABLE IF EXISTS Campi");
					mysqli_query($conn, "DROP TABLE IF EXISTS Fasce_Orarie");
					
					// CREAZIONE DEL COOKIE PER STAMPARE IL MESSAGGIO DIRETTAMENTE NELLA PAGINA "index.php"
					setcookie("caricamento_Effettuato",true);
					header("Location: index.php");
				}
				else {
					echo "<div class='error_message'>\n
						  <div class='container_message'>\n
						  <div class='container_img'>\n
						  <img src=\"Immagini/exclamation-solid.svg\" alt='Immagine non Disponibile...'>\n
						  </div>\n
						  <div class='message'>\n
						  <p class='err'>ERRORE!</p>\n
						  <p>PROBLEMA DURANTE LA CREAZIONE DEL FILE Prenotazioni.xml...</p>\n
						  </div>\n
						  </div>\n
						  </div>\n";
						  
					// ELIMINAZIONE DEI FILE FINORA CREATI
					unlink("Campi.xml");
					unlink("Fasce_Orarie.xml");
					unlink("Prenotazioni.xml");
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
						  <p>PROBLEMA DURANTE LA CREAZIONE DEL FILE Fasce_Orarie.xml...</p>\n
						  </div>\n
						  </div>\n
						  </div>\n";
						  
				// ELIMINAZIONE DEI FILE FINORA CREATI
				unlink("Campi.xml");
				unlink("Fasce_Orarie.xml");
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
						  <p>PROBLEMA DURANTE LA CREAZIONE DEL FILE Campi.xml...</p>\n
						  </div>\n
						  </div>\n
						  </div>\n";
						  
			// ELIMINAZIONE DEI FILE FINORA CREATI
			unlink("Campi.xml");
		}
	?>
</body>
</html>