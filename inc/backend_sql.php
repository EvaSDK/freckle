<?php

$SQL = "pgSQL";

function db_query( $link, $query )
{
	global $SQL;
	switch ( $SQL )
	{
		case "mySQL": $result = mysql_query($query, $link); break;
		case "pgSQL": $result =    pg_query($link, $query); break;
	}
	return $result;
}

function db_close( $link )
{
	global $SQL;
	switch ( $SQL )
	{
		case "mySQL": $result = mysql_close($link); break;
		case "pgSQL": $result =    pg_close($link); break;
	}
	return $result;
}

function q_num_rows( $o1 )
{
	global $SQL;
	switch ( $SQL )
	{
		case "mySQL": $result = mysql_num_rows($o1); break;
		case "pgSQL": $result =    pg_num_rows($o1); break;
	}
	return $result;
}


?>
