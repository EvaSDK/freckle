<?php
/**
 * fonctions d'utilisation générale
 *
 * @package freckle
 * @version v2.0
 */


/**
 * Affiche les catégories dans un tableau
 */
function display_categorie()
{
	global $db;
	/*$result = $db->getAll("SELECT * FROM categorie ORDER BY id",DB_FETCHMODE_ASSOC);*/
	$result = $db->getAll("SELECT id,count(id_fichier) as docs, ccourt,clong FROM categorie,reference WHERE id=id_categorie GROUP BY id,ccourt,clong", DB_FETCHMODE_ASSOC);
	$i=0;

/*
	echo "<pre>";
	print_r( $result );
	echo "</pre>";
*/
	echo "<table>\n";
	foreach( $result as $key=>$value )
	{
		if( $i==0 ) echo "<tr>\n";
		echo "\t<td class='right'>[".$value['ccourt']."]</td>\n";
		echo "\t<td><a href='index.php?what=search&amp;cat1=".$value['id']."&amp;cat2=' title=\"Catégorie: ".$value['clong']." contient ".$value["docs"]." documents.\">".$value['clong']." (".$value["docs"].")</a></td>\n";
		if( $i==1 )
		{
			echo "</tr>\n";
			$i=-1;
		}
		$i = $i+1;
	}

	/* rajouter un </tr> si le nombre d'elements est impaire */
	if ( $i == 1 )
		echo "\t<td colspan='2'>&nbsp;</td>\n</tr>\n";
	
	echo "</table>\n";
}


/**
 * Affiche 2 boites de sélection de catégorie
 */
function display_categorie_select()
{
	global $db;
	
	$result = $db->getAll("SELECT * FROM categorie;",DB_FETCHMODE_ASSOC);

	/* on récupère les valeurs de la recherche */
	$cat1 = isset($_GET['cat1']) ? $_GET['cat1'] : '';
	$cat2 = isset($_GET['cat2']) ? $_GET['cat2'] : '';

	/* premier <select> */
	echo "<select name='cat1'>\n";
	foreach( $result as $k=>$v )
	{
		echo "\t<option value='".$v['id']."'";
		if( $cat1==$v['id'] )
			echo " selected='selected'";
		echo ">[".$v['ccourt']."] ".$v['clong']."</option>\n";
	}
	echo "</select>";
	
	$result = $db->getAll("SELECT * FROM categorie ORDER BY ccourt",DB_FETCHMODE_ASSOC);

	/* deuxième <select> */
	echo "<select name='cat2'>\n";
	echo "<option value='0'>Second Critère</option>\n";
	foreach( $result as $k=>$v )
	{
		echo "\t<option value='".$v['id']."'";
		if( $cat2==$v['id'] )
			echo " selected='selected'";
		echo ">[".$v['ccourt']."] ".$v['clong']."</option>\n";
	}
	echo "</select>\n";
}


/**
 * Affiche un <select> pour les types
 */
function display_types_select()
{
	global $db;
	$result = $db->getAll("SELECT * FROM types", DB_FETCHMODE_ASSOC);

	echo "<select name='type'>\n";
	foreach( $result as $key=>$value )
	{
		echo "\t<option value=".$value['id'].">".$value['type']."</option>\n";
	}
	echo "</select>\n";
}


/**
 * renvoie l'icône correspondant à l'extension du fichier
 * @param string est le nom du fichier dans on veut l'extension
 * @return string url vers l'icône correspondant au type de fichier
 */
function getIcon ( $file )
{
	$pt = strrpos($file, ".");

	if ($pt != FALSE)
	{
		$file_ext = substr($file, $pt + 1, strlen($file) - $pt - 1);
		switch ($file_ext)
		{
			/* images */
			case "avi":
			case "gif":
			case "bmp":
			case "jpg": $image = "images/icones/image.gif"; break;

			/* web */
			case "htm":
			case "html": $image = "images/icones/web.gif"; break;

			/* documents */
			case "ppt": $image = "images/icones/impress.gif"; break;
			case "xls": $image = "images/icones/calc.gif"; break;
			case "sxw":
			case "doc": $image = "images/icones/writer.gif"; break;
			case "pdf": $image = "images/icones/pdf.gif"; break;
			case "txt": $image = "images/icones/text.gif"; break;

			/* archives */
			case "gz":
			case "bz2":
			case "tbz2":
			case "tgz":
			case "rar": 
			case "ace":
			case "zip": $image = "images/icones/archives.gif"; break;

			/* autres */
			default: $image = "images/icones/unknown.gif";
		}
		return $image;
	}
}


/**
 * Affiche un rappel de la requête sélectionnée
 */
function description_critere()
{
	global $db;
	
	$cat1 = isset($_GET['cat1']) ? $_GET['cat1'] : '';
	$cat2 = isset($_GET['cat2']) ? $_GET['cat2'] : '';

	if( $cat1!='' )
		echo "<h4>Recherche des documents en ";
	
	if( $cat1!='' )
	{
		$res = $db->getAll("SELECT ccourt FROM categorie where id='$cat1'",DB_FETCHMODE_ASSOC);
		echo $res[0]['ccourt'];
	}
	if( $cat2!='' and $cat2!=0 )
	{
		echo " et ";
		$res = $db->getAll("SELECT ccourt FROM categorie where id='$cat2'",DB_FETCHMODE_ASSOC);
		echo $res[0]['ccourt'];
	}

	if( $cat1!='' )
		echo ".</h4>\n";
}

?>
