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
	<li><h2>G�n�ral</h2></li>
	<li><a href='index.php?what=accueil' title="Accueil de Freckle">Accueil</a></li>
	<li><a href='index.php?what=search'  title="Rechercher des documents">Documents</a></li>
	<li><a href='index.php?what=tools'   title="Remarques et Conseil pour l'utilisation de Freckle">Outils et recommandations</a></li>
	</ul></li>

	<li><ul>
	<li><h2>Webmaster</h2></li>
<?php
	if( $_SESSION['admin']==TRUE ) {
		echo "\t\t<li><span style='color:red'>God Mode</span></li>\n";
		echo "\t\t<li><a href='management.php?logout=1'>Logout</a></li>\n";
	}
?>
	<li><a href='management.php' title="Syst�me de gestion de Freckle">Administration</a></li>
	<li><a href='http://www.esiee.fr' title="Site de l'�cole qui nous h�berge g�n�reusement">H�bergement</a></li>
	<li><a href='http://piartt.free.fr' title="Site de mainteneur pr�c�dent de Freckle">Mainteneur Pr�c�dent</a></li>
	</ul></li>
</ul>

