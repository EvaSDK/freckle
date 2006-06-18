<?php
	session_start();
	require("./config/config.php");
	require("./inc/definitions.inc.php");

	include("./inc/auth.inc.php");
	include("./inc/general.inc.php");

	if($_SESSION['admin']==FALSE) {
		header("Location: index.php"); 
	}
 
	$action = $_POST['action'];
	$what = $_POST['what'];
	
	$cat1 = $_POST['cat1'];
	$cat2 = $_POST['cat2'];

	/* triage des cat�gories */
	if( $cat1 > $cat2 and $cat2!=0 )
	{
		$cat2 = $cat1;
		$cat1 = $_POST['cat2'];
	} else if ( $cat1==$cat2 )
	{
		$cat2 = 0;
	}



	/** d�but du traitement des donn�es */
/*
	switch( $action )
	{
		case "Ajouter"     : $arr = processAjout(); break;
		case "Supprimer"   : $arr = processSuppr(); break;
		case "Classer"     : $arr = processClass(); break;
		case "Modifier"    : $arr = processModif(); break;
		case "D�saffecter" : $arr = processDesaf(); break;
	}

	le r�sultat arr doit �tre un array ( message, query );
*/

	
	if($action=='Ajouter')
	{
		switch($what)
		{
			case "types":
				$_SESSION["message"] = "Nouveau type ajout�";
				$query = "INSERT INTO types (type) VALUES ('".$_POST['type']."');";
				break;
			case "fichiers":
				$_SESSION["message"] = "Nouveau fichier ajout�";
				$query = "INSERT INTO fichiers (url,annee_prod,commentaire) VALUES ('".$_POST['url']."','".$_POST['annee_prod']."','".$_POST['comment']."');";
				break;
			case "categorie":
				$_SESSION["message"] = "Nouvelle cat�gorie ajout�e";
				$query = "INSERT INTO categorie (ccourt,clong) VALUES ('".$_POST['ccourt']."','".$_POST['clong']."');";
				break;
		}
	} else if ($action=="Supprimer")
	{
		switch($what)
		{
			case "types":
				$_SESSION["message"] = "Type supprim�";
				$query = "DELETE FROM types WHERE id='#ID#';";
				break;
			case "fichiers":
				$_SESSION["message"] = "Fichier supprim�";
				$query = "DELETE FROM fichiers WHERE id='#ID#';";
				break;
			case "categorie":
				$_SESSION["message"] = "Cat�gorie supprim�";
				$query = "DELETE FROM categorie WHERE id='#ID#';";
				break;
		}
	} else if ($action=="Modifier")
	{
		switch($what)
		{
			case "types":
				$_SESSION["message"] = "Type modifi�";
				$query = "UPDATE types SET type='".$_POST['type']."' WHERE id='#ID#';";
				break;
			case "fichiers":
				$_SESSION["message"] = "Fichier modifi�";
				$query = "UPDATE fichiers SET url=\"".$_POST['url']."\", annee_prod=\"".$_POST['annee_prod']."\", commentaire=\"".$_POST['comment']."\" WHERE id='#ID#';";
				break;
			case "categorie":
				$_SESSION["message"] = "Cat�gorie modifi�";
				$query = "UPDATE categorie SET ccourt='".$_POST['ccourt']."', clong='".$_POST['clong']."' WHERE id='#ID#';";
				break;
		}
	} else if ($action=="Classer")
	{
		$_SESSION['message'] = "Fichier(s) class�(s)";
		 
		if( isset($cat2) )
		{
			$query = "INSERT INTO reference (id_categorie,id_fichier,id_type) VALUES ('".$cat1."','#ID#','".$_POST['type']."');";
			$query .= "INSERT INTO reference (id_categorie,id_fichier,id_type) VALUES ('".$cat2."','#ID#','".$_POST['type']."');";			
		} else {
			$query = "INSERT INTO reference (id_categorie,id_fichier,id_type) VALUES ('".$cat1."','#ID#','".$_POST['type']."');";
		}
	} else if ($action=="D�saffecter")
	{
		$_SESSION['message'] = "Fichier $id d�class�";
		$query = "DELETE FROM reference WHERE id_fichier='#ID#';";
		
	} else if ($action=="upload") {
	
		$uploadfile = $repos_abs . basename($_FILES['userfile']['name']);
		
		if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile))
		{
			$_SESSION['message'] = "Fichi� t�l�charg� avec succ�s !";
		} else {
			$_SESSION['message'] = "Possible file upload attack!\n";
		}

		$_SESSION["message"] .= "Nouveau fichier ajout�";
		
		$query = "INSERT INTO fichiers (url,annee_prod,commentaire) VALUES ('".$_POST['url']."','".$_POST['annee_prod']."','".$_POST['comment']."');";

		if( isset($cat2) )
		{
			$query .= "INSERT INTO reference (id_categorie,id_fichier,id_type) VALUES ('".$cat1."'','#ID#','".$_POST['type']."');";
			$query .= "INSERT INTO reference (id_categorie,id_fichier,id_type) VALUES ('".$cat2."'','#ID#','".$_POST['type']."');";
		} else {
			$query .= "INSERT INTO reference (id_categorie,id_fichier,id_type) VALUES ('".$cat1."'','#ID#','".$_POST['type']."');";
		}
	}

	//echo "$action,\n";
	//echo "<p>$query</p>";

	$ids = array();

	foreach( $_POST as $key=>$value )
	{
		if( strpos($key,"ids-")!==FALSE )
			$ids[] = $value;
	}

/*
 * Ex�cution de la requ�te
 */

	if( $action=="Ajouter" )
	{
		//$_SESSION['message'] = "Entr�e ajout�e";
		//header("Location: ./management.php?what=$what");
		$db->query( $query );
		
	} else {
		$cids = count( $ids );

		//print_r( $ids );
#		$sth = $db->prepare( $query );
#		$res = $db->executeMultiple( $sth,$ids );

#print_r( $ids );

		for( $i=0; $i<$cids; $i++ )
		{
			$arr = explode( ';', $query );
			foreach( $arr as $v ) {
				$res = $db->query( preg_replace("/.ID./",$ids[$i],$v) );
				//echo "<p>".preg_replace("/\?/",$ids[$i],$v)."</p>";

				//if( PEAR::isError( $res ) )
				//	print_r( $res );
			}
		}
		
	}


	if( $cids==0 and $action!="Ajouter" )
	{
		$_SESSION['message'] = "Rien � faire";
	}

	header("Location: ./management.php?what=$what");
?>
