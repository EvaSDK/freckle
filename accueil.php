<h2 class="titre-page">Accueil</h2>

<div class="box">
	<h3>Qu'est ce que Freckle</h3>
	<p>
	<strong>Freckle</strong> est un site rassemblant des documents de toute sorte 
fournis par les élèves, les administratifs et les enseignants de l'ESIEE. Vous êtes <em>fortement encouragés</em> à contribuer en envoyant un e-mail avec vos documents à <a href="mailto:fr
eckle[at]esiee[point]fr" title="Addresse mail pour joindre les mainteneurs de Freckle">freckle [at] esiee [point] fr</a>.
	</p>
	<ul>
		<li><a href="index.php?what=search" title="Rechercher des documents">Accéder aux documents</a></li>
		<li><a href="index.php?what=tools"  title="Remarques et Conseil pour l'utilisation de Freckle">Outils et recommandations</a></li>
		<li><a href='https://clubnix.esiee.fr/projects/freckle/newticket' title='Rapporter un nouveau bug'>Un problème ? signalez le.</a>
	</ul>
</div>

<div class="box">
	<h3>Nouveaux</h3>
	<h4>Du plus ancien au plus récent</h4>
	<?php
	display_list_access( "search",0 );
	display_list_entries( "search",0 );
	?>
	<p>Aidez-nous à améliorer Freckle, vous avez une correction à apporter, un document est mal classé, une suggestion pour le site? <a href="mailto:freckle[at]esiee[point]fr" title="Addresse mail pour joindre les mainteneurs de Freckle">Envoyez-nous un mail</a></p>
	
</div>

<div class="box">
	<h3>Listes des catégories</h3>
	<?php display_categorie(); ?>
</div>
