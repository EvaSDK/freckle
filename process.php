<?php
	session_start();
	require("./inc/definitions.inc.php");
	require("./inc/backend_sql.php");

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

	if($action=='Envoyer')
	{
		$uploaddir = $repos_abs;
		$uploadrel = $repos_html;
		$uploadfile = $uploaddir . basename($_FILES['userfile']['name']);
				 
		if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile))
		{
			$_SESSION['message'] = "Photo enregistr�e avec succ�s !";
		} else {
			$_SESSION['message'] = "Possible file upload attack!\n";
		}

		$result = array();
		preg_match("/([A-Z]+)-([0-9.]+)-(\w+)\.\w+/", $uploadfile, $result );

		//print_r( $result );

		/* d�coupe les unit�s */
		$units = split( "\.", $result[2] );

		echo "Unit� : ";
		echo "<pre>\n";
		print_r( $units );
		echo "</pre>\n";

		echo "Mati�re : ".$result[1]."<br/>";
		echo "Nom du document : ".$result[3]."<br/>";
		
		$dblink = db_connect();
		$result = db_query( $dblink, "SELECT count(*) as CPT FROM fichiers WHERE url='".$uploadfile."'" );
		$table = db_fetch_array( $result );

		if( $table['CPT']>1 ) {
			$_SESSION['message'] = "Le fichier existe d�j�.<br />";
		} else {
			$_SESSION['message'] = "Le fichier $uploadfile a �t� ajout�.<br />";
			db_query( $dblink, "INSERT INTO fichiers (url,annee_prod,commentaire) VALUES ('".$uploadfile."','".date("Y", time())."','')" );
		}

		foreach( $units as $key=>$value )
		{
			if( is_numeric( $year = substr($value,1,1)+0) )
				$value = "I".$year;

  	  $result = db_query( $dblink, "SELECT count(*) as CPT,id FROM categorie WHERE ccourt='".$value."'" );
  	  $table = db_fetch_array( $result );

    	if( $table['CPT']>1 ) {
  	    $_SESSION['message'] = "La cat�gorie $value existe.<br />";
    	} else {
  	    $_SESSION['message'] = "La cat�gorie $value a �t� ajout�e.<br />";
    	  db_query( $dblink, "INSERT INTO categorie (ccourt,clong) VALUES ('".$value."','".$value."')" );
    	}
		}
		
		db_close( $dblink );

	}
	
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
			$query .= "INSERT INTO reference (id_categorie1,id_categorie2,id_fichier,id_type) VALUES ('".$cat1."','".$cat2."','#ID#','".$_POST['type']."');";
		} else {
			$query = "INSERT INTO reference (id_categorie1,id_categorie2,id_fichier,id_type) VALUES ('".$cat1."','0','#ID#','".$_POST['type']."');";
		}
	} else if ($action=="D�saffecter")
	{
		$_SESSION['message'] = "Fichier $id d�class�";
		$query = "DELETE FROM reference WHERE id_fichier='#ID#';";
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

	/* connection � la base */
	$dblink = db_connect();

	if( $action=="Ajouter" )
	{
		//$_SESSION['message'] = "Entr�e ajout�e";
		//header("Location: ./management.php?what=$what");
		db_query( $dblink, $query );
		
	} else {
		$cids = count( $ids );

		//print_r( $ids );

		for( $i=0; $i<$cids; $i++ )
		{
			db_query( $dblink, preg_replace("/.ID./",$ids[$i],$query) );
			//echo "<p>".preg_replace("/.ID./",$ids[$i],$query)."</p>";

		}
	}

	/* d�connexion de la base */
	db_close($dblink);

	if( $cids==0 and $action!="Ajouter" )
	{
		$_SESSION['message'] = "Rien � faire";
	}

	header("Location: ./management.php?what=$what");
?>
