<?php

/**
 * Fonction qui sort la liste des fichiers dans le compte de freckle
 */
function get_fs_entries()
{
	global $repos_abs;
	system("find -type f $repos_abs", $file_table);
	return $file_table;
}


/**
 * Fonction ressortant la liste des fichiers enregistrés dans la base
 * de données.
 */
function get_db_entries()
{
	$SQL="SELECT * FROM fichiers";
	$link = db_connect();
	$result = db_query( $link, $SQL );
	$file_table = db_fetch_array( $result );

	db_close( $link );
	return $file_table;
}

/**
 * fonction insérant/détruisant les entrées de la base en fonction
 * de leur présence dans le système de fichiers
 */
function clean_file_entries( $action )
{
	$fs_entries = get_fs_entries();
	$db_entries = get_db_entries();

	$link = db_connect();


	if( $action=="ADD" or $action=="BOTH" )
	{
		$result = array_diff( $fs_entries, $db_entries );
		
		foreach( $result as $key=>$value )
		{
			echo "le fichier $value est dans le fs mais pas dans la bdd<br/>\n";
/*			$SQL="INSERT INTO fichiers (url,annee_prod,commentaire) VALUES ($value,0,'');";
			db_query( $link, $SQL );*/
		}
	}

	if( $action=="REMOVE" or $action=="BOTH" )
	{
		$result = array_diff( $db_entries, $fs_entries );
		
		foreach( $result as $key=>$value )
		{
			echo "le fichier $value est dans la bdd mais pas dans le fs<br/>\n";
/*			$SQL="DELETE FROM fichiers WHERE url='".addslashes($value)."';";
			db_query( $link, $SQL );*/
		}
	}

	db_close( $link );
}

?>
