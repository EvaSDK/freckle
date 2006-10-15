<?php
/**
 * @package freckle
 * @package_version@ 2.0
 * menu include
 * Filename: menu.inc.php
 *
 */
?>
<ul>
	<li><ul>
	<li><h2>Général</h2></li>
	<li><a href='index.php?what=accueil' title="Accueil de Freckle">Accueil</a></li>
	<li><a href='index.php?what=search'  title="Rechercher des documents">Documents</a></li>
	<li><a href='index.php?what=tools'   title="Remarques et Conseil pour l'utilisation de Freckle">Outils et recommandations</a></li>
	</ul></li>

	<li><ul>
<?php
	if( $_SESSION['admin']==TRUE ) {
		echo "<li><h2>Administration</h2></li>";
		echo "\t\t<li><span style='color:red'>God Mode</span></li>\n";
		echo "\t\t<li><a href='managedb.php'>Quick Manage</a></li>\n";
		echo "\t\t<li><a href='management.php?logout=1'>Logout</a></li>\n";
	}
?>
	<li><h2>Webmaster</h2></li>
	<li><a href='management.php' title="Système de gestion de Freckle">Administration</a></li>
	<li><a href='http://www.esiee.fr' title="Site de l'école qui nous héberge généreusement">Hébergement</a></li>
	<li><a href='http://piartt.free.fr' title="Site du mainteneur précédent de Freckle">Mainteneur Précédent</a></li>
	</ul></li>
</ul>

