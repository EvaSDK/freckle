<?php
/**
 *	fonctions de gestion des listes d'administrations
 *
 * @package freckle
 * @version 2.0
 */


/**
 * Renvoie la requ�te ad�quate pour une des 2 fonctions suivantes
 * @param string $what action � effectuer
 * @return string requ�te SQL correspondant � $what
 */
function get_query($what)
{
	switch( $what )
	{
		case "affect":
			$query = "SELECT id,url,annee_prod,commentaire FROM fichiers LEFT JOIN reference ON id=id_fichier WHERE id_fichier IS NULL ";
			break;
		case "defect":
#			$query = "SELECT id_fichier,url,annee_prod,feinte.ccourt as cat1,categorie.ccourt as cat2,commentaire FROM reference,fichiers,categorie, categorie as feinte WHERE fichiers.id=reference.id_fichier AND categorie.id=id_categorie1 AND feinte.id=id_categorie2 ORDER BY id_fichier";
			$query = " SELECT id_fichier,url,annee_prod,commentaire FROM fichiers LEFT JOIN reference ON id=id_fichier WHERE id_fichier IS NOT NULL";
			break;
		case "search":
			$cat1 = isset($_GET['cat1']) ? $_GET['cat1'] : '';
			$cat2 = isset($_GET['cat2']) ? $_GET['cat2'] : '';

			if( $cat1=='' and $cat2=='' )
			{
				$query = "SELECT id_fichier,url,annee_prod,commentaire FROM reference,fichiers,categorie WHERE fichiers.id=reference.id_fichier AND categorie.id=id_categorie ORDER BY id_fichier DESC";	
			} else if( $cat2!="" and $cat1!=$cat2 )
			{
				$query = "SELECT id_fichier,url,annee_prod,feinte.ccourt as cat1,categorie.ccourt as cat2,commentaire FROM reference,fichiers,categorie, categorie as feinte WHERE fichiers.id=reference.id_fichier AND categorie.id=id_categorie1 AND feinte.id=id_categorie2  AND ((id_categorie1='$cat2' AND id_categorie2='$cat1') OR (id_categorie1='$cat1' AND id_categorie2='$cat2'))";
			} else
			{
				$query = "SELECT id_fichier,url,annee_prod,feinte.ccourt as cat1,categorie.ccourt as cat2,commentaire FROM reference,fichiers,categorie, categorie as feinte WHERE fichiers.id=reference.id_fichier AND categorie.id=id_categorie1 AND feinte.id=id_categorie2  AND (id_categorie1='$cat1' OR id_categorie2='$cat1')";
			}
			break;
		default:
		 $query = "SELECT * FROM $what";
	}
	return $query;
}


/**
 * Affiche les �l�ments de la requ�te
 * @param string action � effectuer
 * @param int d�calage par rapport au premier r�sultat de la requ�te
 */
function display_list_entries($what,$offset)
{
	global $db, $step;
	$query = $db->modifyLimitQuery( get_query($what), $offset, $step );
	$result = $db->getAll($query,DB_FETCHMODE_ASSOC);
	
	echo "<table>\n";
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
/*				echo "\t<td>".$arr["cat1"]."</td>\n";
				echo "\t<td>".$arr["cat2"]."</td>\n";*/
				echo "\t<td><a href=\"".$arr."\" title='".$v["commentaire"]."'>".$filename."</a></td>\n";
				break;
			case "defect":
				echo "\t<td><input type='checkbox' name='ids-$id' value='$id'/>\n";
/*				echo "\t<td>".$arr["cat1"]."</td>\n";
				echo "\t<td>".$arr["cat2"]."</td>\n";*/
				echo "\t<td>".$v["url"]."</td>\n";
				break;
		}

		echo "</tr>\n";
	}
	echo "</table>\n";
}



/**
 * affiche une liste permettant d'acc�der aux �l�ments de la requ�te
 * @param string action � effectuer
 * @param int d�calage par rapport au premier r�sultat de la requ�te
 */
function display_list_access($what,$offset)
{
	global $step, $db, $PHP_SELF;

	$query = get_query($what);

	echo $query;

	if( !preg_match("/\*/",$query) )
	{
		$query = preg_replace("/SELECT.(\w+).*.FROM/","SELECT count(\\1) as count FROM", $query );
		$query = preg_replace("/ ORDER BY (\S+)(.(\S+)*)?/",";",$query);
	} else {
		$query = preg_replace("/SELECT.(\S+).FROM/","SELECT count(id) as count FROM", $query );
	}

/*  $result = db_query( $link, $query );*/
	$result =& $db->getRow( $query );
	$max = $result[0];
	
  echo "<pre>$max</pre>\n";
/*  $max = db_fetch_object( $result );*/

	$cat1 = isset($_GET['cat1']) ? $_GET['cat1'] : '';
	$cat2 = isset($_GET['cat2']) ? $_GET['cat2'] : '';

	if( $what=="search" )
		$plus = "&amp;cat1=".$cat1."&amp;cat2=".$cat2;
	else
		$plus = '';

  echo "<div class='admin-accesslist'><span>Pages&nbsp;: </span>\n";

	if( $max==0 )
	{
		echo "0 r�sultats pour cette requ�te";
	} else {
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
 * affiche le formulaire d'action pour le type de donn�es d�sign�
 * @param string type de formulaire demand�
 */
function get_form($what)
{
	echo "\t<fieldset>\n";
	
	switch($what)
	{
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
			echo "\t\t<input type='submit' name='action' value='D�saffecter' />\n";
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
			display_types_select();
			echo "<br />\n";
			display_categorie_select(); 
			break;
		case "defect":
			break;
	}
	echo "\t</fieldset>\n";
}


/**
 * Gestion du mini-vfs
 * @param string url � analyser
 * @return string une url utilisable dans un lien html
 */
function vfs_handling( $arr )
{
	global $repos_html;
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
