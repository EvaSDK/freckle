<?
	session_start();
	include("./inc/auth.inc.php");
	include("./inc/general.inc.php");

	if($_SESSION['admin']==FALSE) {
		header("Location: index.php"); 
	}
	
 
	$action = $_POST['action'];
	$what = $_POST['what'];
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
		 $query = "INSERT INTO reference (id_categorie,id_fichier,id_type) VALUES ('".$_POST['cat1']."','#ID#','".$_POST['type']."');";
		 
		 if ($_POST['cat2']!='')
		 {
			 $query .= "INSERT INTO reference (id_categorie,id_fichier,id_type) VALUES ('".$_POST['cat1']."','#ID#','".$_POST['type']."');";
		 }
	} else if ($action=="D�saffecter")
	{
		$_SESSION['message'] = "Fichier $id d�class�";
		$query = "DELETE FROM reference WHERE id_fichier='#ID#';";
	}

	//echo "$action,\n";
	//echo "<p>$query</p>";

	$ids = array();
	for( $i=0; $i<16; $i++)
	{
		if( isset($_POST['ids-'.$i]) )
			$ids[] = $i;
	}

	$cids = count( $ids );

	if( $what=="Ajouter" and $cids!=1 )
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

	header("Location: ./management.php?what=$what");
?>
