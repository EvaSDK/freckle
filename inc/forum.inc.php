<?
function getCatCode() {
	$res = mysql_query( "select count(*) as cc from cat");
	$lcat[0]=0;
	$row = mysql_fetch_object($res);
	if ($row)
		$lcat[0]=$row->cc;
	
	$res = mysql_query( "select * from cat");
	while ($row = mysql_fetch_object($res) ) {
		$lcat[$row->id]=$row->code;
	}
	return $lcat;	
}

function getCat($cat, $lcat) {
	$strRes=NULL;
	for ( $i=1 ; $i<=$lcat[0] ; $i++ ) {
		$p=pow(2,$i);
		if ( ($p & $cat) == $p ) {
			if ($strRes==NULL) {
//				$strRes="<a href=\"documents.php?see=4&categorie=$p\">$lcat[$i]</a>";
				$strRes="$lcat[$i]";
			}
			else {
//				$strRes .=  " - <a href=\"index.php?location=file&focus=yes&focus1=$p\">$lcat[$i]</a>";
				$strRes .=  " - $lcat[$i]";

			}
		}
	}
	return $strRes;
}

function getFrDate ( $ts ) {
	// date in french, you may modify this section for your language
	$day = array("Dimanche", "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi");
	// month in french, you may modify this section for your language
	$month = array("", "Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Decembre");
	$tr[1] =  $day[date("w",$ts)] . " " . date("j" , $ts) . " " . $month[date ( "n" , $ts)] . " ". date("Y", $ts) ;
	$tr[2] = " &agrave; " . date ("G" , $ts) . "h" . date( "i",$ts);
	return $tr;
}

function toSQL($str){
	return mysql_escape_string($str);
}

function getUId() {
	mysql_query( "UPDATE gc SET id=id+1");
	$res = mysql_query( "SELECT id FROM gc");
	$row = mysql_fetch_object($res);
	return $row->id;
}

function faireMenu ($loc) {
	$liste = array (1 => 'doc' , 'plan' , 'file' , 'actu' , 'site' ,'news');
	for ($i=1 ; $i <= 6 ; $i++) {
		if ($loc==$liste[$i]) {
			$str = $str . "<TD height=15 nowrap class=\"bleufonce\">" . getTitle($loc) . "</td>";
		}
		else {
			$str= $str . "<TD height=15 nowrap class=\"grisclair\"><A class=bleuclair href=\"index.php?location=$liste[$i]\">"
				. getTitle($liste[$i]) . "</A></TD>";
		}
	}
	return $str;		
}

function getTitle (&$loc) {
	switch ($loc) {
	case "actu" :
		return "ACTUALITE";
	break;
	case "plan" :
		return "EMPLOI DU TEMPS";
	break;
	case "site" :
		return "AUTRES SITES";
	break;
	case "bal" :
		return "PORTE-DOCUMENTS";
	break;
	case "forum" :
		return "FORUM";
	break;
	case "file" :
		return "DOCUMENTS ESIEE";
	break;
	case "news" :
		return "NEWS";
	break;
	default :
		$loc="doc";
		return "ACCUEIL";
	}	
}

function getIcon ( $file ) {
	$pt = strrpos($file, ".");
	if ($pt != FALSE){
		$file_ext = substr($file, $pt + 1, strlen($file) - $pt - 1);
		switch ($file_ext){
		case "gif": $image = "_icon/i_gif.gif";
		break;
		case "htm": $image = "_icon/i_htm.gif";
		break;
		case "html": $image = "_icon/i_htm.gif";
		break; 
		case "ppt": $image = "_icon/i_ppt.gif";
		break; 
		case "xls": $image = "_icon/i_xls.gif";
		break; 
		case "doc": $image = "_icon/i_doc.gif";
		break; 
		case "pdf": $image = "_icon/i_pdf.gif";
		break; 
		case "bmp": $image = "_icon/i_img.gif";
		break;
		case "rar": $image = "_icon/i_rar.gif";
		break;
		case "ace": $image = "_icon/i_ace.gif";
		break;
		case "jpg": $image = "_icon/i_jpg.gif";
		break;
		case "mp3": $image = "_icon/i_mp3.gif";
		break;
		case "exe": $image = "_icon/i_pgm.gif";
		break;
		case "txt": $image = "_icon/i_txt.gif";
		break;
		case "wav": $image = "_icon/i_wav.gif";
		break;
		case "zip": $image = "_icon/i_zip.gif";
		break;
		default: $image = "_icon/i_other.gif";
		}
		return($image);
	}
}

function CheckVars($var, $size) {
  $length = strlen($var);

  if ($length > $size)
  	for ( ; $length >= $size; $length--)
		$var[$length] = "";
}

function GetVars($varname) {

  if ($_SERVER[$varname] != "") {
   $retval = $_SERVER[$varname];
	}
  elseif ($_COOKIE[$varname] != ""){
    $retval = $_COOKIE[$varname];
	}
  elseif ($_POST[$varname] != ""){
    $retval = $_POST[$varname];
	}
  elseif ($_GET[$varname] != "" ){
    $retval = $_GET[$varname];
	}
  elseif ($_ENV[$varname] != ""){
    $retval = $_ENV[$varname];
	}
  else
    $retval = NULL;
  return trim($retval) ;
}

