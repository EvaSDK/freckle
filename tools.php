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
 <title>FRECKLE - Outils</title>
 <link href="./style.css" type="text/css" media="screen" rel="stylesheet" />
 <!-- script from www.alistapart.com -->
 <!-- <script type="text/javascript" src="styleswitcher.js"></script> -->

 <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
</head>

<body>
 
 <div class="head">
 <h1>Freckle in da mix</h1>
 <img src="essai-freckle.png" title="bann" alt="logo" />
 </div>

  <div class="menu">
  <ul>
  <li><h2>Général</h2>
  <ul>
   <li><a href="index2.php">Accueil</a></li>
   <li><a href="documents.php">Documents</a></li>
   <li><a href="display.php?what=site">Autres sites ESIEE</a></li>
   <li><a href="tools.php">Outils et recommandations</a></li>
  </ul></li>
  <li><h2>News</h2>
  <ul>
   <?php display_news( "short", $conn ); ?>
   <li><a href="display.php?what=news">En savoir plus</a></li>
  </ul></li>
  <li><h2>Statistiques</h2>
  <ul class="pop">
   <li>Visiteurs:       <?php visiteurs();           ?></li>
   <li>Pages Affichées: <?php viewed_pages();        ?></li>
   <li>Documents:       <?php registred_documents(); ?></li>
   <li>Sites Listés:    <?php registred_sites();     ?></li>
  </ul></li>
  <li><h2>Webmaster</h2>
  <ul class="pop">
   <li><a href="./_admin/auth.php?what=login">Administration</a></li>
   <li><a href="http://www.esiee.fr">Hébergement</a></li>
   <li><a href="http://piartt.free.fr">Mainteneur Précédent</a></li>
  </ul></li>
  </ul>
  </div>

 <div class="content">

  <h2 class="titre-page">Outils et Recommandations</h2>

  <div class="box">
   <h3>Outils</h3>
   <p>
    Ceci est une liste non exhaustive des outils nécessaires pour se
    servir des fichiers de freckle ou pour proposer des documents.
   </p>
   <object>
   <ul>
    <li>
     <a href="http://www.adobe.com/products/acrobat/readstep2.html">Acrobat Reader</a>
     <p>Permet de visualiser le document PDF et existe sur une grande variété d'OS</p>
    </li>
    <li>
     <a href="http://piartt.free.fr/">Winzip et WinRAR</a>
     <p>Outils de compression classique</p>
    </li>
    <li>
     <a href="http://www.tug.org/teTeX/">teTex</a>
     <p>Tout comme LaTeX, outils de mise en forme très puissant qui à l'avantage d'être libre et relativement facile à apprendre</p>
    </li>
    <li>
     <a href="http://www.latex-project.org/">LaTeX</a>
     <p>Voir teTeX</p>
    </li>
   </ul>
   </object>
  </div>

  <div class="box">
   <h3>Recommandations</h3>
   <p>
    Afin d'uniformiser le type de fichier qu'on peut trouver sur ce site,
    vous êtes prié de fournir des documents de préférence sous forme de
    <acronym title="Portable Document Format">PDF</acronym> avec leur sources
    de préférence en LaTeX ou en teTeX, le tout zippé. Vous pouvez pour cela
    vous servir des outils évoqués ci-dessus.
   </p>
   <p>
    Notes: les documents existants seront progressivement amenés vers 
    ces formats. Pour les utilisateurs de système autres que windows, 
    merci d'enregistrer les documents au format dos, ceci afin que
    même nos camarades utilisant cet OS puissent éditer les fichiers
    sources.
   </p>
   <p>
    Pour ceux qui ne saurait pas encore se servir de LaTeX et Cie. le
    Club*nix organisera probablement quelques séances d'initiation
    dans les prochains mois. Pour plus d'informations envoyez un mail
    à <a href="mailto://clubnix@esiee.fr">clubnix@esiee.fr</a>.
   </p>
   </div>

   <div class="box" id="gene">
    Freckle - Mis à jour le 19/02/2004 - Généré en <?php echo round(microtime() - $start_time, 3); ?> secondes.
   </div>

  </div>

 <hr />

 <div class="foot">
  <!-- Validation -->
  <a href="http://validator.w3.org/check/referer">Valid XHtml</a> |
  <a href="http://jigsaw.w3.org/css-validator/">Valid CSS</a> |
  <!-- Fin Validation -->

  <!-- Creative Commons License -->
   <!-- <a href="http://creativecommons.org/licenses/by-nc-sa/1.0/">
    <img alt="Creative Commons License" border="0" src="http://creativecommons.org/images/public/somerights.gif" />
   </a> -->
   This work is licensed under a <a href="http://creativecommons.org/licenses/by-nc-sa/1.0/">Creative Commons License</a>
  <!-- /Creative Commons License -->

  <!--
   <rdf:RDF xmlns="http://web.resource.org/cc/"
            xmlns:dc="http://purl.org/dc/elements/1.1/"
            xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#">
    <Work rdf:about="">
     <dc:type rdf:resource="http://purl.org/dc/dcmitype/Interactive" />
     <license rdf:resource="http://creativecommons.org/licenses/by-nc-sa/1.0/" />
    </Work>

    <License rdf:about="http://creativecommons.org/licenses/by-nc-sa/1.0/">
     <permits rdf:resource="http://web.resource.org/cc/Reproduction" />
     <permits rdf:resource="http://web.resource.org/cc/Distribution" />
     <requires rdf:resource="http://web.resource.org/cc/Notice" />
     <requires rdf:resource="http://web.resource.org/cc/Attribution" />
     <prohibits rdf:resource="http://web.resource.org/cc/CommercialUse" />
     <permits rdf:resource="http://web.resource.org/cc/DerivativeWorks" />
     <requires rdf:resource="http://web.resource.org/cc/ShareAlike" />
    </License>
   </rdf:RDF>
  -->
  and copyrighted by EvaSDK &copy;2004
 </div>

</body>
</html>
