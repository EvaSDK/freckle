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


/* renvoie la requête adéquate pour une des 2 fonctions suivantes */
function list_query($what,$offset,$step,$limit)
{
	$link = dbconn();
	if ($what!="affect" and $what!="defect")
	{
		$query = "SELECT * FROM $what";
	} else if ($what=="affect")
	{
		$query = "SELECT * FROM fichiers WHERE id NOT IN (SELECT id_fichier FROM reference) ";
	} else if ($what=="defect")
	{
		$query = "SELECT (select ccourt FROM categorie WHERE id=id_categorie1) as cat1, (SELECT ccourt FROM categorie WHERE id=id_categorie2) as cat2,id_fichier,annee_prod,url FROM reference,fichiers WHERE fichiers.id=reference.id_fichier";
	}

	if ($limit==TRUE)
	{
		$query.=" LIMIT $step OFFSET $offset;";
	} else
	{
		$query.=";";
	}
	
	$result = pg_query($link, $query);
	pg_close($link);
	return $result;
}



/* affiche les éléments de la requête */
function display_list_entries($what,$offset,$step)
{
	$result = list_query($what,$offset,$step,TRUE);
	echo "<table>\n";
	while ($object = pg_fetch_object($result)) {
		switch($what) {
			case fichiers:
				echo "<tr><td>$object->id</td>";
				echo "<td><input type='checkbox' name='ids' value='".$object->id."'/>";
				echo "<td>$object->anne_prod</td><td>$object->url</td>\n"; break;
				echo "<td>$object->comment</td></tr>\n"; break;
			case categorie:
				echo "<tr><td>$object->id</td>";
				echo "<td><input type='checkbox' name='ids' value='".$object->id."'/>";
				echo "<td>$object->ccourt</td>";
				echo "<td>$object->clong</td></tr>\n"; break;
			case types: 
				echo "<tr><td>$object->id</td>";
				echo "<td><input type='checkbox' name='ids' value='".$object->id."'/>";
				echo "<td>$object->type</td></tr>\n"; break;
			case affect:
				echo "<tr><td>$object->id</td>\n";
				echo "<td><input type='checkbox' name='ids' value='".$object->id."'/>\n";
				echo "<td>$object->url</td></tr>\n";
				break;
			case defect:
				echo "<tr><td>$object->id_fichier</td>";
				echo "<td><input type='checkbox' name='ids' value='".$object->id_fichier."'/>";
				echo "<td>$object->cat1</td>";
				echo "<td>$object->cat2</td>";
				echo "<td>$object->url</td></tr>\n";
				break;
		}
	}
	echo "</table>\n";
}



/* affiche une liste permettant d'accéder aux éléments de la requête */
function display_list_access($what,$offset,$step)
{
	echo "<div class='admin-accesslist'>";
	$result = list_query($what,$offset,$step,FALSE);

	for($i=0; $i<pg_num_rows($result); $i+=$step)
	{
	 echo "<a href='management.php?what=$what&amp;current=$i'>".($i+1)."-".($i+$step)."</a> ";
	}
	echo "</div>";
}



/* affiche le formulaire d'action pour le type de données désigné */
function get_form($what) {

	echo "<form method='post' action='process.php'>\n";
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
			echo "\t\t<input type='text' name='id'	 size='2'	value='Id' />\n";
			echo "\t\t<input type='text' name='type' size='27' value='type' />\n";
			break;
		case "fichiers":
			echo "\t\t<input type='text' name='id'	 size='2'	value='Id' />\n";
			echo "\t\t<input type='text' name='url'	size='22' value='URL' />\n";
			echo "\t\t<input type='text' name='annee_prod' size='4' value='2004' /><br />\n";
			echo "\t\t<textarea name='comment' cols='35' row='4'>commentaires</textarea>\n";
			break;
		case "categorie":
			echo "\t\t<input type='text' name='id'		 size='2'	value='Id' />\n";
			echo "\t\t<input type='text' name='clong'	size='24' value='description longue' />\n";
			echo "\t\t<input type='text' name='ccourt' size='5' value='courte' />\n";
			break;
		case "affect":
			echo "\t\t<input type='text' name='id' size='2' value='Id' />\n";
			display_types_select();
			echo "<br />\n";
			display_categorie_select(); 
			break;
		case "defect":
			echo "\t\t<input type='text' name='id' size='2' value='Id' />\n";
			break;
	}
	echo "\t</fieldset>\n";
	echo "</form>\n";
}

?>
