<?
  session_start();
  include("./inc/auth.inc.php");
  include("./inc/general.inc.php");

  if($_SESSION['admin']==FALSE) {
    header("Location: index.php"); 
  }
  
 
  $action = $_POST['action'];
  $what = $_POST['what'];
  $id = $_POST['id'];

  if( !is_integer( $id ) )
    header("Location: index.php"); 


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
        $query = "DELETE FROM types WHERE id=$id;";
	break;
      case "fichiers":
        $_SESSION["message"] = "Fichier supprimé";
        $query = "DELETE FROM fichiers WHERE id=$id;";
	break;
      case "categorie":
        $_SESSION["message"] = "Catégorie supprimé";
        $query = "DELETE FROM categorie WHERE id=$id;";
	break;
    }
  } else if ($action=="Modifier")
  {
    switch($what)
    {
      case "types":
        $_SESSION["message"] = "Type modifié";
        $query = "UPDATE types SET type='".$_POST['type']."' WHERE id=$id;";
	break;
      case "fichiers":
        $_SESSION["message"] = "Fichier modifié";
        $query = "UPDATE fichiers SET url='".$_POST['url']."', annee_prod='".$_POST['annee_prod']."', commentaire='".$_POST['comment']."' WHERE id=$id;";
	break;
      case "categorie":
        $_SESSION["message"] = "Catégorie modifié";
        $query = "UPDATE categorie SET ccourt='".$_POST['ccourt']."', clong='".$_POST['clong']."' WHERE id=$id;";
	break;
    }
  } else if ($action=="Classer")
  {
     $_SESSION['message'] = "Fichier $id classé";
     $query = "INSERT INTO reference (id_categorie,id_fichier,id_type) VALUES ('".$_POST['cat1']."','$id','".$_POST['type']."');";
     
     if ($_POST['cat2']!='')
     {
       $query .= "INSERT INTO reference (id_categorie,id_fichier,id_type) VALUES ('".$_POST['cat1']."','$id','".$_POST['type']."');";
     }
  } else if ($action=="Désaffecter")
  {
    $_SESSION['message'] = "Fichier $id déclassé";
    $query = "DELETE FROM reference WHERE id_fichier='".$id."';";
  }

  //echo "$action,\n";
  //echo "<p>$query</p>";

  $dblink = dbconn();
  pg_query ($dblink, $query);
  pg_close($dblink);
  header("Location: ./management.php?what=$what");
?>
