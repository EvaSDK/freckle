<?php
  include("param.php");
  include("../forums/include.php");
  include("../inc/stats.inc");
  include("../inc/news.inc");

  $conn = mysql_pconnect($dns,$usr,$pwd);
  mysql_select_db ( $db , $conn );

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html  xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr">
<head>
 <title>FRECKLE</title>
 <link href="./style.css" type="text/css" media="screen" rel="stylesheet" />
 <!-- script from www.alistapart.com -->
 <!-- <script type="text/javascript" src="styleswitcher.js"></script> -->

 <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
</head>

<body>
 
 <div class="head">
 <h1>Freckle in da mix</h1>
 <img src="../essai-freckle.png" title="bann" style="float: right; height: 74px; width: 150px; position: absolute; top: 0; right: 0;"/>
 </div>

 <div class="content">

  <div class="menu">
  <ul>

  <li><h2>Général</h2>
  <ul>
   <li><a href="index.php">Accueil</a></li>
   <li><a href="documents.php">Documents</a></li>
   <li><a href="sites.php">Autres sites ESIEE</a></li>
   <li><a href="tools.php">Outils et recommandations</a></li>
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
   <li><a href="./index.php">Retour au site</a></li>
  </ul></li>
  </ul>
  </div>


  <h2 class="titre-page">Administration</h2>

  <div class="box">
   <h3>Requète</h3>
   <object>
    <form action='$PHP_SELF' method="post" enctype="multipart/form-data">
     <input type="hidden" name="action" value="query" />
     <input type="hidden" name="dbname" value="$dbname" />
     <textarea name="queryStr" cols="70" rows="5">SELECT * FROM </textarea>
     <br /><br />
     <input type="submit" value="Go" />
    </form>
   </object>
  </div>
 </div>

  <div class="foot">
  <!-- Validation -->
  <a href="http://validator.w3.org/check/referer">Valid XHtml</a> |
  <a href="http://jigsaw.w3.org/css-validator/">Valid CSS</a> |
  <!-- Fin Validation -->

  <!-- Creative Commons License -->
   <!-- <a href="http://creativecommons.org/licenses/by-nc-sa/1.0/">
    <img alt="Creative Commons License" border="0" src="http://creativecommons.org/images/public/somerights.gif" />
   </a> -->
   This work is licensed under a <a href="http://creativecommons.org/licenses/by-nc-sa/1.0/">Creative Commons License</a>.
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

  
