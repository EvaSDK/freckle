<?

/******************************************************************************\
 * Copyright (C) 2002 B Squared (b^2) by Josh Sherman <josh@cleancode.org>    *
\******************************************************************************/

/* Make sure no one is calling this file directly */
$file_name = "edit_profile.php";

/* Get the negative length of $file_name */
$file_name_length = -(strlen($file_name));

/* Check if the values match, if so, redirect */
if (substr($SCRIPT_NAME, $file_name_length) == $file_name)
  header("Location: ./index.php");

// Grab the veriables submitted by the form 
$city		= GetVars("city");
$occupation	= GetVars("occupation");
$homepage	= GetVars("homepage");
$interests	= GetVars("interests");
$signature	= GetVars("signature");
$include_sig = GetVars("include_sig");
$action		= GetVars("action");
$step		= GetVars("step");

/* Parse any user input */
CheckVars(&$step, 1);
CheckVars(&$username, 64);
CheckVars(&$password, 64);
CheckVars(&$confirm_password, 64);
CheckVars(&$city, 128);
CheckVars(&$occupation, 64);
CheckVars(&$homepage, 128);
CheckVars(&$interests, 255);
CheckVars(&$signature, 255);
CheckVars(&$include_sig, 1);

/* Check that the user isn't trying to mess with the $step variable */
if ( $step == "" || ( $step != 1 && $step != 2 && $step != 3 && $step != 4 ) )
  $step = 1;

/* Make sure the user isn't feeding information via the query string, thwart all attempts!! */
if ( ( ( $username == "" || $email == "" ) && ( $step == 3 || $step == 4 ) ) ||
     ( ( ( $step == 1 && ( $QUERY_STRING != "location=forum&pid=edit_profile" && $QUERY_STRING != "location=forum&pid=login" ) ) ) ||
       ( $step == 2 && $QUERY_STRING != "location=forum&pid=edit_profile" ) ||
       ( $step == 3 && $QUERY_STRING != "location=forum&pid=edit_profile&step=3" ) ||
       ( $step == 4 && $QUERY_STRING != "location=forum&pid=edit_profile" ) ) ||
       ( ( $step != 1 && $step != 2 ) && ( strlen(trim($username)) == 0 || strlen(trim($email)) == 0 ) ) )

  {
    echo "            <CENTER>Malformed request detected!</CENTER><BR>\n";
    $step = 1;
  }

/* On step 3 we have two choices, determine which step to go to based on the button the user clicks on */
if ($action == "Edit Profile")
  $step = 2;
else if ($action == "Submit Profile")
  $step = 4;

/* Parse some of the variables to ensure accurate values */
if ( $step == 2 && $homepage == "" )
  $homepage = "http://";

/* Strip out html and slashes on step 2 */
if ($step == 2)
  {
    $username   = stripslashes(strip_tags($username));
    $password   = stripslashes(strip_tags($password));
    $city   = stripslashes(strip_tags($city));
    $occupation = stripslashes(strip_tags($occupation));
    $homepage   = stripslashes(strip_tags($homepage));
    $interests  = stripslashes(strip_tags($interests));
    $signature  = stripslashes(strip_tags($signature));
  }

/* Step 3 too ... */
if ($step == 3)
  {
    $username   = stripslashes(strip_tags($username));
    $password   = stripslashes(strip_tags($password));
    $city   = stripslashes(strip_tags($city));
    $occupation = stripslashes(htmlspecialchars($occupation));
    $homepage   = stripslashes(strip_tags($homepage));
    $interests  = stripslashes(strip_tags($interests));

    /* Allowing CRs creates issues, this code should resolve them :) */
    $signature  = stripslashes(htmlspecialchars($signature));
    $signature  = nl2br($signature);
    $signature  = str_replace("<br />", "<BR>", $signature);
  }

/* On step 4, clean up the signature */
if ($step == 4)
  {
    $occupation = htmlspecialchars($occupation);

    $signature = htmlspecialchars($signature);
    $signature = str_replace("&lt;BR&gt;", "<BR>", $signature);
  }

