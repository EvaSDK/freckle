<?php

/* Freckle v2.0
 *
 * menu include
 * Filename: menu.inc.php
 *
 */

	echo "<ul>\n";
	echo "\t<li><h2>Général</h2>\n";
	echo "\t<ul>\n";
	echo "\t\t<li><a href='index.php?what=accueil'>Accueil</a></li>\n";
	echo "\t\t<li><a href='index.php?what=search'>Documents</a></li>\n";
	//echo "\t\t<li><a href='display.php?what=site'>Autres sites ESIEE</a></li>\n";
	echo "\t\t<li><a href='index.php?what=tools'>Outils et recommandations</a></li>\n";
	echo "\t</ul></li>\n";

	echo "\t<li><h2>Webmaster</h2>\n";
	echo "\t<ul>\n";

	if( $_SESSION['admin']==TRUE ) {
		echo "\t\t<li><span style='color:red'>God Mode</span></li>\n";
		echo "\t\t<li><a href='management.php?logout=1'>Logout</a></li>\n";
	}

	echo "\t\t<li><a href='management.php'>Administration</a></li>\n";
	echo "\t\t<li><a href='http://www.esiee.fr'>Hébergement</a></li>\n";
	echo "\t\t<li><a href='http://piartt.free.fr'>Mainteneur Précédent</a></li>\n";
	echo "\t</ul></li>\n";
	echo "\t</ul>\n";

 ?>
