<?php

/* affiche le résultat d'une rechercher de documents
 * si $critere1=="last" et $critere2==0, affiche les 10 derniers documents
 * sinon affiche le résultat de la recherche
 */
function display_documents( $critere1, $critere2 )
{
	$link = db_connect();

	if( $critere1 == "last" ) {
		$sql = "SELECT * FROM reference,fichiers WHERE fichiers.id=reference.id_fichier ORDER BY id DESC LIMIT 10;";
	} else if ($critere2!=0) {
		$sql = "SELECT * FROM reference,fichiers WHERE reference.id_fichier=fichiers.id AND ((id_categorie1=$critere1 AND id_categorie2=$critere2) OR (id_categorie1=$critere2 AND id_categorie2=$critere1))";
	} else {
		$sql = "SELECT * FROM reference,fichiers WHERE reference.id_fichier=fichiers.id AND (id_categorie1=$critere1 OR id_categorie2=$critere1)";
	}

	$result = db_query($link,"SELECT ccourt FROM categorie;");
	$ptr = db_query($link,$sql);

	$cat = pg_fetch_all($result);

	if($critere1!="last")
		echo "<p>".db_num_rows($ptr)." résultats pour cette recherche</p>\n";
	echo "<hr class='separateur'/>\n<table>\n";
	
	while($row = db_fetch_object($ptr)) {
		$filename = str_replace("&", "&amp;", $row->url);
		$filename = substr(strrchr($filename,"-"),1);
		 
		if( strlen($filename) >= 35 ) {
			$filename = "<abbr title=\"$filename\">" . substr($filename,0,30) . "...</abbr>";
		}
		
		$lien = "/freckle/files/".str_replace("&", "&amp;", $row->url);
		echo "<tr>\n";
		echo "\t<td class='icon'><img src='".getIcon($row->url)."' alt='icon'/></td>";
		echo "\t<td>".$cat[($row->id_categorie1)-1]['ccourt']."</td><td>".$cat[($row->id_categorie2)-1]['ccourt']."</td>\n";
		echo "\t<td><a href='$lien' style='font-family: monospace; font-weight:normal;'>".$filename."</a></td>\n";
		echo "</tr>\n";
	}
	db_close($link);
	echo "</table>\n<hr class='separateur'/>";
}

/* affiche le tableau des categories */
function display_categorie() {
	$link = db_connect();
	$ptr = db_query($link,"SELECT * FROM categorie ORDER BY id;");
	$i=0;
	
	echo "<table>\n";
	while( $row = db_fetch_object($ptr) )
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
	db_close($link);
	echo "</tr>\n</table>\n";
}



/* affiche 2 boites de séléction de catégorie */
function display_categorie_select()
{
	$link = db_connect();
	$ptr = db_query($link,"SELECT * FROM categorie;");

	echo "<select name='cat1'>\n";
	while($row = db_fetch_object($ptr)) {
		echo "	<option value='".$row->id."'>[$row->ccourt] $row->clong</option>\n";
	}
	echo "</select>";
	$ptr = db_query($link, "SELECT * FROM categorie ORDER BY ccourt");
	echo "<select name='cat2'>\n";
	echo "	<option value='0'>2ème critère</option>\n";
	while($row = db_fetch_object($ptr)) {
		echo "	<option value='".$row->id."'>[$row->ccourt] $row->clong</option>\n";
	}
	echo "</select>\n";
	db_close($link);
}

function display_types_select()
{
	$link = db_connect();
	$result = db_query($link, "SELECT * FROM types;");

	echo "<select name='type'>\n";
	while( $row = db_fetch_object($result) )
	{
		echo "\t<option value=".$row->id.">".$row->type."</option>\n";
	}
	echo "</select>\n";
	db_close($link);
}

/* renvoie l'icône correspondant à l'extension du fichier */
function getIcon ( $file ) {
	$pt = strrpos($file, ".");

	if ($pt != FALSE) {
		$file_ext = substr($file, $pt + 1, strlen($file) - $pt - 1);
		switch ($file_ext) {
			case "gif": $image = "_icon/i_gif.gif"; break;
			case "htm": $image = "_icon/i_htm.gif"; break;
			case "html": $image = "_icon/i_htm.gif"; break;
			case "ppt": $image = "_icon/i_ppt.gif"; break;
			case "xls": $image = "_icon/i_xls.gif"; break;
			case "doc": $image = "_icon/i_doc.gif"; break;
			case "pdf": $image = "_icon/i_pdf.gif"; break;
			case "bmp": $image = "_icon/i_img.gif"; break;
			case "rar": $image = "_icon/i_rar.gif"; break;
			case "ace": $image = "_icon/i_ace.gif"; break;
			case "jpg": $image = "_icon/i_jpg.gif"; break;
			case "mp3": $image = "_icon/i_mp3.gif"; break;
			case "exe": $image = "_icon/i_pgm.gif"; break;
			case "txt": $image = "_icon/i_txt.gif"; break;
			case "wav": $image = "_icon/i_wav.gif"; break;
			case "zip": $image = "_icon/i_zip.gif"; break;
			default: $image = "_icon/i_other.gif";
		}
		return($image);
	}
}

?>
