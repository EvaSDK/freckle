<h2 class="titre-page">Documents</h2>
  
<div class="box">
	<h3>Trouver...</h3>
	<p>Afficher les documents de la catégorie:</p>
	<form action="#" method='post'>
	<fieldset>
		<?php display_categorie_select(); ?>
		<input type="submit" name="Submit2" value="Voir" />
	</fieldset>
	</form>
</div>

<div class="box">
	<?php
		$cat1=$_POST[ "cat1" ];
		$cat2=$_POST[ "cat2" ];
		if( $cat1 == 0 )
		{
			echo "<h3>Nouveaux</h3>\n";
			echo "<h4>Les 10 documents les plus récents</h4>\n";
			display_documents("last",0);
		} else {
			echo "<h3>Résultats</h3>\n";
			display_documents($cat1,$cat2);
		}
	?>
	<p>Aidez-nous à améliorer Freckle, vous avez une correction à apporter, un document est mal classé, une suggestion pour le site? <a href="mailto:freckle[at]esiee[point]fr">Envoyez-nous un mail</a></p>

</div>
