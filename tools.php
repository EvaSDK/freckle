<?php
  session_start();
  include("inc/general.inc.php");
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
 <h1>Freckle</h1>
 <img src="./freckle.png" title="banni�re" alt="logo freckle" />
 </div>

 <div class="menu">
  <?php include("./inc/menu.inc.php"); ?>
 </div>

 <div class="content">

  <h2 class="titre-page">Outils et Recommandations</h2>

  <div class="box">
   <h3>Outils</h3>
   <p>Ceci est une liste non exhaustive des outils recommand�s pour se
    servir efficacement des fichiers de freckle ou pour proposer des documents.</p>
   <ul>
    <li><a href="http://www.adobe.com/products/acrobat/readstep2.html">Acrobat Reader</a>
     <p>Permet de visualiser le document <acronym title="Portable Document Format">PDF</acronym> et existe sur une grande vari�t� d'OS</p></li>
    <li><a href="http://piartt.free.fr/">Winzip et WinRAR</a>
     <p>Outils de compression classique</p></li>
    <li><a href="http://www.tug.org/teTeX/">teTex</a>|<a href="http://www.latex-project.org/">LaTeX</a>
     <p>C'est une distribution (comprendre un ensemble d'outils) pour faire du Latex. <strong>Latex</strong> est un language de mise en forme tr�s puissant qui � l'avantage d'�tre libre et facile � apprendre, parfait pour faire des rapports de TP.</p></li>
   </ul>
  </div>

  <div class="box">
   <h3>Recommandations</h3>
   <p>
    Afin d'uniformiser les types de fichiers disponibles sur ce site,
    nous recommendons l'utilsation du format 
    <acronym title="Portable Document Format">PDF</acronym>. Cela permet de consulter
    Freckle et ses documents sur n'importe quelle platforme (y-compris les stations Unix 
    HP-UX en 5004 et 5006). Afin de pouvoir mettre � jour les PDF facilement, nous vous 
    demandons de les fournir avec leur sources (LaTeX, OpenOffice, MSOffice, ...).
    Nous vous conseillons d'utiliser LaTeX (un peu complexe au d�but mais r�sultat professionel)
    ou bien OpenOffice qui propose en standard un bouton "Export to PDF" dans sa barre de menu.
   </p>
   <p>
    Notes: les documents existants seront progressivement amen�s vers 
    ces formats. Pour les utilisateurs de syst�me autres que windows, 
    merci d'enregistrer les documents au format dos, ceci afin que
    m�me nos camarades utilisant cet OS puissent �diter les fichiers
    sources.
   </p>
   <p>
    Pour ceux qui ne sauraient pas encore se servir de LaTeX et Cie. le
    Club*Nix organise des s�ances d'initiation (selon la demande)
    Pour plus d'informations envoyez un mail � 
    <a href="mailto:clubnix[at]esiee[point]fr">clubnix [at] esiee [point] fr</a>.
   </p>
   </div>

   <div class="box" id="gene">
    Freckle - Mis � jour le 26/07/2004
   </div>

  </div>

 <hr />

 <div class="foot">
  <?php include("./inc/foot.inc.php"); ?>
 </div>

</body>
</html>
