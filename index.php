<?php
  $start_time = microtime();
  ob_start();

  @include("_admin/param.php");
  include("forums/include.php");
  include("./inc/stats.inc.php");
  include("./inc/general.inc.php");

  $countup=0;
  if ( $_COOKIE["freckle-esiee"] != "1" )
  {
    SetCookie("freckle-esiee","1",time()+8640);
    $countup=1;
  }

  $conn = mysql_pconnect($dns,$usr,$pwd);
  mysql_select_db ( $db , $conn );

  echo "<?xml version='1.0' encoding='ISO-8859-1'?>\n";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html  xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr">
<head>
 <title>FRECKLE - Accueil</title>
 <link href="./style.css" type="text/css" media="screen" rel="stylesheet" />
 <!-- script from www.alistapart.com bouh -->
 <!-- <script type="text/javascript" src="styleswitcher.js"></script> -->

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
    <strong>Freckle</strong>, pour ceux qui ne le sauraient pas encore, est un site rassemblant tous les documents fournis par les élèves, les administratifs et les enseignants de l'ESIEE. Vous êtes <em>fortement encouragé</em> à contribuer en envoyant un mail avec vos documents à <a href="mailto://freckle[at]esiee[point]fr">freckle [at] esiee [point] fr</a>.
   </p>
   <ul>
    <li><a href="documents.php">Accéder au documents</a></li>
    <li><a href="tools.php">Outils et recommandations</a></li>
    <li><a href="display.php?what=site">Les autres sites de l'ESIEE</a></li>
   </ul>
  </div>

  <div class="box">
   <h3>Nouveaux</h3>
   <h4>Les 10 documents les plus récents</h4>
   <table>
   <?php
     display_documents( "10", $conn );
   ?>
   </table>
   <p>
	La description des unités est ci-après.
   </p>
  </div>

  <div class="box">
   <h3>Listes des catégories</h3>
   <?php display_categorie(); ?>
  </div>

  <div class="box" id="gene">
   Freckle - Mis à jour le 19/02/2004 - Généré en <?php echo round(microtime() - $start_time, 3); ?> secondes.
  </div>

 </div>

 <hr />

 <div class="foot">
  <?php include("./inc/foot.inc.php"); ?>
 </div>

</body>
</html>
