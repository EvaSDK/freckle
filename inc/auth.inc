<?php

function login_admin () {

  echo "<object>\n";
  echo "<form method=\"post\" action=\"basic.php\">\n <dl>\n";
  echo "<dd>User: <input name=\"username\" type=\"text\"     id=\"username\" /></dd>\n";
  echo "<dd>Pass: <input name=\"password\" type=\"password\" id=\"password\" /></dd>\n";
  echo "<dd>      <input type=\"submit\"   name=\"submit\"   value=\"Login\" /></dd>\n";
  echo "</dl>\n</form>\n</object>\n";

}

function logout_admin() {

	$var1 = SetCookie("freckle-esiee-admin-usr","");
	$var2 = SetCookie("freckle-esiee-admin-pwd","");
	
	if ( $var1 == TRUE && $var2 == TRUE ) {
		return TRUE; 
	} else {
		return FALSE;
	}
}


?>
