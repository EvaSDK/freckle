<h2 class="titre-page">Accueil</h2>

<div class="box">
	<h3>Qu'est ce que Freckle</h3>
	<p>
	<strong>Freckle</strong>, pour ceux qui ne le sauraient pas encore, est un site rassemblant tous
 les documents fournis par les élèves, les administratifs et les enseignants de l'ESIEE. Vous êtes <em>fortement encouragé</em> à contribuer en envoyant un mail avec vos documents à <a href="mailto:fr
eckle[at]esiee[point]fr">freckle [at] esiee [point] fr</a>.
	</p>
	<ul>
		<li><a href="index.php?what=docs">Accéder aux documents</a></li>
		<li><a href="index.php?what=tools">Outils et recommandations</a></li>
	</ul>
</div>

<div class="box">
	<h3>Nouveaux</h3>
	<h4>Du plus ancien au plus récent</h4>
<?php
	display_list_access( "search",0 );
	display_list_entries( "search",0 );
?>
	<p>Voir la description des unités ci-dessous.</p>
</div>

<div class="box">
	<h3>Listes des catégories</h3>
	<?php display_categorie(); ?>
</div>
