<?php

/*
 * fichier contenant les bindings pour un backend mysql
 */


/* connection � la base de donn�es */
function db_connect()
{
	$link = mysql_connect($hostname, $username, $password);
	mysql_select_db($dbname, $link);
	return $link;
}
		
/* effectue une requ�te SQL */
function db_query( $link, $query )
{
  return mysql_query($query, $link);
}

/* d�connecte de la base de donn�es */
function db_close( $link )
{
  return  mysql_close( $link );
}

/* renvoie le nombre de r�sultats */
function db_num_rows( $resource )
{
  return  mysql_num_rows( $resource );
}

function db_fetch_object( $resource )
{
	return mysql_fetch_object( $resource );
}

function db_fetch_array( $resource )
{
	return mysql_fetch_array( $resource, MYSQL_ASSOC );
}

/* g�n�re la partie limitant le nombre de r�stulats de la requ�te */
function sql_limit($offset)
{
  global $step;
  return " LIMIT $offset,$step;";
} 

?>
