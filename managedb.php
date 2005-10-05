<?php

	session_start();
	require("./inc/definitions.inc.php");
	require("./inc/backend_sql.php");
	require("./inc/file_management.inc.php");


	echo "<pre>";
	print_r( get_fs_entries() );
	print_r( get_db_entries() );
	echo "</pre>";
?>
