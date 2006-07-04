<?php
	session_start();
	require("./config/config.php");
	require("./inc/definitions.inc.php");

	include("./inc/auth.inc.php");
	include("./inc/general.inc.php");

  require_once("./pear/Compat.php");
  require_once("./pear/Compat/Function/array_change_key_case.php");

	if($_SESSION['admin']==FALSE) {
		header("Location: index.php"); 
	}
 
	$action = $_POST['action'];
	$what = $_POST['what'];
	
	$cat1 = $_POST['cat1'];
	$cat2 = $_POST['cat2'];

	/* triage des catégories */
	if( $cat1 > $cat2 and $cat2!=0 )
	{
		$cat2 = $cat1;
		$cat1 = $_POST['cat2'];
	} else if ( $cat1==$cat2 )
	{
		$cat2 = 0;
	}



	/** début du traitement des données */
/*
	switch( $action )
	{
		case "Ajouter"     : $arr = processAjout(); break;
		case "Supprimer"   : $arr = processSuppr(); break;
		case "Classer"     : $arr = processClass(); break;
		case "Modifier"    : $arr = processModif(); break;
		case "Désaffecter" : $arr = processDesaf(); break;
	}

	le résultat arr doit être un array ( message, query );
*/

	
	if($action=='Ajouter')
	{
		switch($what)
		{
			case "types":
				$_SESSION["message"] = "Nouveau type ajouté";
				$query = "INSERT INTO types (type) VALUES ('".$_POST['type']."');";
				break;
			case "fichiers":
				$_SESSION["message"] = "Nouveau fichier ajouté";
				$query = "INSERT INTO fichiers (url,annee_prod,commentaire) VALUES ('".$_POST['url']."','".$_POST['annee_prod']."','".$_POST['comment']."');";
				break;
			case "categorie":
				$_SESSION["message"] = "Nouvelle catégorie ajoutée";
				$query = "INSERT INTO categorie (ccourt,clong) VALUES ('".$_POST['ccourt']."','".$_POST['clong']."');";
				break;
		}
	} else if ($action=="Supprimer")
	{
		switch($what)
		{
			case "types":
				$_SESSION["message"] = "Type supprimé";
				$query = "DELETE FROM types WHERE id='#ID#';";
				break;
			case "fichiers":
				$_SESSION["message"] = "Fichier supprimé";
				$query = "DELETE FROM fichiers WHERE id='#ID#';";
				break;
			case "categorie":
				$_SESSION["message"] = "Catégorie supprimé";
				$query = "DELETE FROM categorie WHERE id='#ID#';";
				break;
		}
	} else if ($action=="Modifier")
	{
		switch($what)
		{
			case "types":
				$_SESSION["message"] = "Type modifié";
				$query = "UPDATE types SET type='".$_POST['type']."' WHERE id='#ID#';";
				break;
			case "fichiers":
				$_SESSION["message"] = "Fichier modifié";
				$query = "UPDATE fichiers SET url=\"".$_POST['url']."\", annee_prod=\"".$_POST['annee_prod']."\", commentaire=\"".$_POST['comment']."\" WHERE id='#ID#';";
				break;
			case "categorie":
				$_SESSION["message"] = "Catégorie modifié";
				$query = "UPDATE categorie SET ccourt='".$_POST['ccourt']."', clong='".$_POST['clong']."' WHERE id='#ID#';";
				break;
		}
	} else if ($action=="Classer")
	{
		$_SESSION['message'] = "Fichier(s) classé(s)";
		 
		if( isset($cat2) )
		{
			$query = "INSERT INTO reference (id_categorie,id_fichier,id_type) VALUES ('".$cat1."','#ID#','".$_POST['type']."');";
			$query .= "INSERT INTO reference (id_categorie,id_fichier,id_type) VALUES ('".$cat2."','#ID#','".$_POST['type']."');";			
		} else {
			$query = "INSERT INTO reference (id_categorie,id_fichier,id_type) VALUES ('".$cat1."','#ID#','".$_POST['type']."');";
		}
	} else if ($action=="Désaffecter")
	{
		$_SESSION['message'] = "Fichier $id déclassé";
		$query = "DELETE FROM reference WHERE id_fichier='#ID#';";
		
	}
	
	
	/* Gestion de la page d'upload */
	if ($action=="Télécharger") 
	{
		$uploadfile = $repos_abs . "upload/". basename($_FILES['userfile']['name']);		
		if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile))
		{
			$_SESSION['message'] = "Fichier téléchargé avec succès !";
		} else {
			$_SESSION['message'] = "Possible file upload attack!\n";
		}

		$_SESSION["message"] .= "<br />Nouveau fichier ajouté";
		$file = "file://upload/". basename($_FILES['userfile']['name']);	
		$query = "INSERT INTO fichiers (url,annee_prod,commentaire) VALUES ('".$file."','".$_POST['annee_prod']."','".$_POST['comment']."');";
		$db->query( $query );
		$result = $db->getAll( "SELECT id FROM fichiers WHERE url='".$file."'", DB_FETCHMODE_ASSOC );

		$id = $result[0]['id'];

		if( count( $result ) > 1 )
			$_SESSION['message'] .= "<br />Attention un fichier porte déjà ce nom.";

		if( isset($cat2) )
		{
			$query = "INSERT INTO reference (id_categorie,id_fichier,id_type) VALUES ('".$cat1."','$id','".$_POST['type']."');";
			$db->query( $query );
			$query = "INSERT INTO reference (id_categorie,id_fichier,id_type) VALUES ('".$cat2."','$id','".$_POST['type']."');";
			$db->query( $query );
		} else {
			$query = "INSERT INTO reference (id_categorie,id_fichier,id_type) VALUES ('".$cat1."','$id','".$_POST['type']."');";
			$db->query( $query );
		}
	}	else {
		//echo "$action,\n";
		//echo "<p>$query</p>";

		$ids = array();
		foreach( $_POST as $key=>$value )
		{
			if( strpos($key,"ids-")!==FALSE )
				$ids[] = $value;
		}

		/* Exécution de la requête */
		if( $action=="Ajouter" )
		{
			$db->query( $query );
			
		} else {
			$cids = count( $ids );
			for( $i=0; $i<$cids; $i++ )
			{
				$arr = explode( ';', $query );
				foreach( $arr as $v ) {
					$res = $db->query( preg_replace("/.ID./",$ids[$i],$v) );
				}
			}		
		}

		if( $cids==0 and $action!="Ajouter" )
			$_SESSION['message'] = "Rien à faire";
	}
	
	/* Redirection finale */
	header("Location: ./management.php?what=$what");
?>
