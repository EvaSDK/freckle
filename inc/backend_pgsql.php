<?php

/*
 * fichier contenant les bindings pour un backend mysql
 */


/* connection � la base de donn�es */
function db_connect()
{
	global $username, $password, $dbname;
	return pg_connect("dbname=$dbname user=$username password=$password");
}

/* effectue une requ�te SQL */
function db_query( $link, $query )
{
  return pg_query( $link, $query);
}

/* d�connecte de la base de donn�es */
function db_close( $link )
{
  return  pg_close( $link );
}

/* renvoie le nombre de r�sultats */
function db_num_rows( $resource )
{
  return  pg_num_rows( $resource );
}

function db_fetch_object( $resource )
{
  return pg_fetch_object( $resource );
}

function db_fetch_array( $resource )
{
  return pg_fetch_assoc( $resource );
}

/* g�n�re la partie limitant le nombre de r�stulats de la requ�te */
function sql_limit($offset)
{
	global $step;
	return " LIMIT $step OFFSET $offset;";
}

?>
