<?php

/*
 * fichier contenant les bindings pour un backend mysql
 */


/* connection à la base de données */
function db_connect()
{
	global $username, $password, $dbname;
	return pg_connect("dbname=$dbname user=$username password=$password");
}

/* effectue une requête SQL */
function db_query( $link, $query )
{
  return pg_query( $link, $query);
}

/* déconnecte de la base de données */
function db_close( $link )
{
  return  pg_close( $link );
}

/* renvoie le nombre de résultats */
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

/* génére la partie limitant le nombre de réstulats de la requête */
function sql_limit($offset)
{
	global $step;
	return " LIMIT $step OFFSET $offset;";
}

?>
