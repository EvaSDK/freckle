<?php

/*
 * fichier contenant les bindings pour un backend mysql
 */


/* connection à la base de données */
function db_connect()
{
	$link = mysql_connect($hostname, $username, $password);
	mysql_select_db($dbname, $link);
	return $link;
}
		
/* effectue une requête SQL */
function db_query( $link, $query )
{
  return mysql_query($query, $link);
}

/* déconnecte de la base de données */
function db_close( $link )
{
  return  mysql_close( $link );
}

/* renvoie le nombre de résultats */
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

/* génére la partie limitant le nombre de réstulats de la requête */
function sql_limit($offset)
{
  global $step;
  return " LIMIT $offset,$step;";
} 

?>