/* Display the current step */
switch ($step)
  {
    /* Display the current profile */
    default:
    case 1:
      /* Pull the number of accounts with the same username */
      $SQL = "SELECT * FROM " . TABLE_PREFIX . "users WHERE user_name='$username';";
      $results = ExeSQL($SQL);

      /* Grab the data and assign it to variables */
      while ($row = mysql_fetch_array($results))
        {
          $username    = $row["user_name"];
          $password    = "";
          $email       = $row["user_email"];
          $city    = $row["user_city"];
          $occupation  = $row["user_occupation"];
          $homepage    = $row["user_homepage"];
          $interests   = $row["user_interests"];
          $signature   = $row["user_signature"];
          $include_sig = $row["user_usesig"];
        }

      /* Display the HTML for the beginning of the form and table */
      echo "            <FORM action=\"?location=forum&pid=edit_profile\" method=\"POST\" name=\"profile\">\n"
         . "              <TABLE cellpadding=\"5\" cellspacing=\"0\" width=\"100%\" border>\n"
         . "                <TR>\n"
         . "                  <TD colspan=\"2\">Profil de $username</TD>\n"
         . "                </TR>\n";

      /* Assign the active color to the inactive value */
      $the_color = TABLE_COLOR_2;

      /* Preview the mandatory fields ... */
      PreviewSection ( $password, "Mot de passe", &$the_color );
      PreviewSection ( $email, "Email", &$the_color );

      /* ... and the optional ones */
      if ( $city != "" )
        PreviewSection( $city, "Ville", &$the_color );

      if ( $occupation != "" )
        PreviewSection( $occupation, "Occupation", &$the_color );

      if ( $homepage != "" && $homepage != "http://" )
        PreviewSection( $homepage, "Homepage", &$the_color );

      if ( $interests != "" )
        PreviewSection ( $interests, "Interests", &$the_color );

      /* The signature is a different kind of field, so we handle it differently */
      if ( $signature != "" )
        {
          /* Change to the other color */
          if ($the_color == TABLE_COLOR_1)
            $the_color = TABLE_COLOR_2;
          else
            $the_color = TABLE_COLOR_1;

          /* Determine if the user is including the signature or not */
          if ($include_sig == 1)
            $show_include = "You have chosen to include this signature on new posts.";
          else
            $show_include = "You have chosen to not include this signature on new posts.";

          /* Display the signature section of the form */
          echo "                <TR bgcolor=\"$the_color\">\n"
             . "                  <TD width=\"25%\" valign=\"top\"><B>Signature :</B></TD>\n"
             . "                  <TD width=\"50%\">\n"
             . "                    $signature<BR><BR>\n"
             . "                    <I>$show_include</I>\n"
             . "                    <INPUT type=\"hidden\" name=\"signature\" value=\"$signature\">\n"
             . "                    <INPUT type=\"hidden\" name=\"include_sig\" value=\"$include_sig\">\n"
             . "                  </TD>\n"
             . "                </TR>\n";
        }

      /* Finish off the HTML */     
      echo "              </TABLE>\n"
         . "              <INPUT type=\"hidden\" name=\"old_email\" value=\"$email\">\n"
         . "              <CENTER><BR><INPUT type=\"submit\" value=\"Edit Profile\" name=\"action\"></CENTER>\n"
         . "            </FORM>\n";
      break;

    /* Display the form for the user to fill out */
    case 2:
      ShowProfileForm( $username, $password, $confirm_password, $email, $city, $occupation, $homepage, $interests, $signature, $include_sig );
      break;

    /* Display the info the user supplied and prompt them to continue or edit */
    case 3:
      /* Display the HTML */
      echo "            <FORM action=\"?location=forum&pid=edit_profile\" method=\"POST\" name=\"profile\">\n"
         . "              <TABLE cellpadding=\"5\" cellspacing=\"0\" width=\"100%\" border>\n"
         . "                <TR>\n"
         . "                  <TD colspan=\"2\">Previsualisation du profile</TD>\n"
         . "                </TR>\n";

      /* Assign second color as the active one */      
      $the_color = TABLE_COLOR_2;

      /* Preview the mandatory sections */
      PreviewSection ( $username, "Username", &$the_color );
      PreviewSection ( $password, "Mot de passa", &$the_color ); 
      PreviewSection ( $email, "Email", &$the_color );
     
      /* Along with the optional sections */
      if ( $city != "" )
        PreviewSection( $city, "Ville", &$the_color );

      if ( $occupation != "" )
        PreviewSection( $occupation, "Occupation", &$the_color );

      if ( $homepage != "" && $homepage != "http://" )
        PreviewSection( $homepage, "Homepage", &$the_color );

      if ( $interests != "" )
        PreviewSection ( $interests, "Interets", &$the_color );        

      /* The signature is a more complex section, hence more code */
      if ( $signature != "" )
        {
          /* Swap out the colors */
          if ($the_color == TABLE_COLOR_1)
            $the_color = TABLE_COLOR_2;
          else
            $the_color = TABLE_COLOR_1;

          /* Determine is the user is including the signatures or not */
          if ($include_sig == 1)
            $show_include = "You have chosen to include this signature on new posts.";
          else
            $show_include = "You have chosen to not include this signature on new posts.";

          /* Display the HTML for the signautre section */
          echo "                <TR bgcolor=\"$the_color\">\n"
             . "                  <TD width=\"25%\" valign=\"top\"><B>Signature:</B></TD>\n"
             . "                  <TD width=\"50%\">\n"
             . "                    $signature<BR><BR>\n"
             . "                    <I>$show_include</I>\n"
             . "                    <INPUT type=\"hidden\" name=\"signature\" value=\"$signature\">\n"
             . "                    <INPUT type=\"hidden\" name=\"include_sig\" value=\"$include_sig\">\n"
             . "                  </TD>\n"
             . "                </TR>\n";
        }

      /* And close off the page */
      echo "              </TABLE>\n"
         . "              <INPUT type=\"hidden\" name=\"old_email\" value=\"$old_email\">\n"
         . "              <CENTER>\n"
         . "                <BR>\n"
         . "                <INPUT type=\"Submit\" value=\"Edit Profile\" name=\"action\">\n"
         . "                &nbsp;\n"
         . "                <INPUT type=\"Submit\" value=\"Submit Profile\" name=\"action\">\n"
         . "              </CENTER>\n"
         . "            </FORM>\n";
      break;

    /* Check the user's input, add the user to the database, and display the results */
    case 4:
      /* If the page was POSTed to, then continue */
      if ( $REQUEST_METHOD == "POST" )
        {
          /* Start off with 0 errors */
          $no_err = 0;

          /* No errors means we continue with out plans */
          if ($no_err == 0)
            {
              /* Clear out the URL variables if they still contain 'http://' */
              if ($homepage == "http://") { $homepage = ""; }

              /* md5 the password to a random salt */
              if ($password != "")
                $password = md5($password); 

              /* If it doesn't equal 1, then set it equal to 0 */
              if ($include_sig != 1)
                $include_sig = 0;

              /* If the password is blank, then don't update the password, if it isn't then do it! */
              if ($password != "")
                $SQL = "UPDATE " . TABLE_PREFIX . "users SET user_email='" . toSQL($email) . "', user_pass='" 
					. toSQL($password) . "', user_city='" . toSQL($city) . "', user_occupation='" . toSQL($occupation) 
					. "', user_homepage='" . toSQL($homepage) . "', user_interests='" . toSQL($interests)
					. "', user_signature='" . toSQL($signature) . "', user_usesig='$include_sig' WHERE user_name='"
					. toSQL($username) . "';";
              else
                  $SQL = "UPDATE " . TABLE_PREFIX . "users SET user_email='" . toSQL($email) . "', user_city='" . toSQL($city)
				  	. "', user_occupation='" . toSQL($occupation) . "', user_homepage='" . toSQL($homepage)
					. "', user_interests='" . toSQL($interests)	. "', user_signature='" . toSQL($signature)
					. "', user_usesig='$include_sig' WHERE user_name='"	. toSQL($username) . "';";
              /* Execute the SQL query */
              $results = ExeSQL($SQL);

              /* Log the user in with their new password if they set one */
              if ($password != "")
                {
                  SetCookie("user_name", $username, time() + 3600, '', $HTTP_HOST);
                  SetCookie("user_pass", $password, time() + 3600, '', $HTTP_HOST);
                }

              /* Set the logged in variable to active */
              $logged_in = 1;
 
              /* Let the user know everything is cool */
              echo "            <CENTER>\n"
                 . "              Your profile has been updated!<BR>\n"
                 . "            </CENTER>\n"
                 . "            <BR>\n";

              /* Display the forum list */
              require("./forums/content/view_forums.php");

              return;
            }
          else
            {
              /* If there's an error, then display the form again */
              ShowProfileForm( $username, $password, $confirm_password, $email, $city, $occupation, $homepage, $interests, $signature, $include_sig );
            }
        }
      else
        {
          /* This means someone way trying to feed the script false info, just let them know and show the form again */
          echo "            <CENTER>Malformed request detected!</CENTER><BR><BR>\n";
          ShowProfileForm( $username, $password, $confirm_password, $email, $city, $occupation, $homepage, $interests, $signature, $include_sig );
        }
      break;
  }

