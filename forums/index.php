<? 
/******************************************************************************\
 * Copyright (C) 2002 B Squared (b^2) by Josh Sherman <josh@cleancode.org>    *
\******************************************************************************/

/* Prefix for the tables in the database */
define("TABLE_PREFIX", "forum_");

/* Define the generic error message */
define("ERROR", "<B>There was a error.</B><BR>\n");

/* Check the super globals and pull the values */

$destination    = GetVars("destination");
$message        = GetVars("message");
$password       = GetVars("password");
$title          = GetVars("title");
$username       = GetVars("username");
$mod_action     = GetVars("mod_action");
$admin_action   = GetVars("admin_action");
$logout         = GetVars("logout");
$pid            = GetVars("pid");
$HTTP_HOST      = GetVars("HTTP_HOST");
$REQUEST_METHOD = GetVars("REQUEST_METHOD");
$QUERY_STRING   = GetVars("QUERY_STRING");
$forum_id       = GetVars("forum_id");
$thread_id      = GetVars("thread_id");
$reply_id       = GetVars("reply_id");
$preview_scheme = GetVars("preview_scheme");
$user_name      = GetVars("user_name");

/* Assign null values to these variables */
$logged_in       = 0;
$login           = "";
$user_id         = "";
$is_moderator    = 0;
$is_admin        = 0;
$hack_attempt    = "";
$mod_feedback    = "";
$admin_feedback  = "";
$show_thread     = "";
$show_forum      = "";

/* Parse the variables and trim them to a specified length */
CheckVars(&$pid, 16);

/* Attempt to log the user in, if requested */
AttemptLogin(&$pid, $REQUEST_METHOD , $HTTP_HOST , &$logged_in, &$login, $username, &$password, &$is_moderator, &$is_admin );
/* Verify their identity, if they are logged in */
VerifyLogin( &$logged_in, &$user_id, &$is_moderator, &$is_admin , $user_name , $HTTP_HOST );
/* Attempt to perform a moderator action, if requested */
ModAction( &$is_moderator, &$mod_action, $forum_id, $thread_id, $reply_id, $user_id, &$hack_attempt, &$mod_feedback, &$show_thread, &$show_forum );
/* Attempt to perform an admin action, if requested */
AdminAction( &$is_admin, &$admin_action, $forum_id, $thread_id, $reply_id, $user_id, &$hack_attempt, &$mod_feedback, &$show_thread, &$show_forum );
/* Attempt to redefine the colors with the defaults (success = there is nothing in the schemes table) */
define("TABLE_COLOR_1", "#EEEEEE");
define("TABLE_COLOR_2", "#CCCCCC");

/* Log the user out if requested */
if ($logout == "now") {
    /* Blow out the cookie */
    SetCookie("user_name", "", time() - 3600, ''); //, $HTTP_HOST);
    SetCookie("user_pass", "", time() - 3600, ''); //, $HTTP_HOST);
    /* Blow out the variables */
    $logged_in    = 0;
    $is_admin     = 0;
    $is_moderator = 0;
}

/* If the destination is specified, then assign it to the $pid */
if ($destination != "") {
  $pid = $destination;
 // echo $pid . "eeeeeeeee";
 }

/* If there's no specified $pid, then default to 'view_forums' */
if  ($logged_in == 0){
	if ($pid == ""){
	  $pid = "login";
	}
	else {
		if ($pid != "login" && 	$pid != "faq" && $pid != "register"  ){
			$destination=$pid;
			$pid=login;
		}
	}
}
else {
	if ($pid=="")
	$pid="view_forums";
}

/* If $show_thread isn't 0, then set the $pid and $thread_id */
if ($show_thread != 0) {
    $pid       = "view_replies";
    $thread_id = $show_thread;
}

/* Same deal as before, except it happens if $show_forum isn't 0 */
if ($show_forum != 0) {
    $pid       = "view_threads";
    $thread_id = $show_forum;
}

/* Conver the $pid to lower case, and pull that filename */
$page_file = "./forums/content/" . strtolower($pid) . ".php";

/* Display the page header, including CSS stuff */
echo "<TABLE align=\"center\" valign=\"top\" cellpadding=\"8\" cellspacing=\"0\" width=\"100%\"><TR><TD>"
	. "<TABLE width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border ><TR><TD bgcolor=\"#EEEEEE\" valign=\"middle\">"
	. "<TABLE cellpadding=\"3\" cellspacing=\"0\" border=\"0\" width=\"100%\"><TR><TD align=\"left\">"
	. "<A href=\"?location=forum\">Forum</A> | \n";

/* If not logged in, give the register link */
if ($logged_in == 0)
  {
    $show_profile  = "";
    $show_register = "<A href=\"?location=forum&pid=register\">Inscription</A> | ";
  }
/* If logged in, then give a link to their profile */
else
  {
    $show_profile  = "<A href=\"?location=forum&pid=edit_profile\">Profil</A> | ";
    $show_register = "";
  }

/* If the user is an admin, and logged in, display the admin links */
if ( $is_admin == 1 && $logged_in == 1 )
  $show_admin = "<A href=\"?location=forum&pid=forum_admin\">Forum Admin</A> | <A href=\"?location=forum&pid=user_admin\">User Admin</A> | <A href=\"?location=forum&pid=new_user\">New User</A> | ";
else
  $show_admin = "";

/* Display the rest of the menu, and continue to the body of the page */
echo "                      &nbsp;$show_profile$show_register$show_admin <A href=\"?location=forum&pid=faq\">Questions &amp; R&eacute;ponses</A><BR>\n"
   . "                    </TD>\n"
   . "                     <TD align=\"right\">\n";

/* Check if the user is logged in */
if ($logged_in == 0) {
    /* If not, then display the 'Log In' option */
    $login_status = "Not logged in (<A href=\"?location=forum&pid=login\">Log In</A>)";
  }
else {
    /* If they are logged in, pull the username form the cookie */
    if ($user_name != "")
      $username = $user_name;

    /* Tell them they are logged in, and give them the option to log out */
    $login_status = "Logged in as <B>$username</B> (<A href=\"?location=forum&pid=login&logout=now\">Log Out</A>)";
  }

/* Display the login status, and start on the menu */
echo "                            $login_status\n"
   . "                          </TD>\n"
   . "                  </TR>\n"
   . "                </TABLE>\n"
   . "              </TD\n"
   . "            </TR>\n"
   . "          </TABLE>\n"
   . "        </TD>\n"
   . "      </TR>\n"
   . "      <TR>\n"
   . "        <TD>";

/* If there's a malformed request to the moderator tools, then error out */
if ($hack_attempt == "outside")
  echo "            <CENTER>Malformed request detected!</CENTER><BR>\n";
else if ($hack_attempt == "inside")
  echo "            <CENTER>Sorry, but your moderator privileges don't extend to this particular forum.</CENTER></BR>\n";

/* If a moderator tool have been executed, give feedback on it, positive or negative */
if ($mod_feedback != "")
  echo "            <CENTER>$mod_feedback</CENTER><BR>\n";

/* Load the content for the page that was requested */
require($page_file);

echo "       </TD> </TABLE>\n";
?>
