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



/* affiche 2 boites de s�l�ction de cat�gorie */
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

/* renvoie l'ic�ne correspondant � l'extension du fichier */
function getIcon ( $file ) {
	$pt = strrpos($file, ".");

	if ($pt != FALSE) {
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
