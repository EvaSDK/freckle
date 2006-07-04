<?php
/**
 *	fonctions de gestion des listes d'administrations
 *
 * @package freckle
 * @version 2.0
 */


/**
 * Renvoie la requête adéquate pour une des 2 fonctions suivantes
 * @param string $what action à effectuer
 * @return string requête SQL correspondant à $what
 */
function get_query($what)
{
	switch( $what )
	{
		case "affect":
			$query = "SELECT id,url,annee_prod,commentaire FROM fichiers LEFT JOIN reference ON id=id_fichier WHERE id_fichier IS NULL ";
			break;
		case "defect":
			$query = "SELECT distinct(id_fichier),url,annee_prod,commentaire FROM fichiers LEFT JOIN reference ON id=id_fichier WHERE id_fichier IS NOT NULL";
			break;
		case "search":
			$cat1 = isset($_GET['cat1']) ? $_GET['cat1'] : '';
			$cat2 = isset($_GET['cat2']) ? $_GET['cat2'] : '';

			if( $cat1=='' and $cat2=='' )
			{
				$query = "SELECT distinct(id_fichier),url,annee_prod,commentaire FROM reference,fichiers,categorie WHERE fichiers.id=reference.id_fichier AND categorie.id=id_categorie ORDER BY id_fichier DESC";	
			} else if( $cat2!=0 and $cat1!=$cat2 )
			{
				$query = "SELECT id_fichier, url, count(id_fichier) as occur, id_categorie, commentaire
				FROM fichiers, reference, categorie
				WHERE (
					id_categorie = '$cat1'
					OR id_categorie = '$cat2'
					)
				AND id_fichier = fichiers.id AND id_categorie = categorie.id
				GROUP BY id_fichier";
			} else
			{
				$query = "SELECT distinct(id_fichier),url,annee_prod,commentaire FROM reference,fichiers,categorie WHERE fichiers.id=reference.id_fichier AND categorie.id=id_categorie AND id_categorie='$cat1'";
			}
			break;
		default:
		 $query = "SELECT * FROM $what";
	}
	//echo $query;
	return $query;
}


/**
 * Affiche les éléments de la requête
 * @param string action à effectuer
 * @param int décalage par rapport au premier résultat de la requête
 */
function display_list_entries($what,$offset)
{
	global $db, $step, $format, $_DEBUG, $repos_html;
	
	if($what=="upload") return;

	if( $_DEBUG )
		echo "--".$result[0]["occur"]."--\n";

	if( $_GET['cat2']==0 ) $_GET['cat2'] = '';

	if( $what=="search" and ($_GET['cat1']!='' and ($_GET['cat2']!='' ))) {
		$result = $db->getAll( get_query( $what ),DB_FETCHMODE_ASSOC);
		$result = array_slice( array_filter( $result, "elag" ), $offset, $step );
	} else {
		$query = $db->modifyLimitQuery( get_query($what), $offset, $step );
		$result = $db->getAll($query,DB_FETCHMODE_ASSOC);
	}
	
	echo "<table>\n";

	if( $_DEBUG )
	{
		echo "<pre>";
		print_r( $result );
		echo "</pre>";
	}
	
	foreach( $result as $k=>$v )
	{
		$id = isset($v["id_fichier"]) ? $v["id_fichier"] : $v["id"];
		
		echo "<tr>\n";

		if( $_SESSION["admin"]==TRUE )
			echo "\t<td>$id</td>\n";
		
		switch($what) {
			case "fichiers":
				echo "\t<td><input type='checkbox' name='ids-$id' value='$id'/></td>\n";
				echo "\t<td>".$v["annee_prod"]."</td>\n";
				echo "\t<td>".$v["url"]."</td>\n";
				break;
			case "categorie":
				echo "\t<td><input type='checkbox' name='ids-$id' value='$id'/></td>\n";
				echo "\t<td>".$v["ccourt"]."</td>\n";
				echo "\t<td>".$v["clong"]."</td>\n";
				break;
			case "types": 
				echo "\t<td><input type='checkbox' name='ids-$id' value='$id'/></td>\n";
				echo "\t<td>".$v["type"]."</td>\n";
				break;
			case "affect":
				echo "\t<td><input type='checkbox' name='ids-$id' value='$id'/></td>\n";
				echo "\t<td>".$v["url"]."</td>\n";
				break;
			case "search":
				$arr = vfs_handling( $v["url"] );
				$filename = basename( $v["url"] );
				echo "\t<td><img src='".getIcon($v["url"])."' alt='icon'/></td>\n";
				$result = array();
				//echo $filename;
				preg_match( $format, $filename, $result );
				echo "\t<td>".$result[1]."</td>\n";
				echo "\t<td>".$result[2]."</td>\n";
				echo "\t<td><a href=\"".$repos_html.$arr["url"]."\" title='".$v["commentaire"]."'>".$result[3]."</a></td>\n";
				break;
			case "defect":
				$arr = vfs_handling( $v["url"] );
				$filename = basename( $v["url"] );
				echo "\t<td><input type='checkbox' name='ids-$id' value='$id'/>\n";
				$result = array();
				preg_match( $format, $filename, $result );
				echo "\t<td>".$result[1]."</td>\n";
				echo "\t<td>".$result[2]."</td>\n";
				echo "\t<td>".$result[3]."</td>\n";
				break;
		}

		echo "</tr>\n";
	}
	echo "</table>\n";
	echo "<hr class='separateur'/>";
}


