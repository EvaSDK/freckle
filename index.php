<?php
	ini_set("arg_separator.output", "&amp;");
	session_start();
	require("./inc/definitions.inc.php");
	require("./inc/backend_sql.php");
	
	require("./inc/auth.inc.php");
	require("./inc/general.inc.php");
	include("./inc/management.inc.php");

    if ( !isset($_SERVER["HTTP_ACCEPT"]) ||
        stristr($_SERVER["HTTP_ACCEPT"], "application/xhtml+xml") )
    {
	    header("Content-type: application/xhtml+xml");
    	echo '<?xml version="1.0" encoding="iso-8859-1"?>' . "\n";
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html	xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr">
<head>
	<title>FRECKLE</title>
	<link href="./styles/style-light-blue.css" type="text/css" media="screen" rel="alternate stylesheet" title="light blue"/>
	<link href="./styles/style.css" type="text/css" media="screen" rel="alternate stylesheet" title="test"/>
	<link href="./styles/style-noel.css" type="text/css" media="screen" rel="stylesheet" title="No�l"/>
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
</head>

<body>
 
	<div class="wrapper">
 
		<div class="head">
			<h1>Freckle</h1>
		</div>

		<div class="menu">
		<?php include("./inc/menu.inc.php"); ?>
		</div>

	 <div class="content">
	
<?php

	if( isset($_GET['what']) )
		$what = $_GET['what'];
	else
		$what = "accueil";

	switch( $what )
	{
		case "accueil" : include("accueil.php"); break;
		case "search"  : include("documents.php"); break;
		case "tools"	 : include("tools.php"); break;
	}

?>
		</div>

	 <hr />

	 <div class="foot">
		<?php include("./inc/foot.inc.php"); ?>
	 </div>
	 
	</div>

</body>
</html>
