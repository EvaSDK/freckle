<?php

/* affiche le résultat d'une rechercher de documents
 * si $critere1=="last" et $critere2==0, affiche les 10 derniers documents
 * sinon affiche le résultat de la recherche
 */
function display_documents( $critere1, $critere2 )
{
	$link = dbconn();

	if( $critere1 == "last" ) {
		$sql = "SELECT * FROM reference,fichiers WHERE fichiers.id=reference.id_fichier ORDER BY id DESC LIMIT 10;";
	} else if ($critere2!=0) {
		$sql = "SELECT * FROM reference,fichiers WHERE reference.id_fichier=fichiers.id
						AND fichiers.id IN
	 (SELECT id_fichier FROM reference WHERE id_categorie1=$critere1 or id_categorie2=$critere1)
						AND fichiers.id IN
	 (SELECT id_fichier FROM reference WHERE id_categorie1=$critere2 or id_categorie2=$critere2);";
	} else {
		$sql = "SELECT * FROM reference,fichiers WHERE
						reference.id_fichier=fichiers.id
						and fichiers.id IN
	 (SELECT id_fichier FROM reference WHERE id_categorie1=$critere1 or id_categorie2=$critere1);";
	}

	$result = pg_query($link,"SELECT ccourt FROM categorie;");
	$ptr = pg_query($link,$sql);

	$cat = pg_fetch_all($result);

	if($critere1!="last") echo "<p>".pg_num_rows($ptr)." résultats pour cette recherche</p>\n";
	echo "<hr class='separateur'/>\n<table>\n";
	
	while($row = pg_fetch_object($ptr)) {
		$filename = str_replace("&", "&amp;", $row->url);
		$filename = substr(strrchr($filename,"-"),1);
		 
		if( strlen($filename) >= 35 ) {
			$filename = "<abbr title=\"$filename\">" . substr($filename,0,30) . "...</abbr>";
		}
		
		$lien = "/freckle/files/".str_replace("&", "&amp;", $row->url);
		echo "<tr>\n";
		echo "\t<td class='icon'><img src='".getIcon($row->url)."' alt='icon'/></td>";
		echo "<td>".$cat[($row->id_categorie1)-1]['ccourt']."</td><td>".$cat[($row->id_categorie2)-1]['ccourt']."</td>\n";
		echo "\t<td><a href='$lien' style='font-family: monospace; font-weight:normal;'>".$filename."</a></td>\n";
		echo "\t<td class='right'>".getCat($row->cat , $lcat)."</td>\n</tr>\n";
	}
	pg_close($link);
	echo "</table>\n<hr class='separateur'/>";
}

/* affiche le tableau des categories */
function display_categorie() {
	$link = dbconn();
	$ptr = pg_query($link,"SELECT * FROM categorie ORDER BY id;");
	$i=0;
	
	echo "<table>\n";
	while( $row = pg_fetch_object($ptr) )
	{
		if( $i==0 ) echo "<tr>\n";
		echo " <td class='right'>[$row->ccourt]</td>\n";
		echo " <td><a href='documents.php?see'>$row->clong</a></td>\n";
		if( $i==1 ) {
			echo "</tr>\n";
			$i=-1;
		}
		$i = $i+1;
	}
	pg_close($link);
	echo "</tr>\n</table>\n";
}



/* affiche 2 boites de séléction de catégorie */
function display_categorie_select()
{
	$link = dbconn();
	$ptr = pg_query($link,"SELECT * FROM categorie;");

	echo "<select name='cat1'>\n";
	while($row = pg_fetch_object($ptr)) {
		echo "	<option value='".$row->id."'>[$row->ccourt] $row->clong</option>\n";
	}
	echo "</select>";
	$ptr = pg_query("SELECT * FROM categorie ORDER BY ccourt");
	echo "<select name='cat2'>\n";
	echo "	<option value='0'>2ème critère</option>\n";
	while($row = pg_fetch_object($ptr)) {
		echo "	<option value='".$row->id."'>[$row->ccourt] $row->clong</option>\n";
	}
	echo "</select>\n";
	pg_close($link);
}

function display_types_select()
{
	$link = dbconn();
	$result = pg_query($link, "SELECT * FROM types;");

	echo "<select name='type'>\n";
	while( $row = pg_fetch_object($result) )
	{
		echo "\t<option value=".$row->id.">".$row->type."</option>\n";
	}
	echo "</select>\n";
	pg_close($link);
}
?>
