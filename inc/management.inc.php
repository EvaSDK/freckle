<?php
/* Freckle v2.0
 * Distributed under the terms of the General Public Licence (GPL)
 * Copyright 2004 Gilles Dartiguelongue
 *
 * File Name: management.inc.php 
 * Developper: Gilles Dartiguelongue
 * Date: 2004-08-01
 *
 *	fonctions de gestion des listes d'administrations
 */


/* renvoie la requête adéquate pour une des 2 fonctions suivantes
 * $what est l'action à effectuer
 */
function get_query($what)
{
	switch( $what )
	{
		case "affect":
			$query = "SELECT id,url,annee_prod,commentaire FROM fichiers LEFT JOIN reference ON id=id_fichier WHERE id_fichier IS NULL ";
			break;
		case "defect":
			$query = "SELECT id_fichier,url,annee_prod,feinte.ccourt as cat1,categorie.ccourt as cat2,commentaire FROM reference,fichiers,categorie, categorie as feinte WHERE fichiers.id=reference.id_fichier AND categorie.id=id_categorie1 AND feinte.id=id_categorie2 ORDER BY id_fichier";
			break;
		case "search":
			$cat1 = $_GET['cat1'];
			$cat2 = $_GET['cat2'];

			if( $cat1=="" and $cat2=="" ) {
				$query = "SELECT id_fichier,url,annee_prod,feinte.ccourt as cat1,categorie.ccourt as cat2,commentaire FROM reference,fichiers,categorie, categorie as feinte WHERE fichiers.id=reference.id_fichier AND categorie.id=id_categorie1 AND feinte.id=id_categorie2 ORDER BY id_fichier DESC";	
			} else if ($cat2!=0) {
				$query = "SELECT id_fichier,url,annee_prod,feinte.ccourt as cat1,categorie.ccourt as cat2,commentaire FROM reference,fichiers,categorie, categorie as feinte WHERE fichiers.id=reference.id_fichier AND categorie.id=id_categorie1 AND feinte.id=id_categorie2  AND ((id_categorie1='$cat2' AND id_categorie2='$cat1') OR (id_categorie1='$cat1' AND id_categorie2='$cat2'))";
			} else {
				$query = "SELECT id_fichier,url,annee_prod,feinte.ccourt as cat1,categorie.ccourt as cat2,commentaire FROM reference,fichiers,categorie, categorie as feinte WHERE fichiers.id=reference.id_fichier AND categorie.id=id_categorie1 AND feinte.id=id_categorie2  AND (id_categorie1='$cat1' OR id_categorie2='$cat1')";
			}
			break;
		default:
		 $query = "SELECT * FROM $what";
	}
	return $query;
}


/* affiche les éléments de la requête
 *
 * $what = action à effectuer
 * $offset = décalage par rapport au premier résultat de la requête
 */
function display_list_entries($what,$offset)
{
	$link = db_connect();
	$query = get_query($what).sql_limit($offset);

	$result = db_query($link, $query);

	echo "<table>\n";
	$i = 0;
	while ($arr = db_fetch_array($result))
	{
		$i++;

		if( array_key_exists("id_fichier",$arr) && $arr["id_fichier"]!="" )
		{
			$arr["id"] = $arr["id_fichier"];
		}
		$id = $arr["id"];
		
		echo "<tr>\n";

		if( $_SESSION["admin"]==TRUE )
		{
				echo "\t<td>$id</td>\n";
		}

		
		switch($what) {
			case "fichiers":
				echo "\t<td><input type='checkbox' name='ids-$id' value='$id'/></td>\n";
				echo "\t<td>".$arr["anne_prod"]."</td>\n";
				echo "\t<td>".$arr["url"]."</td>\n";
				break;
			case "categorie":
				echo "\t<td><input type='checkbox' name='ids-$id' value='$id'/></td>\n";
				echo "\t<td>".$arr["ccourt"]."</td>\n";
				echo "\t<td>".$arr["clong"]."</td>\n";
				break;
			case "types": 
				echo "\t<td><input type='checkbox' name='ids-$id' value='$id'/></td>\n";
				echo "\t<td>".$arr["type"]."</td>\n";
				break;
			case "affect":
				echo "\t<td><input type='checkbox' name='ids-$id' value='$id'/></td>\n";
				echo "\t<td>".$arr["url"]."</td>\n";
				break;
			case "search":
				$arr = vfs_handling( $arr );
				$filename = $arr["url"];
				echo "\t<td><img src='".$arr["icon"]."' alt='icon'/></td>\n";
				echo "\t<td>".$arr["cat1"]."</td>\n";
				echo "\t<td>".$arr["cat2"]."</td>\n";
				echo "\t<td><a href='".$arr["url"]."'>".$arr["disp"]."</a></td>\n";
				break;
			case "defect":
				echo "\t<td><input type='checkbox' name='ids-$id' value='$id'/>\n";
				echo "\t<td>".$arr["cat1"]."</td>\n";
				echo "\t<td>".$arr["cat2"]."</td>\n";
				echo "\t<td>".$arr["url"]."</td>\n";
				break;
		}

		echo "</tr>\n";
	}
	echo "</table>\n";
	db_close($link);
}



