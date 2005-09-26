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
	<li><a href='index.php?what=accueil'>Accueil</a></li>
	<li><a href='index.php?what=search'>Documents</a></li>
	<li><a href='index.php?what=tools'>Outils et recommandations</a></li>
	</ul></li>

	<li><ul>
	<li><h2>Webmaster</h2></li>
<?php
	if( $_SESSION['admin']==TRUE ) {
		echo "\t\t<li><span style='color:red'>God Mode</span></li>\n";
		echo "\t\t<li><a href='management.php?logout=1'>Logout</a></li>\n";
	}
?>
	<li><a href='management.php'>Administration</a></li>
	<li><a href='http://www.esiee.fr'>Hébergement</a></li>
	<li><a href='http://piartt.free.fr'>Mainteneur Précédent</a></li>
	</ul></li>
</ul>

