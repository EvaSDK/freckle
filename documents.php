<h2 class="titre-page">Documents</h2>
	
<div class="box">
	<h3>Trouver...</h3>
	<p>Afficher les documents de la cat�gorie:</p>
	<form action="index.php" method='get'>
	<fieldset>
		<input type="hidden" name="what" value="search" />
		<input type="hidden" name="current" value="0" />
		<?php display_categorie_select(); ?>
		<input type="submit" value="Voir" />
	</fieldset>
	</form>
</div>

<div class="box">
	<?php
		
		$cat1 = isset($_GET["cat1"]) ? $_GET["cat1"] : '';
		$cat2 = isset($_GET["cat2"]) ? $_GET["cat2"] : '';
		
		$current = isset($_GET['current']) ? $_GET['current'] : 0;
		 
		if( $cat1==0 )
		{
			echo "<h3>Nouveaux</h3>\n";
			echo "<h4>Du plus r�cent au plus ancien</h4>\n";
		} else {
			echo "<h3>R�sultats</h3>\n";
		}
		description_critere();
		display_list_access( "search", $current );
		display_list_entries( "search", $current );
	?>
	<p>Aidez-nous � am�liorer Freckle, vous avez une correction � apporter, un document est mal class�, une suggestion pour le site? <a href="mailto:freckle[at]esiee[point]fr" title='Addresse mail pour joindre les mainteneurs de Freckle'>Envoyez-nous un mail</a></p>

</div>
