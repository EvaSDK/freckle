<?php
/**
 * @package freckle
 * @version 2.0
 */


/**
 * affiche le menu d'administration
 * @param string onglet qui doit �tre mis en valeur
 * @param int valeur de la page courante
 */
function adminMenu( $what, $current )
{
	echo "<h4>\n";
	echo "\t<div class='admin-datalist' id='".$what."'>\n";
	?>
	<a id='types'     href='management.php?what=types'     title="G�rer les types de documents">Types</a>
	<a id='fichiers'  href='management.php?what=fichiers'  title="G�rer les fichiers">Fichiers</a>
	<a id='categorie' href='management.php?what=categorie' title="G�rer les cat�gories de documents">Cat�gorie</a>
	<a id='affect'    href='management.php?what=affect'    title="Ranger les fichiers dans des cat�gories">Classer</a>
	<a id='defect'    href='management.php?what=defect'    title="Enlever des fichiers de l'interface">D�saffecter</a>
	</div>
	<?php
	display_list_access($what,$current);
	echo "</h4>\n";
}

?>
