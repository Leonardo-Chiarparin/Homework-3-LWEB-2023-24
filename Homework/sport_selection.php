<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<?php
	require_once("./session_control.php");
	
	// NELL'EVENTUALITÀ IN CUI CI SIA UN TENTATIVO DI ACCESSO DA PARTE DI UN DIPENDENTE, BISOGNA REINDERIZZARLO ALLA PAGINA INIZIALE DELL'AREA RISERVATA 
	if($_SESSION["tipo_Utente"]!="C")
		header ("Location: pagina_riservata.php");
	
	if(isset($_GET["confirm"])){
		// UNA VOLTA VERIFICATA L'EFFETTIVA SELEZIONE DI UNA DELLE VOCI PROPOSTE, SI PROCEDE CON LA CREAZIONE DEL RELATIVO COOKIE. NELLO SPECIFICO, ESSO TORNERÀ UTILE PER AVERE MEMORIA DELLE PREFERENZE MOSTRATE DI RECENTE DALL'UTENTE 
		if(isset($_GET["gioco"])){
			setcookie("Disciplina_Scelta", $_GET["gioco"]);
			
			header("Location: prenotazione.php");	
		}
		else {
			echo "<div class='error_message'>\n
				  <div class='container_message'>\n
				  <div class='container_img'>\n
				  <img src=\"Immagini/exclamation-solid.svg\" alt='Immagine non Disponibile...'>\n
				  </div>\n
				  <div class='message'>\n
				  <p class='err'>ERRORE!</p>\n
				  <p>SPORT NON INDICATO...</p>\n
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
		
			<h1 class="saluti">Selezione della Disciplina!</h1>
			
			<form class="container_form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
				<div class="form">
					<div class="intestazione">
						<h2>
							Indicare la tipologia delle informazioni da mostrare
						</h2>
					</div>
					<div class="container_elenco_campi">
						<div class="intestazione_elenco_campi">
							<h3>Dettagli della Specifica</h3>
						</div>
						<div class="corpo_elenco_campi">
							<div class="container_sezione">
        						<div class="titolo_sezione"><p>Profilo Sportivo</p></div>
							</div>
							<div class="campo_radio">
								<div class="contenuto">
									<div class="item">
										<div class="titolo">
											<p>Gioco</p>
										</div>
										<div class="voci">
											<div class="voce">
												<p style="padding-right: 0.5%;">
													<?php
														echo "<input type=\"radio\" name=\"gioco\" value=\"Calcio a 5\" ";
														if(isset($_COOKIE["Disciplina_Scelta"]) && $_COOKIE["Disciplina_Scelta"]=="Calcio a 5")
															echo "checked=\"checked\" ";
														echo " />";
													?>
												</p>
												<p style="margin-top: -0.5%;">
													Calcio a 5
												</p>
											</div>
											<div class="voce">
												<p style="padding-right: 0.5%;">
													<?php
														echo "<input type=\"radio\" name=\"gioco\" value=\"Calcio a 6\" ";
														if(isset($_COOKIE["Disciplina_Scelta"]) && $_COOKIE["Disciplina_Scelta"]=="Calcio a 6")
															echo "checked=\"checked\" ";
														echo " />";
													?>
												</p>
												<p style="margin-top: -0.5%;">
													Calcio a 6
												</p>
											</div>
											<div class="voce">
												<p style="padding-right: 0.5%;">
													<?php
														echo "<input type=\"radio\" name=\"gioco\" value=\"Calcio a 8\" ";
														if(isset($_COOKIE["Disciplina_Scelta"]) && $_COOKIE["Disciplina_Scelta"]=="Calcio a 8")
															echo "checked=\"checked\" ";
														echo " />";
													?>
												</p>
												<p style="margin-top: -0.5%;">
													Calcio a 8
												</p>
											</div>
											<div class="voce">
												<p style="padding-right: 0.5%;">
													<?php
														echo "<input type=\"radio\" name=\"gioco\" value=\"Basket\" ";
														if(isset($_COOKIE["Disciplina_Scelta"]) && $_COOKIE["Disciplina_Scelta"]=="Basket")
															echo "checked=\"checked\" ";
														echo " />";
													?> 
												</p>
												<p style="margin-top: -0.5%;">
													Basket
												</p>
											</div>
											<div class="voce">
												<p style="padding-right: 0.5%;">
													<?php
														echo "<input type=\"radio\" name=\"gioco\" value=\"Tennis\" ";
														if(isset($_COOKIE["Disciplina_Scelta"]) && $_COOKIE["Disciplina_Scelta"]=="Tennis")
															echo "checked=\"checked\" ";
														echo " />";
													?>
												</p>
												<p style="margin-top: -0.5%;">
													Tennis
												</p>
											</div>
										</div>	
									</div>
								</div>
							</div>
							<div class="container_button">
								<button type="submit" name="confirm" value="conferma" class="confirm">Conferma!</button>
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