<h2 class="titre-page">Documents</h2>
	
<div class="box">
	<h3>Trouver...</h3>
	<p>Afficher les documents de la cat�gorie:</p>
	<form action="index.php" method='GET'>
	<fieldset>
		<input type="hidden" name="action" value="docs" />
		<?php display_categorie_select(); ?>
		<input type="submit" value="Voir" />
	</fieldset>
	</form>
</div>

<div class="box">
	<?php
		$cat1 = $_GET["cat1"];
		$cat2 = $_GET["cat2"];
		if( $cat1 == 0 )
		{
			echo "<h3>Nouveaux</h3>\n";
			echo "<h4>Les 10 documents les plus r�cents</h4>\n";
			display_documents("last",0);
		} else {
			echo "<h3>R�sultats</h3>\n";
			display_documents($cat1,$cat2);
		}
	?>
	<p>Aidez-nous � am�liorer Freckle, vous avez une correction � apporter, un document est mal class�, une suggestion pour le site? <a href="mailto:freckle[at]esiee[point]fr">Envoyez-nous un mail</a></p>

</div>
