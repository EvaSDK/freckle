<?php
  session_start();
  include("inc/auth.inc.php");
  include("inc/forum.inc.php");
  include("inc/general.inc.php");

  echo "<?xml version='1.0' encoding='ISO-8859-1'?>\n";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html  xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr">
<head>
 <title>FRECKLE - Documents</title>
 <link href="./style.css" type="text/css" media="screen" rel="stylesheet" />

 <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
</head>

<body>

 <div class="head">
 <h1>Freckle</h1>
 <img src="freckle.png" title="bannière" alt="logo freckle" />
 </div>

 <div class="menu">
  <?php include("./inc/menu.inc.php"); ?>
 </div>

 <div class="content">
  <h2 class="titre-page">Documents</h2>

  <div class="box">
   <h3>Trouver...</h3>
   <p>Afficher les documents de la catégorie:</p>
   <form action="#" method='post'>
    <fieldset>
     <?php display_categorie_select(); ?>
    <input type="submit" name="Submit2" value="Voir" />
   </fieldset>
  </form>
 </div>

 <div class="box">
   <?php
    $cat1=$_POST[ "cat1" ];
    $cat2=$_POST[ "cat2" ];
    if( $cat1 == 0 )
     {
       echo "<h3>Nouveaux</h3>\n";
       echo "<h4>Les 10 documents les plus récents</h4>\n";
       display_documents("last",0);
     } else {
       echo "<h3>Résultats</h3>\n";
       display_documents($cat1,$cat2);
     }
   ?>
   <p>Aidez-nous à améliorer Freckle, vous avez une correction à apporter, un document est mal classé, une suggestion pour le site? <a href="mailto:freckle[at]esiee[point]fr">Envoyez-nous un mail</a></p>

 </div>

  <div class="box" id="gene">
   Freckle - Mis à jour le 26/07/2004
  </div>

 </div>

 <hr />

 <div class="foot" >
  <?php include("./inc/foot.inc.php"); ?>
 </div>

</body>
</html>
