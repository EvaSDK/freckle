<?php
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


	echo "<pre>";
	print_r( get_fs_entries() );
	#print_r( get_db_entries() );
	echo "</pre>";
?>
