<?php

$SQL = "pgSQL";

function q_num_rows( $o1 )
{
	switch ( global $SQL )
	{
		case "mySQL": $result = mysql_num_rows($o1); break;
		case "pgSQL": $result =    pg_num_rows($o2); break;
	}
	return $result;
}


?>
