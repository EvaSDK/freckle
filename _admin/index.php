<?php
include("param.php");
include("../inc/forum.inc.php");

$conn = mysql_pconnect($dns,$usr,$pwd);
mysql_select_db ( $db , $conn );
if ( $password==$pwd && $username==$usr) {
	SetCookie("freckle-esiee-admin-usr",$usr,time()+8640);
	SetCookie("freckle-esiee-admin-pwd",$pwd,time()+8640);
	$step=2;
}
else {
	if ( trim($_COOKIE["freckle-esiee-admin-usr"]) != $usr || trim($_COOKIE["freckle-esiee-admin-pwd"]) != $pwd ) {
		$step=0;
	}
	else {
		if ($_POST[$step] != ""){
		    $step = trim($_POST[$step]);
		}
		elseif ($_GET[$step] != "" ){
			$step = trim($_GET[$step]);
		}
		if ($step=="") {
			$step=2;
		}
	}
}
switch ($step) {
case 2 : 
	echo "<html><head></head><body>";
	?>Action
<ul>
  <li><a href="mysql.php">mysql</a></li>
  <li><a href="index.php?step=3">gestion du site</a></li>
  <li><a href="index.php?step=12">dump</a></li>
  <li><a href="index.php?step=7">ajout d'une categorie</a></li>
  <li><a href="index.php?step=9">classement d'un fichier dans une categorie</a></li>
  <li><a href="index.php?step=13">ajout des nouveaux fichiers &agrave; la base</a></li>
  <li><a href="index.php?step=11">phpinfo</a></li>
  <li><a href="index.php?step=5">modification d'un fichier php</a></li>
  <li><a href="index.php?step=14">statistiques</a></li>
</ul>
<p><a href="index.php?step=15">Logout</a></p>
<?php 
	echo "</body></html>";
break;
case 3 :
	echo "<html><head></head><body>";
?>
<form method="post" action="index.php">
  <input name="step" type="hidden" value="4">
  <table border="1">
    <tr valign="baseline">
      <td valign="top"><input type="radio" name="radiobutton" value="news">
        news </td>
      <td align="left"><p>date
          <input name="ndate" type="text" id="ndate" value="<?php
	  echo  date("Y-m-d", time());
		   ?>">
        </p>
        <p>contenu
          <textarea name="ncomment" cols="40" rows="5" id="ncomment"></textarea>
        </p>
      </td>
    </tr>
    <tr valign="baseline">
      <td valign="top"><input type="radio" name="radiobutton" value="actu">
        actu</td>
      <td align="left"><p>titre
          <input name="anom" type="text" id="anom" size="50">
        </p>
        <p>date
          <input name="adate" type="text" id="adate" value="<?php
		  echo  date("Y-m-d", time());
		   ?>">
        </p>
        <p>contenu
          <textarea name="acomment" cols="40" rows="5" id="acomment"></textarea>
        </p>
        <p>logo
          <input name="alogo" type="checkbox" id="alogo" value="on">
        </p>
        <p>adresse du logo
          <input name="aadresselogo" type="text" id="aadresselogo" size="50">
        </p>
      </td>
    </tr>
    <tr valign="baseline">
      <td valign="top"><input type="radio" name="radiobutton" value="site">
        lien </td>
      <td align="left"><p>titre
          <input name="lnom" type="text" id="lnom" size="50">
        </p>
        <p>lien
          <input name="ladresse" type="text" id="ladresse" size="50">
        </p>
        <p>conmmentaire
          <textarea name="lcomment" cols="50" rows="5" id="lcomment"></textarea>
        </p>
        <p>logo
          <input name="llogo" type="checkbox" id="llogo" value="on">
        </p>
        <p>adresse du logo
          <input name="ladresselogo" type="text" id="ladresselogo" size="50">
        </p>
      </td>
    </tr>
    <tr valign="baseline">
      <td valign="top"><input name="radiobutton" type="radio" value="nada" checked>
      </td>
      <td align="left"><input type="submit" name="Submit2" value="Submit">
      </td>
    </tr>
  </table>
</form>
<a href="index.php">Retour</a>
</p>
<?php
	echo "</body></html>";
break;
case 4 :
	switch ( $radiobutton ) {
	case "news" :
			$sql="INSERT INTO news ( Date , Comment ) VALUES ( '$ndate' , '" . toSQL($ncomment) . "' )" ;
			mysql_query($sql,$conn);
	break;
	case "actu" :
			if ($alogo == "on") {	
				$sql="INSERT INTO actu ( Date , Comment , Logo , AdresseLogo , Nom ) VALUES ( '$ndate' , '" . toSQL($ncomment) . "' , 1 , '$aadresselogo', '" . toSQL($anom) . "')" ;
			}
			else {
				$sql="INSERT INTO actu ( Date , Comment , Nom ) VALUES ( '$ndate' , '" . toSQL($acomment) . "' , '" . toSQL($anom) . "') " ;
			}
			$p = mysql_query($sql,$conn);
	break;
	case "site" :
			if ($llogo == "on") {	
				$sql="INSERT INTO site ( Adresse , Comment , Logo , Adresselogo , Nom ) VALUES ( '$ladresse' , '" . toSQL($lcomment) . "' , 1 , '$ladresselogo', '" . toSQL($lnom) . "')" ;
			}
			else {
				$sql="INSERT INTO site ( Adresse , Comment , Nom ) VALUES ( '$ladresse' , '" . toSQL($lcomment) . "' , '" . toSQL($lnom) . "')" ;
			}
			$p = @mysql_query($sql,$conn);
	break;
	}
	header("Location: index.php?username=$usr&password=$pwd&step=2");
break;
case 5 :
?>
<form method="post" action="index.php">
  <input name="step" type="hidden" value="6">
  <table border="1">
    <tr>
      <td>fichier</td>
    </tr>
    <?php 

$pile [1] = "../";
$in=2;
$out=1;
$cpt=0;
while($in > $out ) {
	$handle=opendir($pile[$out]);
	while ($file = readdir($handle)) {
		if ($file != "." && $file != "..") {
			if (is_dir($pile[$out].$file)){
				$pile[$in]=$pile[$out].$file."/";
				$in++;
 			}
			else {
				if (is_file ( $pile[$out].$file )) {
					$pt = strrpos($file, ".");
					if ($pt != FALSE){
						if ( substr($file, $pt + 1, strlen($file) - $pt - 1)=="php" ) {
							$tablink[$cpt]=substr ( $pile[$out], 3) .$file;
							$tabfile[$cpt]=$file;
							$cpt++;
						}
					}
				}
			}
		}	
	}
	$out++;
} 
for ( $i=0 ; $i < $cpt ; $i++ ){
	echo "<tr><td><input type=\"radio\" name=\"path\" value=\"$tablink[$i]\">fichier : ../$tablink[$i]<br></td></tr>";
} ?>
    <tr>
      <td><input name="path" type="radio" value="--" checked>
      </td>
    </tr>
  </table>
  <br>
  <textarea name="newphp" cols="80" rows="10"></textarea>
  <input type="submit" name="Submit" value="Submit">
</form>
<?php
	echo "<a href=\"index.php\">Retour</a></body></html>";
break;
case 6 :
	$rs=fopen("../$path" , "w" );
	$res = fwrite( $rs , $newphp );
//	fclose($rs);
	header("Location: index.php");
break;
case 7 :
	echo "<html><head></head><body>";
	echo "<form method=\"post\" action=\"index.php\">"
		. "<input type=\"hidden\" name=\"step\" value=\"8\">code : <input type=\"text\" name=\"code\">"
		. " mon : <input type=\"text\" name=\"nom\"><input type=\"submit\" name=\"Submit\" value=\"Envoyer\"></form>";
	$sql= "select * from cat order by code";
	$res = mysql_query($sql);
	while ( $row = mysql_fetch_object($res)) {
		echo "$row->id <b> $row->code </b> - $row->nom<br>";
	}
	echo "<a href=\"index.php\">Retour</a></body></html>";
break;
case 8 :
	$sql = "insert into cat (code , nom) values ( '" . toSQL(strtoupper($code)) . "' , '" . toSQL($nom) . "')";
	$res = mysql_query($sql);	
	header("Location: index.php");
break;
case 9 :
	echo "<html><head></head><body><table border=\"1\">";
	$sql= "select * from cat order  by code";
	$leCat="<select name=\"catId\">";
	$res = mysql_query($sql);
	while ( $row = mysql_fetch_object($res)) {
		$leCat = "$leCat<option value=\"$row->id\">[$row->code] $row->nom</option>";
	}
	$leCat="$leCat</select>";

	$sql = "select * from file order by cat";
	$res = mysql_query($sql);
	$lcat = getCatCode();
	while ( $row = mysql_fetch_object($res)) {
		echo "<form method=\"post\" action=\"index.php\"><tr><input type=\"hidden\" name=\"id\" value=\"$row->id\">"
			. "<input type=\"hidden\" name=\"step\" value=\"10\"><td>$row->link</td><td>" 
			. getCat($row->cat, $lcat) . "&nbsp;</td><td>$leCat</td><td colspan=\"2\">"
			. "<input type=\"submit\" name=\"Submit\" value=\"Ajouter\"></td></tr></form>";
	}
	echo "</table><a href=\"index.php\">Retour</a></body></html>";
break;
case 10 : 
	$sql = "update file set file.cat=(file.cat|POW(2,$catId)) where id=$id";
	$res = mysql_query($sql);
	header("Location: index.php?step=9");	
break;
case 11 :
	echo phpinfo();
break;
case 12 :
	header("Content-disposition: filename=$db.sql");
	header("Content-type: application/octetstream");
	header("Pragma: no-cache");
	header("Expires: 0");

	$pResult = mysql_query( "show variables" );
	while( 1 ) {
		$rowArray = mysql_fetch_row( $pResult );
		if( $rowArray == false ) break;
		if( $rowArray[0] == "basedir" )
			$bindir = $rowArray[1]."bin/";
	}
	passthru( $bindir."mysqldump --add-drop-table --quote-names --user=$usr --password=$pwd $db" );
break;
case 13 :
	$pile [1] = "../files/";
	$in=2;
	$out=1;
	$cpt=0;
	while($in > $out ) {
		$handle=opendir($pile[$out]);
		while ($file = readdir($handle)) {
			if ($file != "." && $file != "..") {
				if (is_dir($pile[$out].$file)){
					$pile[$in]=$pile[$out].$file."/";
					$in++;
	 			}
				else {
					if (is_file ( $pile[$out].$file )) {
						$tablink[$cpt]=substr ( $pile[$out], 3) .$file;
						$tabfile[$cpt]=$file;
						$cpt++;
					}
				}
			}	
		}
		$out++;
	}
	for ( $i=0 ; $i < $cpt ; $i++ ){
		$sql = "select * from file where link='" . toSQL($tablink[$i]) . "'";
		$res = mysql_query($sql);		
		$row = mysql_fetch_object($res);
		if (!($row)) {
			$sql = "insert into file ( id, file , link , forumid ) values ( " . getUId() . " , '" 
				. toSQL($tabfile[$i]) . "' , '" . toSQL($tablink[$i]) . "' , 0 )";
			mysql_query( $sql);
		
		}
	}
	header("Location: index.php");
break;
case 14 :
	echo "<html><head><meta http-equiv=\"refresh\" content=10;URL=index.php?step=14></head><body>";
	$sql = "select * from visit";
	$res = mysql_query($sql);		
	$row = mysql_fetch_object($res);
	if ($row)
		echo "Nombre de visites : $row->clicks <br>";
	$sql = "select * from hit";
	$res = mysql_query($sql);		
	$row = mysql_fetch_object($res);
	if ($row)
		echo "Nombre de hit : $row->clicks <br>";
	echo "<a href=\"index.php\">Retour</a></body></html>";
break;
case 15 :
	SetCookie("freckle-esiee-admin-usr","");
	SetCookie("freckle-esiee-admin-pwd","");
	header("Location: index.php");
break;
default :
	echo "<html><head></head><body><form method=\"post\" action=\"index.php\"><input name=\"username\" type=\"text\" id=\"username\">"
		. "<input name=\"password\" type=\"password\" id=\"password\"><input type=\"submit\" name=\"Submit\" value=\"Envoyer\">"
		. "</form><a href=\"../index.php\">Retour</a></body></html>";
break;
}
?>
