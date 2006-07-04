<?php
/**
 * Page d'entretien de la DB
 *
 * @package freckle
 * @version 2.2
 */

	ini_set("arg_separator.output", "&amp;");
  	ini_set("url_rewriter.tags", "0");

	session_start();
	require_once("./config/config.php");

	require_once("./inc/definitions.inc.php");

	require_once("./inc/auth.inc.php");
	require_once("./inc/general.inc.php");
	require_once("./inc/management.inc.php");
	require_once("./inc/file_management.inc.php");
	require_once("./pear/Compat.php");
	require_once("./pear/Compat/Function/array_change_key_case.php");


	if( !isset($_SESSION['admin']) )
		$_SESSION['admin'] = false;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html	xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr">
<head>
	<title>FRECKLE</title>
	<link href="./styles/style-light-blue.css" type="text/css" media="screen" rel="stylesheet" title="light blue"/>
	<link href="./styles/style-noel.css" type="text/css" media="screen" rel="alternate stylesheet" title="Noël" />	
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

		<div class='content'>
<?php
/*
echo "<pre>";
print_r( get_fs_entries() );
print_r( get_db_entries() );
echo "</pre>";
*/

	clean_file_entries( "BOTH" );
?>
		</div>

	 <hr />

	 <div class="foot">
		<?php include("./inc/foot.inc.php"); ?>
	 </div>
	 
	</div>

</body>
</html>
