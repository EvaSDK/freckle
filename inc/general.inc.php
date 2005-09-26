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
	$link = db_connect();
	$ptr = db_query($link,"SELECT * FROM categorie ORDER BY id;");
	$i=0;
	
	echo "<table>\n";
	while( $row = db_fetch_object($ptr) )
	{
		if( $i==0 ) echo "<tr>\n";
		echo "\t<td class='right'>[$row->ccourt]</td>\n";
		echo "\t<td><a href='index.php?what=search&amp;cat1=".$row->id."&amp;cat2='>$row->clong</a></td>\n";
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
	
	db_close($link);
	echo "</table>\n";
}


/**
 * Affiche 2 boites de sélection de catégorie
 */
function display_categorie_select()
{
	$link = db_connect();
	$ptr = db_query($link,"SELECT * FROM categorie;");

	/* on récupère les valeurs de la recherche */
	if ( isset($_GET['cat1']) )
		$cat1 = $_GET['cat1'];
		
	if ( isset($_GET['cat2']) )
		$cat2 = $_GET['cat2'];

	/* premier <select> */
	echo "<select name='cat1'>\n";
	while($row = db_fetch_object($ptr)) {
		echo "\t<option value='".$row->id."'";
		if( $cat1==$row->id )
			echo " selected='selected'";
		echo ">[$row->ccourt] $row->clong</option>\n";
	}
	echo "</select>";
	
	$ptr = db_query($link, "SELECT * FROM categorie ORDER BY ccourt");

	/* deuxième <select> */
	echo "<select name='cat2'>\n";
	echo "<option value='0'>Second Critère</option>\n";
	while($row = db_fetch_object($ptr)) {
		echo "\t<option value='".$row->id."'";
		if( $cat2==$row->id )
			echo " selected='selected'";
		echo ">[$row->ccourt] $row->clong</option>\n";
	}
	echo "</select>\n";
	db_close($link);
}


/**
 * Affiche le type sélectionné
 */
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


/**
 * renvoie l'icône correspondant à l'extension du fichier
 * @param $file est le nom du fichier dans on veut l'extension
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
			case "tgz":
			case "rar": 
			case "ace":
			case "zip": $image = "images/icones/archives.gif"; break;

			/* autres */
			default: $image = "images/icones/unknown.gif";
		}
		return($image);
	}
}


/**
 * Affiche un rappel de la requête sélectionnée
 */
function description_critere()
{
	global $db;
	
	if( isset($_GET['cat1']) )
		$cat1 = $_GET['cat1'];
	else
		$cat1='';
		
	if( isset($_GET['cat2']) )
		$cat2 = $_GET['cat2'];
	else
		$cat2='';

	if( $cat1!='' )
		echo "<h4>Recherche des documents en ";
	
	if( $cat1!='' )
	{
		$res = getAll("SELECT ccourt FROM categorie where id='$cat1'");
		echo "<pre>".print_r($res)."</pre>";
		echo $res->ccourt;
	}
	if( $cat2!='' )
	{
		echo " et ";
		$res = getAll($link,"SELECT ccourt FROM categorie where id='$cat2'");
		echo "<pre>".print_r($res)."</pre>";
		echo $res->ccourt;
	}

	if( $cat1!='' )
		echo ".</h4>\n";
}

?>
