<?php

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
	echo "\n</table>\n";
}



/* affiche 2 boites de séléction de catégorie */
function display_categorie_select()
{
	$link = db_connect();
	$ptr = db_query($link,"SELECT * FROM categorie;");

	echo "<select name='cat1'>\n";
	while($row = db_fetch_object($ptr)) {
		echo "\t<option value='".$row->id."'>[$row->ccourt] $row->clong</option>\n";
	}
	echo "</select>";
	$ptr = db_query($link, "SELECT * FROM categorie ORDER BY ccourt");
	echo "<select name='cat2'>\n";
	echo "\t<option value='0'>2ème critère</option>\n";
	while($row = db_fetch_object($ptr)) {
		echo "\t<option value='".$row->id."'>[$row->ccourt] $row->clong</option>\n";
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

function description_critere()
{
	$link = db_connect();

	if( $_GET['cat1']!="" )
		echo "<h4>Recherche des documents en ";
	
	if( isset( $_GET['cat1'] ) )
	{
		$result = db_query($link,"SELECT ccourt FROM categorie where id='".$_GET['cat1']."'");
		$res =  db_fetch_object($result);
		echo $res->ccourt;
	}
	if( $_GET['cat2']!=0)
	{
		echo " et ";
		$result = db_query($link,"SELECT ccourt FROM categorie where id='".$_GET['cat2']."'");
		$res = db_fetch_object($result);
		echo $res->ccourt;
	}

	if( $_GET['cat1']!="" )
		echo ".</h4>\n";
		
	db_close($link);
}

?>