/* affiche une liste permettant d'accéder aux éléments de la requête
 *
 * $what = action à effectuer
 * $offset = décalage par rapport au premier résultat de la requête
 */
function display_list_access($what,$offset)
{
  global $step;

  $link  = db_connect();
  $query = get_query($what);

  if( !preg_match("/\*/",$query) )
  {
    $query = preg_replace("/SELECT.(\w+).*.FROM/","SELECT count(\\1) as count FROM", $query );
    $query = preg_replace("/ ORDER BY (\S+)(.(\S+)*)?/",";",$query);
  } else {
    $query = preg_replace("/SELECT.(\S+).FROM/","SELECT count(id) as count FROM", $query );
  }

  $result = db_query( $link, $query );
  $max = db_fetch_object( $result );

  if( $what=="search" )
    $plus = "&amp;cat1=".$_GET["cat1"]."&amp;cat2=".$_GET["cat2"];

  echo "<div class='admin-accesslist'><span>Pages&nbsp;: </span>\n";

	if( $max->count==0 )
	{
		echo "0 résultats pour cette requête";
	} else {
	  for($i=0; $i< $max->count; $i+=$step)
		{
	    echo "<a href='".basename($PHP_SELF)."?what=$what&amp;current=$i".$plus."'";
			$current = $_GET['current'];
			if( ($current!="" and $i==$current) or ($current=="" and $i==0) )
				echo " id='current_page'";
			echo ">".(($i/$step)+1)."</a>\n";
		}
	}
  echo "</div>";
  db_close($link);
}


/* affiche le formulaire d'action pour le type de données désigné
 *
 * $what = type de formulaire demandé
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
			display_types_select();
			echo "<br />\n";
			display_categorie_select(); 
			break;
		case "defect":
			break;
	}
	echo "\t</fieldset>\n";
}

function crawl_fs()
{
	global $repos;
	$fs_list = array();

	if( !($handle = opendir($repos)) )
	{
		$_SESSION['message'] = "impossible d'accéder à l'entrepôt de fichiers.";
		return FALSE;
	}

	$dir_list = array();
	$file_list = array();
	rewinddir($handle);
	
	dive_fs( $repos, $file_list, $handle );
	
	return $file_list;
}

function dive_fs( $dir, &$files, $handle )
{
	while( ($entrie=readdir($handle))!==FALSE )
	{
		if( $entrie!="." and $entrie!=".." and is_dir($entrie) )
			dive_fs( $dir.$entrie, $files, $handle );
		if( $entrie!="." and $entrie!=".." and is_file($entrie) )
			$files[] = $dir.$entrie;
	}
}

function vfs_handling( $arr )
{

	global $repos_html;
	$scheme = substr( $arr["url"], 0, strpos( $arr["url"], ":") );

	switch( $scheme )
	{
		case "file" :
			$arr["url"] = preg_replace("/file:\/\//", $repos_html, $arr["url"]);
			$arr["disp"] = basename( substr( $arr["url"], strpos( $arr["url"], "-",  strpos( $arr["url"], "-") + 1 ) + 1 ) );
			$arr["icon"] = getIcon($arr["disp"])
			break;

		case "http" :
			$arr["disp"] = $arr["commentaire"];
			$arr["icon"] = "~freckle/_icon/website.png";
			break;
	}

	return $arr;
}

?>
