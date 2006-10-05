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
 * Fonction ressortant la liste des fichiers enregistr�s dans la base
 * de donn�es.
 */
function get_db_entries()
{
	global $db, $repos_abs;
	$SQL="SELECT id,url FROM fichiers";
	$file_table = $db->getAll( $SQL, DB_FETCHMODE_ASSOC );
	foreach( $file_table as $value )
	{
		if( !preg_match("/file:\/\//", $value["url"]) ) {
			$url = $repos_abs.$value['url'];
		} else {
			$url = str_replace( "file://", $repos_abs, $value['url'] );
		}
		$result[] = $url;
	}
	return $result;
}

/**
 * fonction ins�rant/d�truisant les entr�es de la base en fonction
 * de leur pr�sence dans le syst�me de fichiers
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
			$SQL[]="INSERT INTO fichiers (url,annee_prod,commentaire) VALUES ('file://$url',0,'');";
			echo "<li class='add'>$url a �t� ajout� dans la base.</li>\n";
		}
	}

	if( $action=="REMOVE" or $action=="BOTH" )
	{
		$result = array_diff( $db_entries, $fs_entries );
		
		foreach( $result as $key=>$value )
		{
			$url = str_replace( $repos_abs, "", $value );
			#echo "le fichier $value est dans la bdd mais pas dans le fs<br/>\n";
			$SQL[]="DELETE FROM fichiers WHERE url='".addslashes($url)."';";
			echo "<li class='suppr'>$url a �t� enlev� de la BDD</li>\n";
		}
	}
	echo "</ul>\n";

	return $SQL;
}

/**
 * Fonction qui assigne les fichiers non class� dans les cat�gories.
 */
function autoassign_file_entries() {
	global $db, $format;
	$cats = $db->getAll("SELECT * FROM categorie" );

	foreach( $cats as $key=>$value )
		$categorie[$value[0]] = $value[1];

	$categorie = array_flip( $categorie );

	/* r�cup�rer les ids et l'url des fichiers non class�s */

	preg_match( $format, substr($url,3), $cats );

	/* faire une boucle qui ins�re dans _toutes_ les cat�gories */
	$SQL[]="INSERT INFO reference (id_categorie,id_fichier,id_type) VALUES ('','','')";
	echo "$url a �t� class� dans la cat�gorie ".$categorie[$cats[1]]." et ".$categorie["I".substr($cats[2],0,1)]."</li>\n";
}

?>
