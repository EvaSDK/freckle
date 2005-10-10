<?php
	session_start();
	require("./inc/definitions.inc.php");
	
	require("./inc/auth.inc.php");
	require("./inc/general.inc.php");
	require("./inc/management.inc.php");


	require_once("./inc/administration.php");
	require_once("./config/config.php");

	$pass = isset($_POST['password']) ? $_POST['password'] : '';
	$user = isset($_POST['username']) ? $_POST['username'] : '';
 
	if ( isset($_GET['logout']) && $_GET['logout']==TRUE) {
		logout_admin();
	}

	if ( ($pass==$admin_pass and $user==$admin_user) or $_SESSION['admin']==TRUE) {
		$_SESSION['admin']=TRUE;
	} else {
		$_SESSION['admin']=FALSE;
	}
	
	echo "<?xml version='1.0' encoding='ISO-8859-1'?>\n";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html	xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr">
<head>
	<title>FRECKLE/Admin</title>
	<link href="./styles/style-light-blue.css" type="text/css" media="screen" rel="stylesheet" title="light blue"/>
	<link href="./styles/style.css" type="text/css" media="screen" rel="alternate stylesheet" title="test"/>
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
</head>

<body>

	<div class='wrapper'>
	
		<div class="head">
			<h1>Freckle/Admin</h1>
		</div>

		<div class="menu">
			<?php include("./inc/menu.inc.php"); ?>
		</div>

		<div class="content">
			<h2 class="titre-page">Administration</h2>

			<div class="box">
<?php
	if ($_SESSION['admin']==FALSE)
	{
		login_admin();
		
	} else {

		$what = isset($_GET['what']) ? $_GET['what'] : 'types';
		$current = isset($_GET['current']) ? $_GET['current'] : 0;

		echo "<h3>Vue des données</h3>\n";
		adminMenu( $what, $current );

		if( isset($_SESSION['message']) )
		{
			echo "<p id='message'>".$_SESSION['message']."</p>\n";
			unset($_SESSION['message']);
		}

		echo "<form action='process.php' method='POST'>\n";
		display_list_entries($what,$current);
		echo "</div>\n<div class='box'>\n<h3>Actions</h3>\n";
		get_form($what); 
		echo "</form>\n";
	}
?> 
			</div>
		</div>

		<hr />

		<div class="foot">
			<?php include("./inc/foot.inc.php"); ?>
		</div>

	</div>
	
</body>
</html>

	
