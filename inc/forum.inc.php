<?

function getCat($cat, $lcat) {
	$strRes=NULL;
	for ( $i=1 ; $i<=$lcat[0] ; $i++ ) {
		$p=pow(2,$i);
		if ( ($p & $cat) == $p ) {

			if ($strRes==NULL) {
				$strRes="$lcat[$i]";
			}
			else {
				$strRes .=	" - $lcat[$i]";
			}
		}
	}
	return $strRes;
}

function getFrDate ( $ts ) {
	// date in french, you may modify this section for your language
	$day = array("Dimanche", "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi");
	// month in french, you may modify this section for your language
	$month = array("", "Janvier", "F�vrier", "Mars", "Avril", "Mai", "Juin", "Juillet", "Ao�t", "Septembre", "Octobre", "Novembre", "Decembre");
	$tr[1] =	$day[date("w",$ts)] . " " . date("j" , $ts) . " " . $month[date ( "n" , $ts)] . " ". date("Y", $ts) ;

	$tr[2] = " &agrave; " . date ("G" , $ts) . "h" . date( "i",$ts);

	return $tr;

}

/* renvoie l'ic�ne correspondant � l'extension du fichier */
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
