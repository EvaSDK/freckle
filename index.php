<?php
  session_start();
  include("inc/auth.inc.php");
  include("inc/general.inc.php");
  include("inc/forum.inc.php");

  echo "<?xml version='1.0' encoding='ISO-8859-1'?>\n";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html  xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr">
<head>
 <title>FRECKLE - Accueil</title>
 <link href="./style.css" type="text/css" media="screen" rel="stylesheet" />
 <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
</head>

<body>
 
 <div class="head">
 <h1>Freckle</h1>
 <img src="./freckle.png" title="bannière" alt="logo freckle" />
 </div>

 <div class="menu">
  <?php include("./inc/menu.inc.php"); ?>
 </div>

 <div class="content">

  <h2 class="titre-page">Accueil</h2>

  <div class="box">
   <h3>Qu'est ce que Freckle</h3>
   <p>
    <strong>Freckle</strong>, pour ceux qui ne le sauraient pas encore, est un site rassemblant tous les documents fournis par les élèves, les administratifs et les enseignants de l'ESIEE. Vous êtes <em>fortement encouragé</em> à contribuer en envoyant un mail avec vos documents à <a href="mailto:freckle[at]esiee[point]fr">freckle [at] esiee [point] fr</a>.
   </p>
   <ul>
    <li><a href="documents.php">Accéder aux documents</a></li>
    <li><a href="tools.php">Outils et recommandations</a></li>
    <li><a href="display.php?what=site">Les autres sites de l'ESIEE</a></li>
   </ul>
  </div>

  <div class="box">
   <h3>Nouveaux</h3>
   <h4>Les 10 documents les plus récents</h4>
   <table>
   <?php display_documents( "last", 0 ); ?>
   </table>
   <p>Voir la description des unités ci-dessous.</p>
  </div>

  <div class="box">
   <h3>Listes des catégories</h3>
   <?php display_categorie(); ?>
  </div>

  <div class="box" id="gene">
   Freckle - Mis à jour le 26/07/2004
  </div>

 </div>

 <hr />

 <div class="foot">
  <?php include("./inc/foot.inc.php"); ?>
 </div>

</body>
</html>
