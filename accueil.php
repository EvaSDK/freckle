<h2 class="titre-page">Accueil</h2>

<div class="box">
	<h3>Qu'est ce que Freckle</h3>
	<p>
	<strong>Freckle</strong>, pour ceux qui ne le sauraient pas encore, est un site rassemblant tous
 les documents fournis par les �l�ves, les administratifs et les enseignants de l'ESIEE. Vous �tes <em>fortement encourag�</em> � contribuer en envoyant un mail avec vos documents � <a href="mailto:fr
eckle[at]esiee[point]fr">freckle [at] esiee [point] fr</a>.
	</p>
	<ul>
		<li><a href="index.php?action=docs">Acc�der aux documents</a></li>
		<li><a href="index.php?action=tools">Outils et recommandations</a></li>
<!--		<li><a href="display.php?what=site">Les autres sites de l'ESIEE</a></li> -->
	</ul>
</div>

<div class="box">
	<h3>Nouveaux</h3>
	<h4>Les 10 documents les plus r�cents</h4>
	<table>
		<?php display_documents( "last", 0 ); ?>
	</table>
	<p>Voir la description des unit�s ci-dessous.</p>
</div>

<div class="box">
	<h3>Listes des cat�gories</h3>
	<?php display_categorie(); ?>
</div>
