<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<?php
	// STAMPA DEL MESSAGGIO DI POP UP INERENTE ALL'EFFETTIVO CARICAMENTO DELLA BASE DI DATI
	if(isset($_COOKIE["caricamento_Effettuato"]) && ($_COOKIE["caricamento_Effettuato"])){
		echo "<div class='confirm_message'>\n
			  <div class='container_message'>\n
			  <div class='container_img'>\n
			  <img src=\"Immagini/check-solid.svg\" alt='Immagine non Disponibile...'>\n
			  </div>\n
			  <div class='message'>\n
			  <p class='con'>OTTIMO!</p>\n
			  <p>CARICAMENTO EFFETTUATO CON SUCCESSO!</p>\n
			  </div>\n
			  </div>\n
			  </div>\n";
		
		// DISTRUZIONE DEL COOKIE CREATO IN PRECEDENZA
		setcookie("caricamento_Effettuato","",time()-60);
	}
?>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>CSS: Campo Sportivo dei Sogni</title>
	<link rel="icon" href="Immagini/Logo.png" />
	<link rel="stylesheet" href="CSS/style_index.css" type="text/css" />
</head>
<body>
	<div class="barra_navigazione">
		<div class="container_logo">
			<img class="logo" src="Immagini/Barra.png" alt="Logo non Disponibile..." />
		</div>
		<div class="container_menu">
			<div class="menu">
				<span class="voce_menu">
					<a href="#Introduzione" title="Introduzione">Introduzione</a>
				</span>
				
				<span class="voce_menu">
					<a href="#Storia" title="Storia">Storia</a>
				</span>
				
				<span class="voce_menu">
					<a href="#Offerte" title="Offerte">Offerte</a>
				</span>
				
				<span class="voce_menu">
					<a href="#Contatti" title="Contatti">Contatti</a>
				</span>
				
				<span class="voce_menu">
					<a href="login.php" title="Schermata di Login">Accedi</a>
				</span>
				
			</div>
		</div>
	</div>
	<div class="container_corpo">
		<div class="container_principale">
		
			<p id="Introduzione" class="spazio_link"></p>
		
			<h1 class="introduzione">Introduzione</h1>
			<p>
				Siete pronti a immergervi in un mondo di <strong>passione</strong>, <strong>energia</strong> e <strong>divertimento</strong> sportivo? Benvenuti nel nostro sito dedicato ai <strong>campi sportivi</strong>, un luogo dove la vostra passione per lo sport prende vita! Con un'ampia selezione di impianti sportivi di alta qualit&agrave; e servizi su misura, siamo qui per offrirvi un'esperienza sportiva indimenticabile.
				Che tu sia un atleta professionista in cerca di un luogo per allenarti o semplicemente un appassionato che desidera trascorrere del tempo attivo con gli amici, abbiamo tutto ci&ograve; di cui hai bisogno. Dai campi di calcio a quelli da tennis, dai campi da basket alle strutture per sport all'aperto, la nostra gamma di opzioni ti permetter&agrave; di praticare il tuo sport preferito in un ambiente accogliente e sicuro. Inoltre, i nostri servizi aggiuntivi, come spogliatoi attrezzati, aree picnic e punti ristoro, garantiscono un'esperienza completa per te e i tuoi compagni di squadra. Esplora il nostro sito, prenota il tuo campo e preparati a vivere momenti indimenticabili!
			</p>
			
			<div class="container_presentazione">
				<div class="slider_immagini">
					<div class="cornice">
						<div class="elenco_immagini">
							<div class="slide">
								<img src="Immagini/Centro_Sportivo.jpg" alt="Immagine non Disponibile..." />
							</div>
							<div class="slide">
								<img src="Immagini/Campo_Calcio.jpg" alt="Immagine non Disponibile..." />
							</div>
							<div class="slide">
								<img src="Immagini/Campo_Basket.jpg" alt="Immagine non Disponibile..." />
							</div>
							<div class="slide">
								<img src="Immagini/Campo_Tennis.jpg" alt="Immagine non Disponibile..." />
							</div>
						</div>
						<div class="slider_point">
							<span class="point" id="point_1"></span>
							<span class="point" id="point_2"></span>
							<span class="point" id="point_3"></span>
							<span class="point" id="point_4"></span>
						</div>
					</div>
				</div>
				<div class="container_descrizione">
					<div class="descrizione">
						<div class="testo">
							<h1>Campus Sportivo dei Sogni</h1>
							<p>
								Il Campus Sportivo dei Sogni cura tutta l’area del Circolo Sportivo, offrendo ben 18 campi di cui:
							</p>
							
							<ul>
								<li>
									<div class="list_voice">
										<img src="Immagini/futbol-solid.svg" alt="Immagine non Disponibile..." />
										<p><strong>8 Campi da Calcio (a 5, 6 e 8 giocatori) con manto erboso sintetico</strong></p>
									</div>
									<div class="list_voice">
										<img src="Immagini/basketball-solid.svg" alt="Immagine non Disponibile..." />
										<p><strong>2 Campi da Pallacanestro in parquet</strong></p>
									</div>
									<div class="list_voice">
										<img src="Immagini/table-tennis-paddle-ball-solid.svg" alt="Immagine non Disponibile..." />
										<p><strong>8 Campi da Tennis in cemento, di cui 4 coperti</strong></p>
									</div>
								</li>
							</ul>
								
							<p style="padding-top: 1.5%;">
								Il Campus Sportivo dei Sogni organizza e ospita tutto l’anno diversi tornei ed eventi sportivi a livello nazionale e internazionale, rivolti ad appassionati di tutte le et&agrave;. Sempre alla ricerca dei migliori standard qualitativi, il complesso &egrave; in continua espansione offrendo campi e strutture in linea con i trend e le novit&agrave; del mondo sportivo.
								<br />
								<br />
								Per <strong>info</strong> e <strong>prenotazioni</strong>: tel. 3451772123, 3337279141
							</p>
						</div>
					</div>
				</div>
			</div>
			
			<p id="Storia" class="spazio_link"></p>
			
			<h1 class="storia">Storia</h1>
			<p>
				Nel 1990, a Latina Scalo, sorse un ambizioso progetto: la creazione di un plesso sportivo destinato a diventare il cuore pulsante dell'attivit&agrave; sportiva e ricreativa della comunit&agrave; locale, nonch&egrave; un vanto per il panorama nazionale. Questo complesso, che all'inizio era poco pi&ugrave; di un'idea, ha preso forma grazie alla determinazione e all'impegno dei cittadini, delle autorit&agrave; locali e di numerosi sponsor.
				L'inaugurazione del plesso, avvenuta nel cuore dell'estate del 1990, fu un evento festoso e pieno di speranza. Il <strong>Campus Sportivo dei Sogni</strong>, come fu chiamato, era composto da diverse strutture: un grande campo da calcio, un palazzetto dello sport dedicato principalmente alla pallacanestro, campi da tennis e aree verdi per il relax e il divertimento.
				Negli anni successivi, il complesso divenne il luogo di incontro per atleti di tutte le et&agrave; e livelli di abilit&agrave;. Le squadre disputavano le gare nelle strutture preposte, portando rapidamente all'organizzazione dei primi tornei locali e regionali.
				Campus Sportivo dei Sogni divenne anche un punto di riferimento per eventi culturali e sociali. Di fatto, festival sportivi si tenevano regolarmente nei suoi spazi, contribuendo a creare un senso di comunit&agrave; tra i vari partecipanti.
				Con il passare degli anni, il plesso sportivo ha continuato a evolversi, aggiungendo nuove strutture e servizi per soddisfare le crescenti esigenze del settore.
			</p>
			
			<div class="album_foto">
				<div class="prima_colonna"></div>
				<div class="foto">
					<div class="sfondo">
						<div class="container_immagini">
							<div class="immagine" style="transform: rotate(-2.5deg);">
								<img style="filter: sepia(90%);" src="Immagini/Campo_Storico.jpg" alt="Immagine non Disponibile..." />
								<div class="memories">
									<p>Fondazione: 06-06-1990</p>
								</div>
							</div>
							<div class="immagine" style="margin-top: 10%;transform: rotate(2.5deg);">
								<img src="Immagini/Prima_Partita_Tennis.jpg" alt="Immagine non Disponibile..." />
								<div class="memories">
									<p>I° Tennis Masters Cup: 18-11-2002</p>
								</div>
							</div>
						</div>
						<div class="container_immagini">
							<div class="immagine" style="transform: rotate(-2.5deg);">
								<img style="filter: sepia(90%);" src="Immagini/Prima_Partita_Calcio.jpg" alt="Immagine non Disponibile..." />
								<div class="memories">
									<p>Partita del "Secolo": 08-06-1990</p>
								</div>
							</div>
							<div class="immagine" style="margin-top: 10%;">
								<img src="Immagini/Primo_Evento_Internazionale.jpg" alt="Immagine non Disponibile..." />
								<div class="memories">
									<p>I° Evento Internazionale: 09-07-2006</p>
								</div>
							</div>
						</div>
						<div class="container_immagini">
							<div class="immagine" style="transform: rotate(2.5deg);">
								<img src="Immagini/Prima_Partita_Basket.jpg" alt="Immagine non Disponibile..." />
								<div class="memories">
									<p>The First Slam Dunk: 21-02-1997</p>
								</div>
							</div>
							<div class="immagine" style="margin-top: 10%;transform: rotate(-2.5deg);">
								<img src="Immagini/Presente.jpg" alt="Immagine non Disponibile..." />
								<div class="memories">
									<p>Presente: 27-03-2024</p>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			
			<p id="Offerte" class="spazio_link"></p>
			
			<h1 class="offerte">Offerte</h1>
			<p style="padding-bottom: 1%;">
				Benvenuti nella nostra sezione dedicata alle prenotazioni dei campi da calcetto, tennis e basket! Presso il nostro centro sportivo, abbiamo a cuore offrirvi un'ampia gamma di opzioni e vantaggi per soddisfare le vostre esigenze sportive.
			</p>
			<div class="pacchetti">
				<h2 style="margin-bottom: 1%;">
					Pacchetti Multisport		
				</h2>
				<ul>
					<li>
						<div class="offerta">
							<img src="Immagini/dollar-sign-solid.svg" alt="Immagine non Disponibile..." />
							<p>
								<strong>Prenota e Risparmia:</strong> Approfitta dei nostri pacchetti speciali per prenotare slot per calcetto, tennis e basket e risparmia sulle tariffe standard;
							</p>
						</div>
					</li>
					<li>
						<div class="offerta">
							<img src="Immagini/people-group-solid.svg" alt="Immagine non Disponibile..." />
							<p>
								<strong>Offerte per Gruppi:</strong> Organizza tornei multisportivi con i tuoi amici, colleghi o squadre e beneficiate di tariffe scontate per gruppi numerosi;
							</p>
						</div>
					</li>
					<li>
						<div class="offerta">
							<img src="Immagini/tag-solid.svg" alt="Immagine non Disponibile..." />
							<p>
								<strong>Promozioni Stagionali:</strong> Mantieniti aggiornato sulle nostre promozioni stagionali che offrono sconti esclusivi e vantaggi durante tutto l'anno.
							</p>
						</div>
					</li>				
				</ul>
				
				<h3 style="margin-top: 1.5%; margin-bottom: 1%;">
					Servizi Extra		
				</h3>
				<ul>
					<li>
						<div class="offerta">
							<img src="Immagini/lightbulb-solid.svg" alt="Immagine non Disponibile..." />
							<p>
								<strong>Campo Illuminato:</strong> Gioca anche di sera grazie ai nostri campi illuminati per calcetto, tennis e basket, per partite notturne indimenticabili;
							</p>
						</div>
					</li>
					<li>
						<div class="offerta">
							<img src="Immagini/couch-solid.svg" alt="Immagine non Disponibile..." />
							<p>
								<strong>Area Relax e Spogliatoi:</strong> Dopo l'allenamento o la partita, rilassati nelle nostre moderne aree relax e usufruisci dei nostri spogliatoi dotati di docce e servizi igienici;
							</p>
						</div>
					</li>
					<li>
						<div class="offerta">
							<img src="Immagini/square-parking-solid.svg" alt="Immagine non Disponibile..." />
							<p>
								<strong>Parcheggio Gratuito:</strong>  Approfitta del comodo parcheggio gratuito disponibile presso il nostro centro sportivo, per una visita senza problemi.
							</p>
						</div>
					</li>				
				</ul>
				
				<div class="container_scelte">
					<div class="scelta">
						<div class="anteprima">
							<img src="Immagini/Offerta_Calcio.png" style="width: 125%;" alt="Immagine non Disponibile..." />
						</div>
						<div class="paragrafo">
							<h2>Calcio</h2>
							<p>
								Esplora le nostre strutture di calcio all'avanguardia, dotate di campi in erba sintetica di alta qualità. Scopri i nostri campi illuminati per partite notturne emozionanti con i tuoi amici o compagni di squadra. Offriamo anche opzioni per allenamenti individuali o di gruppo, con prezzi competitivi e tariffe flessibili per ogni esigenza. 
							</p>
							<form action="offerte.html#Calcio" method="post">
								<p><button type="submit" class="dettagli">Scopri di pi&ugrave;!</button></p>
							</form>
						</div>
					</div>
					<div class="scelta">
						<div class="anteprima">
							<img src="Immagini/Offerta_Basket.png" style="margin-top: 20%;width: 140%;" alt="Immagine non Disponibile..." />
						</div>
						<div class="paragrafo">
							<h2>Basket</h2>
							<p>
								Entra nel campo da basket e lasciati trasportare dalla passione per questo sport dinamico. I nostri campi sono progettati per garantire un'esperienza di gioco eccellente, con superfici di gioco di qualità superiore e strutture ben mantenute. Prenota il tuo campo e vivi momenti indimenticabili con tutti i tuoi compagni di squadra.
							</p>
							<form action="offerte.html#Basket" method="post">
								<p><button type="submit" class="dettagli">Scopri di pi&ugrave;!</button></p>
							</form>
						</div>
					</div>
					<div class="scelta">
						<div class="anteprima">
						  	<img src="Immagini/Offerta_Tennis.png" style="width: 180%;margin-top: 5%;" alt="Immagine non Disponibile..." />
						</div>
						<div class="paragrafo">
							<h2>Tennis</h2>
							<p>
								Per gli amanti del tennis, offriamo una vasta gamma di campi in cemento. Scopri le nostre strutture moderne e ben attrezzate, ideali per giocatori di tutti i livelli e di tutte le et&agrave;.  
								Esplora le opzioni disponibili, controlla le tariffe e prenota il tuo campo preferito oggi stesso! Siamo qui per rendere la tua esperienza sportiva indimenticabile.
							</p>
							<form action="offerte.html#Tennis" method="post">
								<p><button type="submit" class="dettagli">Scopri di pi&ugrave;!</button></p>
							</form>
						</div>
					</div>
				</div>
				
				<p style="margin-top: 3%;">
					Non perdere l'occasione di vivere esperienze sportive coinvolgenti e divertenti. Prenota subito i tuoi slot preferiti per calcetto, tennis e basket e unisciti a noi per giocare, allenarti e divertirti. Contattaci oggi stesso o consulta le schede precedenti per maggiori informazioni sulle tariffe e sulle nostre offerte personalizzate. Siamo qui per rendere la tua esperienza sportiva indimenticabile!
				</p>
	
				<form action="login.php" method="post">
					<p><button type="submit" class="prenotazione">Prenota Ora!</button></p>
				</form>				
			</div>
			
			<p id="Contatti" class="spazio_link"></p>
			
			<h1 class="contatti">Contatti</h1>
			
			<div class="container_contatti">
				<div class="mappa">		
					<a title="Campus Sportivo dei Sogni" href="https://www.google.com/maps/dir/41.2325301,13.0883596/Via+Gorgia,+7,+04100+Latina+Scalo+LT/@41.3802546,12.8448461,11z/data=!3m1!4b1!4m9!4m8!1m1!4e1!1m5!1m1!1s0x132572e229e149ff:0x60e8c134ae2c9af7!2m2!1d12.9461628!2d41.5276836?entry=ttu">
						<img src="Immagini/Mappa.jpg" alt="Immagine non Disponibile..." />
					</a>
				</div>
				<div class="descrizione_contatti">
					<div class="testo_contatti">
						<h2>Informazioni</h2>
						<p style="margin-top: 2.5%;">
							Siamo aperti tutti i giorni, tutto l’anno, con ampio parcheggio a disposizione. Potete trovarci in <strong>Via Gorgia, n.7 - 04013 Latina Scalo</strong>.
						</p>
						
						<h3 style="margin-bottom: 1%;">
							Recapiti e Social
						</h3>
						
						<ul>
							<li>
								<div class="info">
									<img src="Immagini/phone-volume-solid.svg" alt="Immagine non Disponibile..." />
									<p>
										<strong>Ettore: 3451772123</strong>
									</p>
								</div>
							</li>
							<li>
								<div class="info" style="margin-top: -2%;">
									<img src="Immagini/envelope-solid.svg" alt="Immagine non Disponibile..." />
									<p>
										<strong>cantile.2026562@studenti.uniroma1.it</strong> 
									</p>
								</div>
							</li>
							<li>
								<div class="info" style="margin-top: -2%;">
									<img src="Immagini/instagram.svg" alt="Immagine non Disponibile..." />
									<p>
										<strong>ettorecantile</strong> 
									</p>
								</div>
							</li>
							<li>
								<div class="info">
									<img src="Immagini/phone-volume-solid.svg" alt="Immagine non Disponibile..." />
									<p>
										<strong>Leonardo: 3337279141</strong>
									</p>
								</div>
							</li>
							<li>
								<div class="info" style="margin-top: -2%;">
									<img src="Immagini/envelope-solid.svg" alt="Immagine non Disponibile..." />
									<p>
										<strong>chiarparin.2016363@studenti.uniroma1.it</strong> 
									</p>
								</div>
							</li>
							<li>
								<div class="info" style="margin-top: -2%;margin-bottom: -2%;">
									<img src="Immagini/instagram.svg" alt="Immagine non Disponibile..." />
									<p>
										<strong>leonardochiarparin</strong> 
									</p>
								</div>
							</li>
						</ul>
					</div>
				</div>
			</div>
			<div class="blank_space"></div>
		</div>
	</div>
	<div class="footer">
		<p>
			Ettore Cantile e Leonardo Chiarparin, Linguaggi per il Web  a.a. 2023-2024
		</p>
	</div>
</body>
</html>