<?

function getCatCode() {
	$res = mysql_query( "select count(*) as cc from cat");
	$lcat[0]=0;
	$row = mysql_fetch_object($res);
	if ($row) $lcat[0]=$row->cc;
	$res = mysql_query( "select * from cat");
	while ($row = mysql_fetch_object($res) ) {
		$lcat[$row->id]=$row->code;
	}
	return $lcat;	
}



function getCat($cat, $lcat) {
	$strRes=NULL;
	for ( $i=1 ; $i<=$lcat[0] ; $i++ ) {
	  $p=pow(2,$i);
	  if ( ($p & $cat) == $p ) {

			if ($strRes==NULL) {

//				$strRes="<a href=\"documents.php?see=4&categorie=$p\">$lcat[$i]</a>";

				$strRes="$lcat[$i]";

			}

			else {

//				$strRes .=  " - <a href=\"index.php?location=file&focus=yes&focus1=$p\">$lcat[$i]</a>";

				$strRes .=  " - $lcat[$i]";


			}

		}

	}

	return $strRes;

}



function getFrDate ( $ts ) {
	// date in french, you may modify this section for your language
	$day = array("Dimanche", "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi");
	// month in french, you may modify this section for your language
	$month = array("", "Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Decembre");
	$tr[1] =  $day[date("w",$ts)] . " " . date("j" , $ts) . " " . $month[date ( "n" , $ts)] . " ". date("Y", $ts) ;

	$tr[2] = " &agrave; " . date ("G" , $ts) . "h" . date( "i",$ts);

	return $tr;

}



function toSQL($str){
	return mysql_escape_string($str);
}



function getUId() {
	mysql_query( "UPDATE gc SET id=id+1");
	$res = mysql_query( "SELECT id FROM gc");
	$row = mysql_fetch_object($res);
	return $row->id;
}



function faireMenu ($loc) {
	$liste = array (1 => 'doc' , 'plan' , 'file' , 'actu' , 'site' ,'news');
	for ($i=1 ; $i <= 6 ; $i++) {
		if ($loc==$liste[$i]) {
			$str = $str . "<TD height=15 nowrap class=\"bleufonce\">" . getTitle($loc) . "</td>";
		}
		else {
			$str= $str . "<TD height=15 nowrap class=\"grisclair\"><A class=bleuclair href=\"index.php?location=$liste[$i]\">"
				. getTitle($liste[$i]) . "</A></TD>";

		}
	}
	return $str;		
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



function CheckVars($var, $size) {
  $length = strlen($var);
  if ($length > $size)
  	for ( ; $length >= $size; $length--)
		$var[$length] = "";
}


function GetVars($varname) {
  if ($_SERVER[$varname] != "") {
   $retval = $_SERVER[$varname];
	}
  elseif ($_COOKIE[$varname] != ""){
    $retval = $_COOKIE[$varname];
	}
  elseif ($_POST[$varname] != ""){
    $retval = $_POST[$varname];
}
  elseif ($_GET[$varname] != "" ){
    $retval = $_GET[$varname];
	}
  elseif ($_ENV[$varname] != ""){
    $retval = $_ENV[$varname];
	}
  else
    $retval = NULL;
  return trim($retval) ;
}

?>
