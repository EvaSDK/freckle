<?php
  $start_time = microtime();
  ob_start();

  @include("_admin/param.php");
  include("forums/include.php");
  include("./inc/stats.inc");
  include("./inc/news.inc");

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
 <img src="./logo_freckle.png" title="bann" alt="toto" />
 </div>

 <div class="menu">
  <?php include("menu.inc.php"); ?>
 </div>

 <div class="content">

  <h2 class="titre-page">Accueil</h2>

  <div class="box">
   <h3>Qu'est ce que Freckle</h3>
   <p>
    <strong>Freckle</strong>, pour ceux qui ne le sauraient pas encore, est un site rassemblant tous les documents fournis par les �l�ves, les administratifs et les enseignants de l'ESIEE. Vous �tes <em>fortement encourag�</em> � contribuer en envoyant un mail avec vos documents � <strong>freckle [at] esiee [point] fr</strong>.
   </p>
   <ul>
    <li><a href="documents.php">Acc�der au documents</a></li>
    <li><a href="tools.php">Outils et recommandations</a></li>
    <li><a href="display.php?what=site">Les autres sites de l'ESIEE</a></li>
   </ul>
  </div>

  <div class="box">
   <h3>Nouveaux</h3>
   <h4>Les 10 documents les plus r�cents</h4>
   <table>
   <?php
     display_documents( "10", $conn );
   ?>
   </table>
   <p>
	La description des unit�s est ci-apr�s.
   </p>
  </div>

  <div class="box">
   <h3>Listes des cat�gories</h3>
   <?php display_categorie(); ?>
  </div>
<!--
  <div class="box">
   <h3>Actualit�s</h3>
   <?php display_actu( "short", $conn ); ?>
   <h4>
    <img src="_img_actu/next.gif" alt="next"/>
    Anciennes Nouvelles:
   </h4>
   <p>
    <dl>
     <dd><a href="./display.php?what=actu">Pour acc�der aux anciennes nouvelles</a></dd>
	 <dd>Pour faire passer un message ou annoncer un �v�nement: <a href="mailto:freckle[@]esiee[.]fr">freckle[@]esiee[.]fr</a></dd>
    </dl>
   </p>
  </div>
-->

  <div class="box" id="gene">
   Freckle - Mis � jour le 19/02/2004 - G�n�r� en <?php echo round(microtime() - $start_time, 3); ?> secondes.
  </div>

 </div>

 <hr />

 <div class="foot">
  <?php include("foot.inc.php"); ?>
 </div>

</body>
</html>