function  ExeSQL($SQL) {
  $results = mysql_query($SQL);
  if (!$results) {
      exit("There was an error.<BR><BR><B>MySQL Error:</B> <I>" . mysql_error() . "</I><br>$SQL<br>\n");
    }
  return($results);
}

function AttemptLogin( $pid, $reqMeth, $the_host , &$logged_in, &$login, $username, $password, &$is_moderator, &$is_admin ) {
  /* Attempt to log the user in if they request it */
  if ( $reqMeth == "POST" && $pid == "login" && $username != "" && $password != "" ) {
      /* Check to see if the provided username exists in the database */
      $SQL     = "SELECT COUNT(user_id) AS user_exists FROM " . TABLE_PREFIX . "users WHERE user_name='$username'";
      $results = ExeSQL($SQL);

      /* Grab the data, and analyze it */
      while ($row = mysql_fetch_array($results))
        $user_exists = $row["user_exists"];

      /* User provided correct username */
      if ($user_exists == 1) {
          /* Check to see if the provided username exists in the database */
          $SQL     = "SELECT user_pass FROM " . TABLE_PREFIX . "users WHERE user_name='$username'";
          $results = ExeSQL($SQL);

          /* Grab the data, and analyze it */
          while ($row = mysql_fetch_array($results))
            $existing_pass = $row["user_pass"];
	          $password = md5($password);
        	  if ($password == $existing_pass) {
	        	  /* Set the cookies */
	              SetCookie("user_name", $username, time() + 3600, ''); //, $the_host);
    	          SetCookie("user_pass", $password, time() + 3600, ''); //, $the_host);
	
        	      $logged_in = 1;
				  $pid="view_forums";
            	}
	          else {
	              /* Clear the cookies */
    	          SetCookie("user_name", "", time() - 3600, ''); //, $the_host);
        	      SetCookie("user_pass", "", time() - 3600, ''); //, $the_host);
	
        	      $login = "failed";
            	  $logged_in = 0;
			}
          if ($logged_in == 1) {
              /* Pull the user ID for the user */
			  
              $SQL = "SELECT user_id FROM " . TABLE_PREFIX . "users WHERE user_name='$username'";
              $results = ExeSQL($SQL);

              /* Grab the data */
              while ($row = mysql_fetch_array($results))
                $user_id = $row["user_id"];

              /* Check to see if the user is a moderator */
              $SQL = "SELECT COUNT(*) AS is_moderator FROM " . TABLE_PREFIX . "moderators WHERE user_id='$user_id'";
              $results = ExeSQL($SQL);

              /* Grab the data */
              while ($row = mysql_fetch_array($results))
                $is_moderator = $row["is_moderator"];

              /* Check to see if the user is an administrator */
              $SQL     = "SELECT COUNT(*) AS is_admin FROM " . TABLE_PREFIX . "administrators WHERE user_id='$user_id'";
              $results = ExeSQL($SQL);

              /* Grab the data */
              while ($row = mysql_fetch_array($results))
                $is_admin = $row["is_admin"];

              /* If user is admin, grant them moderator privileges */
              if ($is_admin != 0)
                $is_moderator = $is_admin;
            }
        }
      /* User provided incorrect username */
      else {
          /* Clear the cookies */
          SetCookie("user_name", "", time() - 3600, ''); //, $the_host);
          SetCookie("user_pass", "", time() - 3600, ''); //, $the_host);
 
          $login = "failed";
          $logged_in = 0;
        } 
    }
}

