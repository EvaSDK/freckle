<h2 class="titre-page">Accueil</h2>

<div class="box">
	<h3>Qu'est ce que Freckle</h3>
	<p>
	<strong>Freckle</strong> est un site rassemblant des documents de toute sorte 
fournis par les �l�ves, les administratifs et les enseignants de l'ESIEE. Vous �tes <em>fortement encourag�s</em> � contribuer en envoyant un e-mail avec vos documents � <a href="mailto:fr
eckle[at]esiee[point]fr" title="Addresse mail pour joindre les mainteneurs de Freckle">freckle [at] esiee [point] fr</a>.
	</p>
	<ul>
		<li><a href="index.php?what=search" title="Rechercher des documents">Acc�der aux documents</a></li>
		<li><a href="index.php?what=tools"  title="Remarques et Conseil pour l'utilisation de Freckle">Outils et recommandations</a></li>
		<li><a href='https://clubnix.esiee.fr/projects/freckle/newticket' title='Rapporter un nouveau bug'>Un probl�me ? signalez le.</a>
	</ul>
</div>

<div class="box">
	<h3>Nouveaux</h3>
	<h4>Du plus ancien au plus r�cent</h4>
	<?php
	display_list_access( "search",0 );
	display_list_entries( "search",0 );
	?>
	<p>Aidez-nous � am�liorer Freckle, vous avez une correction � apporter, un document est mal class�, une suggestion pour le site? <a href="mailto:freckle[at]esiee[point]fr" title="Addresse mail pour joindre les mainteneurs de Freckle">Envoyez-nous un mail</a></p>
	
</div>

<div class="box">
	<h3>Listes des cat�gories</h3>
	<?php display_categorie(); ?>
</div>
