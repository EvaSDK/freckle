<?php

/*
 * Sélectionne le backend SQL
 */

global $SQL;

switch( $SQL )
{
	case "mySQL": require("./inc/backend_mysql.php"); break;
	case "pgSQL": require("./inc/backend_pgsql.php"); break;
}

?>
