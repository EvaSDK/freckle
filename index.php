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

	<?php

	if( isset($_GET['action']) )
		$action = $_GET['action'];
	else
		$action = "accueil";

	switch( $action )
	{
		case "accueil" : include("accueil.php"); break;
		case "docs"    : include("documents.php"); break;
		case "tools"   : include("tools.php"); break;
	}

	?>

  <div class="box" id="gene">
   Freckle - Mis à jour le 24/10/2004
  </div>

 </div>

 <hr />

 <div class="foot">
  <?php include("./inc/foot.inc.php"); ?>
 </div>

</body>
</html>
