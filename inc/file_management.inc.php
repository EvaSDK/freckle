<?php

/**
 * Fonction qui sort la liste des fichiers dans le compte de freckle
 */
function get_fs_entries()
{
	global $repos_abs;
	exec("find $repos_abs -type f", &$file_table);
	/*foreach( $file_table as $value )
	{
		$url = str_replace( $repos_abs, "file://", $value );
		$result[] = array( "id" => -1, "url" => $url );
	}*/
	$result = $file_table;
	return $result;
}


/**
 * Fonction ressortant la liste des fichiers enregistrés dans la base
 * de données.
 */
function get_db_entries()
{
	global $db, $repos_abs;
	$SQL="SELECT id,url FROM fichiers";
	$file_table = $db->getAll( $SQL, DB_FETCHMODE_ASSOC );
	foreach( $file_table as $value )
	{
		if( strpos( "file://", $value['url']) ==0 )
			$value['url'] =  "file://". $value['url'];

		$url = str_replace( "file://", $repos_abs, $value['url'] );
		$result[] = $url;
	}
	return $result;
}

/**
 * fonction insérant/détruisant les entrées de la base en fonction
 * de leur présence dans le système de fichiers
 */
function clean_file_entries( $action )
{
	global $repos_abs;
	$fs_entries = get_fs_entries();
	$db_entries = get_db_entries();

	echo "<ul>";
	if( $action=="ADD" or $action=="BOTH" )
	{
		$result = array_diff( $fs_entries, $db_entries );
		
		foreach( $result as $key=>$value )
		{
			$url = str_replace( $repos_abs, "", $value );
			#echo "le fichier $value est dans le fs mais pas dans la bdd<br/>\n";
			$SQL[]="INSERT INTO fichiers (url,annee_prod,commentaire) VALUES ($value,0,'');";
			echo "<li class='add'>$url a été ajouté la BDD</li>\n";
		}
	}

	if( $action=="REMOVE" or $action=="BOTH" )
	{
		$result = array_diff( $db_entries, $fs_entries );
		
		foreach( $result as $key=>$value )
		{
			$url = str_replace( $repos_abs, "", $value );
			#echo "le fichier $value est dans la bdd mais pas dans le fs<br/>\n";
			$SQL[]="DELETE FROM fichiers WHERE url='".addslashes($value)."';";
			echo "<li class='suppr'>$url a été enlevé de la BDD</li>\n";
		}
	}
	echo "</ul>\n";
}

?>
