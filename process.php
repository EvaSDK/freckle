<?
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
	}
	
/*	$id = $_POST['id'];*/
/*
	if( !is_integer( $id ) )
		header("Location: index.php"); 
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
				$query = "UPDATE fichiers SET url='".$_POST['url']."', annee_prod='".$_POST['annee_prod']."', commentaire='".$_POST['comment']."' WHERE id=''#ID#';";
				break;
			case "categorie":
				$_SESSION["message"] = "Cat�gorie modifi�";
				$query = "UPDATE categorie SET ccourt='".$_POST['ccourt']."', clong='".$_POST['clong']."' WHERE id='#ID#';";
				break;
		}
	} else if ($action=="Classer")
	{
		$_SESSION['message'] = "Fichier $id class�";
		 
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

	$cids = count( $ids );

	if( ($what=="Ajouter" or $what=="Modifier") and $cids!=1 )
	{
		$_SESSION['message'] = "Impossible de faire des requ�tes simultan�es pour ajouter des �l�ments";
		header("Location: ./management.php?what=$what");
	}

	$dblink = db_connect();
	for( $i=0; $i<$cids; $i++ )
	{
		db_query( $dblink, preg_replace("/.ID./",$ids[$i],$query) );
	}
	db_close($dblink);

	if( $cids==0 )
	{
		$_SESSION['message'] = "Rien � faire";
	}

	header("Location: ./management.php?what=$what");
?>