function VerifyLogin( $logged_in, $user_id, $is_moderator, $is_admin , $user_name , $the_host) {
  $user_pass = GetVars("user_pass");
  /* Verify the user's integrity */
  if ( $user_name != "" && $user_pass != "" )
    {
      /* Check to see if the provided username exists in the database */
      $SQL     = "SELECT COUNT(*) AS user_verification FROM " . TABLE_PREFIX . "users WHERE user_name='" . $user_name . "'";
      $results = ExeSQL($SQL);

      /* Grab the data, and analyze it */
      while ($row = mysql_fetch_array($results))
        $user_verification = $row["user_verification"];

      if ($user_verification == 1)
        {
          /* Pull the password for the username we just determine existed */
          $SQL     = "SELECT user_name, user_pass FROM " . TABLE_PREFIX . "users WHERE user_name='" . $user_name . "'";
          $results = ExeSQL($SQL);

          /* Grab the data, and analyze it */
          while ($row = mysql_fetch_array($results))
            {
              $existing_user = $row["user_name"];
              $existing_pass = $row["user_pass"];
            }

          $cookie_pass = urldecode($user_pass);

          if ($existing_pass == $cookie_pass) 
            {
              /* Set the cookies */
              SetCookie("user_name", $existing_user, time() + 3600, '', $the_host);
              SetCookie("user_pass", $existing_pass, time() + 3600, '', $the_host);

              $pid = "view_forums";
              $logged_in = 1;
            }
          else {
              /* Clear the cookies */
              SetCookie("user_name", "", time() - 3600, '', $the_host);
              SetCookie("user_pass", "", time() - 3600, '', $the_host);
              $pid = "login";
              $login = "failed";
              $logged_in = 0;
			  			  echo "fail330";
            }
        }
      else {
          SetCookie("user_name", "", time() - 3600, '', $the_host);
          SetCookie("user_pass", "", time() - 3600, '', $the_host);
          $logged_in = 0;
        }

      $is_moderator = $logged_in;
      $is_admin     = $logged_in;

      if ($logged_in == 1)
        {
          /* Pull the user ID for the user */
          $SQL     = "SELECT user_id FROM " . TABLE_PREFIX . "users WHERE user_name='" . $user_name . "';";
          $results = ExeSQL($SQL);
 
          /* Grab the data */
          while ($row = mysql_fetch_array($results))
            $user_id = $row["user_id"];

          /* Check to see if the user is a moderator */
          $SQL     = "SELECT COUNT(*) AS is_moderator FROM " . TABLE_PREFIX . "moderators WHERE user_id='$user_id';";
          $results = ExeSQL($SQL);

          /* Grab the data */
          while ($row = mysql_fetch_array($results))
            $is_moderator = $row["is_moderator"];

          /* Check to see if the user is an administrator */
          $SQL     = "SELECT COUNT(*) AS is_admin FROM " . TABLE_PREFIX . "administrators WHERE user_id='$user_id';";
          $results = ExeSQL($SQL);

          /* Grab the data */
          while ($row = mysql_fetch_array($results))
            $is_admin = $row["is_admin"];

          /* If user is admin, grant them moderator privileges */
          if ($is_admin != 0)
            $is_moderator = $is_admin;
        }
      else
        {
          $is_moderator = 0;
          $is_admin     = 0;
        }
    }
}

function ModAction ( $is_moderator, $mod_action, $forum_id, $thread_id, $reply_id, $user_id, $hack_attempt, $mod_feedback, $show_thread, $show_forum )  {
  if ( $is_moderator == 0 && $mod_action != "" )
    {
      $hack_attempt = "outside";
      return;
    }

  if ($mod_action != "")
    {
      /* Pull the list of forums this user is a moderator for */
      $SQL     = "SELECT * FROM " . TABLE_PREFIX . "moderators WHERE user_id='$user_id';";
      $results = ExeSQL($SQL);

      /* Grab the data and load it in an array */
      while ($row = mysql_fetch_array($results))
        $moderated_forums[] = $row["forum_id"];  

      if (!in_array($forum_id, $moderated_forums))
        {
          $hack_attempt = "inside";
          return;
        }
    }

  switch ($mod_action)
    {
      case "Delete Reply":
        /* Delete the specified reply */
        $SQL     = "DELETE FROM " . TABLE_PREFIX . "replies WHERE reply_id='$reply_id';";
        $results = ExeSQL($SQL);

        $mod_feedback = "The reply has been removed from the board.";
        $show_thread  = $thread_id;
        break;

      case "Delete Entire Thread":
        /* Delete the specified thread */
        $SQL     = "DELETE FROM " . TABLE_PREFIX . "threads WHERE thread_id='$thread_id';";
        $results = ExeSQL($SQL);

        /* Delete the replies to the specified thread */
        $SQL     = "DELETE FROM " . TABLE_PREFIX . "replies WHERE thread_id='$thread_id';";
        $results = ExeSQL($SQL);

        $mod_feedback = "The thread has been removed from the board.";
        $show_forum   = $forum_id;
    }
}

function AdminAction ( $is_admin, $admin_action, $forum_id, $thread_id, $reply_id, $user_id, $hack_attempt, $admin_feedback, $show_thread, $show_forum ) {
  if ( $is_admin == 0 && $admin_action != "" ) {
      $hack_attempt = "outside";
      return;
    }

  switch ($admin_action) {
      case "Delete Reply":
        /* Delete the specified reply */
        $SQL     = "DELETE FROM " . TABLE_PREFIX . "replies WHERE reply_id='$reply_id';";
        $results = ExeSQL($SQL);

        $mod_feedback = "The reply has been removed from the board.";
        $show_thread  = $thread_id;
		break;
      case "Delete Entire Thread":
        /* Delete the specified thread */
        $SQL     = "DELETE FROM " . TABLE_PREFIX . "threads WHERE thread_id='$thread_id';";
        $results = ExeSQL($SQL);

        /* Delete the replies to the specified thread */
        $SQL     = "DELETE FROM " . TABLE_PREFIX . "replies WHERE thread_id='$thread_id';";
        $results = ExeSQL($SQL);

        $mod_feedback = "The thread has been removed from the board.";
        $show_forum   =  $forum_id;
    }
}
?>
