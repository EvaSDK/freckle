<?php
  include("param.php");
  include("../forums/include.php");
  include("../inc/auth.inc.php");
  include("../inc/stats.inc.php");

  $conn = mysql_pconnect($dns,$usr,$pwd);
  mysql_select_db ( $db , $conn );

  if ( $what == "logout" ) {
    $success = logout_admin();
  }

  echo "<?xml version='1.0' encoding='ISO-8859-1'?>\n";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html  xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr">
<head>
 <title>FRECKLE</title>
 <link href="../style.css" type="text/css" media="screen" rel="stylesheet" />
 <!-- script from www.alistapart.com -->
 <!-- <script type="text/javascript" src="styleswitcher.js"></script> -->

 <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
</head>

<body>
 
 <div class="head">
 <h1>Freckle in da mix</h1>
 <img src="../freckle.png" title="bannière" alt="logo freckle" />
 </div>

 <div class="menu">
  <ul>
  <li><h2>Général</h2>
  <ul>
   <li><a href="../index.php">Accueil</a></li>
   <li><a href="../documents.php">Documents</a></li>
   <li><a href="../display.php?what=site">Autres sites ESIEE</a></li>
   <li><a href="../tools.php">Outils et recommandations</a></li>
  </ul></li>
  <li><h2>Statistiques</h2>
  <ul class="pop">
   <li>Visiteurs:       <?php visiteurs();           ?></li>
   <li>Pages Affichées: <?php viewed_pages();        ?></li>
   <li>Documents:       <?php registred_documents(); ?></li>
   <li>Sites Listés:    <?php registred_sites();     ?></li>
  </ul></li>
  <li><h2>Webmaster</h2>
  <ul>
   <li><a href="../index.php">Retour au site</a></li>
  </ul></li>
  </ul>
 </div>

 <div class="content">

  <h2 class="titre-page">Administration</h2>

  <div class="box">
   <h3>Authentification</h3>
   <?php switch( $what ) {
     case "logout":
       if ( $success == TRUE ) {
         echo "<p>Vous êtes déloggé.\n";
       } else {
         echo "<p>Une erreur s'est produite.\n";
       }
       echo " <a href='auth.php?what=login'>Se relogger</a>\n</p>";
       break;
     case "login" :
     default:
       login_admin(); break;
     }
   ?>
   <p>Le cookie expirera au bout de 15 minutes</p>
  </div>
 </div>

 <hr />

 <div class="foot">
  <?php include("../inc/foot.inc.php"); ?>
 </div>

</body>
</html>

  
