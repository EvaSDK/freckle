<?php

/* Fonction de connection à la base pgsql */
function dbconn() {
  $link = pg_connect("dbname=freckle user=reader password=bidon");
  return $link;
}


/* Formulaire d'autentification */
function login_admin () {
  echo "<form method='post' action='management.php'>\n <dl>\n";
  echo "  <dd>User: <input name='username' type='text'     id='username' /></dd>\n";
  echo "  <dd>Pass: <input name='password' type='password' id='password' /></dd>\n";
  echo "  <dd>      <input type='submit'   name='submit'   value='Login' /></dd>\n";
  echo " </dl>\n</form>\n";
}

/* beurk -> on ecrase les donnees du cookie */
function logout_admin() {
	session_start();
	$_SESSION = array();
	session_destroy();
}
?>
