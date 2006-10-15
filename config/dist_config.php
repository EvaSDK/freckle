<?php
/**
 * fichier de configuration de freckle
 * @package freckle
 */

/** augmentation de la rigueur de php */
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);

/**#@+
 * informations sur la base de données
 */
require_once 'DB.php';

	$dsn = "pgsql://user:password@machine/base";
	
	$options = array(
		'debug'       => 2,
		'portability' => DB_PORTABILITY_ALL,
	);

	$db =& DB::connect($dsn, $options);
	if (PEAR::isError($db)) {
		die($db->getMessage());
	}
	
/**#@-*/


/**
 * Repertoire temporaire pour le fonctionnement de chronix.
 */
$temp_rep = "/tmp";

/** Nom du fichier de log. */
$fichier_log = "{$temp_rep}/animix.log";

/** Activation du mode DEBUG 
 * utile uniquement à des fins de développement */
$_DEBUG = TRUE;