function elag( $var )
{
	return ( ($var["occur"]+0)==2 );
}


/**
 * affiche une liste permettant d'accéder aux éléments de la requête
 * @param string action à effectuer
 * @param int décalage par rapport au premier résultat de la requête
 */
function display_list_access($what,$offset)
{
	global $step, $db, $PHP_SELF;

	if($what=="upload") return;

	$query = get_query($what);

	if( $what=="search" ) {
		$result = count( array_filter( $db->getAll( $query,DB_FETCHMODE_ASSOC), "elag" ) );
		$max = $result;
	}else {
		if( !preg_match("/\*/",$query) )
		{
			$query = preg_replace("/SELECT.((distinct\()?[\w]+?\)).*.FROM/","SELECT count(\\1) as count FROM", $query );
			$query = preg_replace("/ ORDER BY (\S+)(.(\S+)*)?/",";",$query);
		} else {
			$query = preg_replace("/SELECT.(\S+).FROM/","SELECT count(id) as count FROM", $query );
		}
		$result =& $db->getRow( $query );
		$max = $result[0];
	}

	$cat1 = isset($_GET['cat1']) ? $_GET['cat1'] : '';
	$cat2 = isset($_GET['cat2']) ? $_GET['cat2'] : '';

	if( $what=="search" )
		$plus = "&amp;cat1=".$cat1."&amp;cat2=".$cat2;
	else
		$plus = '';

  echo "<div class='admin-accesslist'><span>Pages&nbsp;: </span>\n";

	if( $max==0 )
	{
		echo "0 résultats pour cette requête";
	} else {

		/* affiche la liste des pages */
	  for($i=0; $i< $max; $i+=$step)
		{
	    echo "<a href='".basename($PHP_SELF)."?what=$what&amp;current=$i".$plus."'";
			$current = isset($_GET['current']) ? $_GET['current'] : '';
			if( ($current!="" and $i==$current) or ($current=="" and $i==0) )
				echo " id='current_page'";
			echo ">".(($i/$step)+1)."</a>\n";
		}
	}
  echo "</div>";
}


/**
 * affiche le formulaire d'action pour le type de données désigné
 * @param string type de formulaire demandé
 */
function get_form($what)
{
	echo "\t<fieldset>\n";
	
	switch($what)
	{
		case "upload":
			echo "\t\t<input type='file' name='userfile' value='' />\n";
			echo "<input type='submit' name='action' value='Télécharger' />\n";
			break;
		case "types":
		case "fichiers":
		case "categorie":
			echo "\t\t<input type='submit' name='action' value='Ajouter' />\n";
			echo "\t\t<input type='submit' name='action' value='Modifier' />\n";
			echo "\t\t<input type='submit' name='action' value='Supprimer' />\n";
			break;
		case "affect":
			echo "\t\t<input type='submit' name='action' value='Classer' />\n";
			break;
		case "defect":
			echo "\t\t<input type='submit' name='action' value='Désaffecter' />\n";
			break;
	}
		
	echo "\t\t<input type='hidden' name='what' value='$what' />\n";
	echo "<br /><br />\n";
	
	switch( $what )
	{
		case "types":
			echo "\t\t<input type='text' name='type' size='27' value='type' />\n";
			break;
		case "fichiers":
			echo "\t\t<input type='text' name='url'	size='22' value='URL' />\n";
			echo "\t\t<input type='text' name='annee_prod' size='4' value='2004' /><br />\n";
			echo "\t\t<textarea name='comment' cols='35' row='4'>commentaires</textarea>\n";
			break;
		case "categorie":
			echo "\t\t<input type='text' name='clong'	size='28' value='description longue' />\n";
			echo "\t\t<input type='text' name='ccourt' size='5' value='courte' />\n";
			break;
		case "affect":
			display_categorie_select(); 
			echo "<br />\n";
			display_types_select();
			break;
		case "defect":
			break;
		case "upload":
			display_types_select();
			echo "<br />\n";
			display_categorie_select(); 
			echo "<br />\n";
			echo "\t\t<input type='text' name='annee_prod' size='4' value='2004' /><br />\n";
			echo "\t\t<textarea name='comment' cols='35' row='4'>commentaires</textarea>\n";
			break;
	}
	echo "\t</fieldset>\n";
}


/**
 * Gestion du mini-vfs
 * @param string url à analyser
 * @return string une url utilisable dans un lien html
 */
function vfs_handling( $arr )
{
	global $repos_html;
	$arr = array( "url" => $arr );
	$scheme = substr( $arr["url"], 0, strpos( $arr["url"], ":") );

	switch( $scheme )
	{
		case "file" :
			$arr["url"] = preg_replace("/file:\/\//", $repos_html, $arr["url"]);
			$arr["disp"] = basename( substr( $arr["url"], strpos( $arr["url"], "-",  strpos( $arr["url"], "-") + 1 ) + 1 ) );
			$arr["icon"] = getIcon($arr["disp"]);
			break;

		case "http" :
			$arr["disp"] = $arr["commentaire"];
			$arr["icon"] = "images/icones/web.gif";
			break;
	}

	return $arr;
}

?>
