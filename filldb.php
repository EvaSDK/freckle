<?php

  session_start();
  require("./inc/definitions.inc.php");
  require("./inc/backend_sql.php");

  require("./inc/auth.inc.php");
  require("./inc/general.inc.php");
  include("./inc/management.inc.php");

	$table = $_GET['table'];

	$link = db_connect();

	db_query($link,"TRUNCATE TABLE $table");

	$handle = fopen("$table.sql", "r");
	while (!feof($handle)) {
	   $buffer = fgets($handle, 4096);
	   echo "<br />".substr($buffer,0,-1);

		 db_query($link,substr($buffer,0,-1));
	}
	fclose($handle);

	db_close( $link );

?>
