<?php
	echo "<div class=\"barra_navigazione\"> \n
			<div class=\"container_logo\">
				<img class=\"logo\" src=\"Immagini/Barra.png\" alt=\"Logo non Disponibile...\" /> \n
			</div> \n
			<div class=\"container_menu\"> \n
				<div class=\"menu\"> \n
					<span class=\"voce_menu\"> \n
						<p title=\"Utente\">".$_SESSION["nome_Utente"]." ".$_SESSION["cognome_Utente"]."</p> \n
					</span> \n
				
					<span class=\"voce_menu\"> \n
						<a href=\"pagina_riservata.php\" title=\"Pagina Riservata\">Indietro</a> \n
					</span> \n
					
					<span class=\"voce_menu\"> \n
						<a href=\"login.php\" title=\"Pagina di Login\">Esci</a> \n
					</span>
				</div>
			</div>
		</div>";
?>