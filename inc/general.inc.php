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
		echo " <td><a href='index.php?what=search&amp;cat1=".$row->id."&amp;cat2='>$row->clong</a></td>\n";
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
		switch ($file_ext)
		{
			/* images */
			case "gif":
			case "bmp":
			case "jpg": $image = "_icon/image.gif"; break;

			/* web */
			case "htm":
			case "html": $image = "_icon/web.gif"; break;

			/* documents */
			//case "swd":
			case "ppt": $image = "_icon/impress.gif"; break;
			//case ":
			case "xls": $image = "_icon/calc.gif"; break;
			case "sxw":
			case "doc": $image = "_icon/writer.gif"; break;
			case "txt": $image = "_icon/text.gif"; break;
			case "pdf": $image = "_icon/pdf.gif"; break;

			/* archives */
			case "tgz":
			case "rar": 
			case "ace":
			case "zip": $image = "_icon/archives.gif"; break;

			/* trucs à la con */
/*			case "mp3": $image = "_icon/i_mp3.gif"; break;
			case "exe": $image = "_icon/i_pgm.gif"; break;
			case "txt": $image = "_icon/i_txt.gif"; break;
			case "wav": $image = "_icon/i_wav.gif"; break;*/

			/* autres */
			default: $image = "_icon/unknown.gif";
		}
		return($image);
	}
}

function description_critere()
{
	$link = db_connect();
	$cat1 = $_GET['cat1'];
	$cat2 = $_GET['cat2'];

	if( $cat1!="" )
		echo "<h4>Recherche des documents en ";
	
	if( $cat1!="" )
	{
		$result = db_query($link,"SELECT ccourt FROM categorie where id='$cat1'");
		$res =  db_fetch_object($result);
		echo $res->ccourt;
	}
	if( $cat2!="" and $cat2!=0 )
	{
		echo " et ";
		$result = db_query($link,"SELECT ccourt FROM categorie where id='$cat2'");
		$res = db_fetch_object($result);
		echo $res->ccourt;
	}

	if( $cat1!="" )
		echo ".</h4>\n";
		
	db_close($link);
}

?>
