<?php

/*
 * fichier contenant les bindings pour un backend mysql
 */


/* connection à la base de données */
function db_connect()
{
	$link = mysql_connect("localhost","reader","bidon");
	mysql_select_db("freckle",$link);
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
function db_num_rows( $ressource )
{
  return  mysql_num_rows( $ressource );
}

?>
