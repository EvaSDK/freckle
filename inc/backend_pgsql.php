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
function db_num_rows( $ressource )
{
  return  pg_num_rows( $ressource );
}

?>