/*
 *
 */

function ShowProfileForm( $username, $password, $confirm_password, $email, $city, $occupation, $homepage, $interests, $signature, $include_sig ) {
  echo "            <SCRIPT language=\"JavaScript\">\n";
  echo "              function\n";
  echo "              CheckForm()\n";
  echo "              {\n";
  echo "                if (document.profile.password.value != document.profile.confirm_password.value)\n";
  echo "                  {\n";
  echo "                    alert('The \'Password\' and \'Confirm Password\' fields must be the same!');\n";
  echo "                    document.profile.password.focus();\n";
  echo "                    document.profile.password.select();\n";
  echo "                    return false;\n";
  echo "                  }\n";
  echo "                if ( document.profile.password.value.length < 6 && document.profile.password.value != '' )\n";
  echo "                  {\n";
  echo "                    alert('The \'Password\' field must be at least 6 characters!');\n";
  echo "                    document.profile.password.focus();\n";
  echo "                    document.profile.password.select();\n";
  echo "                    return false;\n";
  echo "                  }\n";
  echo "                if (document.profile.signature.value.length > 255)\n";
  echo "                  {\n";
  echo "                    alert('The \'Signature\' field cannot exceed 255 characters!');\n";
  echo "                    document.profile.signature.focus();\n";
  echo "                    document.profile.signature.select();\n";
  echo "                    return false;\n";
  echo "                  }\n";
  echo "                return true;\n";
  echo "              }\n";
  echo "            </SCRIPT>\n";
  echo "            <FORM action=\"?location=forum&pid=edit_profile&step=3\" method=\"POST\" name=\"profile\">\n";
  echo "              <TABLE cellpadding=\"5\" cellspacing=\"0\" width=\"100%\" border>\n";
  echo "                <TR>\n";
  echo "                  <TD colspan=\"2\"><B>Required Information</B>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<I>Leave the password fields blank if you wish to keep your current password.</I></TD>\n";
  echo "                </TR>\n";
  $username = str_replace("\"", "&quot;", $username);
  echo "                <TR bgcolor=\"#EEEEEE\">\n";
  echo "                  <TD width=\"25%\" nowrap><B>Username:</B></TD>\n";
  echo "                  <TD width=\"50%\" nowrap>$username <INPUT type=\"hidden\" name=\"username\" value=\"$username\"></TD>\n";
  echo "                </TR>\n";
  $password = str_replace("\"", "&quot;", $password);
  echo "                <TR bgcolor=\"" . TABLE_COLOR_2 . "\">\n";
  echo "                  <TD width=\"25%\" nowrap><B>Mot de passe :</B></TD>\n";
  echo "                  <TD width=\"50%\" nowrap><INPUT type=\"password\" name=\"password\" value=\"$password\" maxlength=\"64\" size=\"50\"> Min 6 characters - Max: 64 characters</TD>\n";
  echo "                </TR>\n";
  $password = str_replace("\"", "&quot;", $password);
  echo "                <TR bgcolor=\"#EEEEEE\">\n";
  echo "                  <TD width=\"25%\" nowrap><B>Confirmation du mot de passe :</B></TD>\n";
  echo "                  <TD width=\"50%\" nowrap><INPUT type=\"password\" name=\"confirm_password\" value=\"$password\" maxlength=\"64\" size=\"50\"> Min: 6 characters - Max: 64 characters</TD>\n";
  echo "                </TR>\n";
  $email = str_replace("\"", "&quot;", $email);
  echo "                <TR bgcolor=\"#CCCCCC\">\n";
  echo "                  <TD width=\"25%\" nowrap><B>Email:</B></TD>\n";
  echo "                  <TD width=\"50%\" nowrap>$email<INPUT type=\"hidden\" name=\"email\" value=\"$email\"></TD>\n";
  echo "                </TR>\n";
  echo "                <TR>\n";
  echo "                  <TD colspan=\"2\">Informations facultatives</TD>\n";
  echo "                </TR>\n";
  $city = str_replace("\"", "&quot;", $city);
  echo "                <TR bgcolor=\"" . TABLE_COLOR_1 . "\">\n";
  echo "                  <TD width=\"25%\" nowrap><B>Ville :</B></TD>\n";
  echo "                  <TD width=\"50%\" nowrap><INPUT type=\"text\" name=\"city\" value=\"$city\" maxlength=\"128\" size=\"50\"> Max: 128 characters</TD>\n";
  echo "                </TR>\n";
  $occupation = str_replace("\"", "&quot;", $occupation);
  echo "                <TR bgcolor=\"#CCCCCC\">\n";
  echo "                  <TD width=\"25%\" nowrap><B>Occupation:</B></TD>\n";
  echo "                  <TD width=\"50%\" nowrap><INPUT type=\"text\" name=\"occupation\" value=\"$occupation\" maxlength=\"64\" size=\"50\"> Max: 64 characters</TD>\n";
  echo "                </TR>\n";
  $homepage = str_replace("\"", "&quot;", $homepage);
  echo "                <TR bgcolor=\"" . TABLE_COLOR_1 . "\">\n";
  echo "                  <TD width=\"25%\" nowrap><B>Homepage:</B></TD>\n";
  echo "                  <TD width=\"50%\" nowrap><INPUT type=\"text\" name=\"homepage\" value=\"$homepage\" maxlength=\"128\" size=\"50\"> Max: 128 characters</TD>\n";
  echo "                </TR>\n";
  $interests = str_replace("\"", "&quot;", $interests);
  echo "                <TR bgcolor=\"" . TABLE_COLOR_1 . "\">\n";
  echo "                  <TD width=\"25%\" nowrap><B>Interests:</B></TD>\n";
  echo "                  <TD width=\"50%\" nowrap><INPUT type=\"text\" name=\"interests\" value=\"$interests\" maxlength=\"255\" size=\"50\"> Max: 255 characters</TD>\n";
  echo "                </TR>\n";
  echo "                <TR bgcolor=\"" . TABLE_COLOR_1 . "\">\n";
  echo "                  <TD width=\"25%\" valign=\"top\" nowrap><B>Signature:</B></TD>\n";
  echo "                  <TD width=\"50%\" valign=\"top\" nowrap>\n";
  echo "                    <TABLE cellspacing=\"0\" cellpadding=\"0\" border=\"0\">\n";
  echo "                      <TR>\n";
  echo "                        <TD><TEXTAREA name=\"signature\" rows=\"5\" cols=\"40\" maxlength=\"255\">$signature</TEXTAREA></TD><TD valign=\"top\" nowrap>&nbsp;Max: 255 characters</TD>\n";
  echo "                      </TR>\n";

  if ($include_sig == 1)
    $checked = " checked";
  else
    $checked = "";

  echo "                      <TR>\n";
  echo "                        <TD colspan=\"2\"><INPUT type=\"checkbox\" name=\"include_sig\" value=\"1\"$checked> Include signature on new posts?</TD>\n";
  echo "                      </TR>\n";
  echo "                    </TABLE>\n";
  echo "                  </TD>\n";                     
  echo "                </TR>\n";
  echo "              </TABLE>\n";
  echo "              <INPUT type=\"hidden\" name=\"old_email\" value=\"$email\">\n";
  echo "              <CENTER><BR><INPUT type=\"Submit\" value=\"Previsualiser\" onClick=\"return CheckForm();\"></CENTER>\n";
  echo "            </FORM>\n";
}

function PreviewSection ( $section_value, $section_title, $the_color ) {
  if ($the_color == TABLE_COLOR_1)
    $the_color = TABLE_COLOR_2;
  else
    $the_color = TABLE_COLOR_1;

  echo "                <TR bgcolor=\"$the_color\">\n";
  echo "                  <TD width=\"25%\" valign=\"top\"><B>$section_title:</B></TD>\n";
  echo "                  <TD width=\"50%\">\n";

  if ($section_title == "Mot de passe")
    echo "                    <I>Le mot de passe est masqué pour des raisons de securité.</I>\n";
  else
    echo "                    $section_value\n";

    $section_title = strtolower($section_title);


  /* URL encode the double quotes */
  $section_value = str_replace("\"", "&quot;", $section_value);

  echo "                    <INPUT type=\"hidden\" name=\"$section_title\" value=\"$section_value\">\n";
  echo "                  </TD>\n";
  echo "                </TR>\n";
}

?>
