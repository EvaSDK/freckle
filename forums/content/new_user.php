<?
/******************************************************************************\
 * Copyright (C) 2002 B Squared (b^2) by Josh Sherman <josh@cleancode.org>    *
\******************************************************************************/

/* Call this file directly, get sent back */
$file_name = "new_user.php";

/* Get the negative length of $file_name */
$file_name_length = -(strlen($file_name));

/* Check if the values match, if so, redirect */
if (substr($_SERVER['SCRIPT_NAME'], $file_name_length) == $file_name)
  header("Location: ./index.php");

// Grab the veriables held by super globals 
$username    = GetVars("username");
$password    = GetVars("password");
$email       = GetVars("email");
$action      = GetVars("action");
$step        = GetVars("step");

/* Parse any user input */
CheckVars(&$step, 1);
CheckVars(&$username, 64);
CheckVars(&$password, 64);
CheckVars(&$email, 128);

/* Strip &nbsp; from the username */
$username = str_replace("&nbsp;", "", $username);

/* Check that the user isn't trying to mess with the $step variable */
if ( $step == "" || ( $step != 1 && $step != 2 && $step != 3 ) )
  $step = 1;

/* Make sure the user isn't feeding information via the query string, thwart all attempts!! */
if ( ( ( $username == "" || $password == "" || $email == "" ) && ( $step == 3 || $step == 4 ) ) || 
     ( ( $step == 1 && $QUERY_STRING != "location=forum&pid=new_user" ) ||
       ( $step == 2 && $QUERY_STRING != "location=forum&pid=new_user&step=2" ) ||  
       ( $step == 4 && $QUERY_STRING != "location=forum&pid=new_user" ) ) )
  {
    /* If so, give them an error */
    echo "            <CENTER>Malformed request detected!</CENTER><BR>\n";
    $step = 1;
  }

/* Determine which step to go to */
if ($action == "Edit Information")
  $step = 1;
else if ($action == "Submit Information")
  $step = 3;


/* Again, with some sig clean up */
if ($step == 2)
  {
    $username   = stripslashes(strip_tags($username));
    $password   = stripslashes(strip_tags($password));
    $email      = stripslashes(strip_tags($email));
  }

/* To step, or not to step! */
switch ($step) {
default:
case 1:
	ShowRegistrationForm();
break;
/* Display the info the user supplied and prompt them to continue or edit */
case 2:
    /* Line starts here, no cutting [or pasting ;)] */
	echo "            <FORM action=\"index.php?location=forum&pid=new_user\" method=\"POST\" name=\"registration\">\n"
         . "              <TABLE cellpadding=\"5\" cellspacing=\"0\" width=\"100%\" border>\n"
         . "                <TR class=\"table_header\">\n"
         . "                  <TD colspan=\"2\">Previsualisation de l'enregistrement</TD>\n"
         . "                </TR><tr><td><b>Informations du compte  : </b><br>\n";

      /* Display the mandatory fields */
      PreviewSection ( $username, "Username");
      PreviewSection ( $password, "Mot de passe"); 
      PreviewSection ( $email, "Email");
     
      /* And then we finish off the form */
      echo "              </TABLE>\n"
         . "              <CENTER><BR><INPUT type=\"Submit\" value=\"Effacer\" name=\"action\"> <INPUT type=\"Submit\" value=\"Valider\" name=\"action\"></CENTER>\n"
         . "            </FORM>\n";
      break;

/* Check the user's input, add the user to the database, and display the results */
case 3:
      /* Make sure it was POSTed, if it wasn't they are trying to be slick */
      if ( $_SERVER["REQUEST_METHOD"] == "POST" ) {
          /* No errors... yet */
          $no_err = 0;

          /* Pull the number of accounts with the same username */
          $SQL     = "SELECT COUNT(*) as user_exists FROM " . TABLE_PREFIX . "users WHERE user_name='$username';";
          $results = ExeSQL($SQL);

          /* Grab the data, parse the results */
          while ($row = mysql_fetch_array($results))
            {
              /* If the username exists, error out */
              if ($row["user_exists"] != 0)
                {
                  echo "            <CENTER>That username already exists!</CENTER><BR>\n";
                  $no_err++;
                }
            }

          /* Pull the number of accounts with the same email */
          $SQL     = "SELECT COUNT(*) as email_exists FROM " . TABLE_PREFIX . "users WHERE user_email='$email';";
          $results = ExeSQL($SQL);

          /* Grab the data, parse the results */
          while ($row = mysql_fetch_array($results))
            {
              /* If the email exists, then error out */
              if ($row["email_exists"] != 0)
                {
                  echo "            <CENTER>Someone has already registered using that email address!</CENTER><BR>\n";
                  $no_err++;
                }
            }

          /* If there are no errors, then proceed with the registration */
          if ($no_err == 0) {

          	echo "<br><br><b> Informations du compte  : </b><br>\n";

			/* Display the mandatory fields */
			PreviewSection ( $username, "Username");
			PreviewSection ( $password, "Mot de passe"); 
			PreviewSection ( $email, "Email");
			
			echo "<i>Modifiez votre mot de passe rapidement</i><br><br>";
              /* md5 the password to a random salt */
              $password = md5($password); 
  
              /* Insert the user into the database */
              $SQL = "INSERT INTO " . TABLE_PREFIX . "users (user_id, user_name, user_email, user_pass) VALUES ("
			  	 .  getUId() . ", '$username', '$email', '$password');";
              $results = ExeSQL($SQL);

              /* Log the new user in */
              SetCookie("user_name", $username, time() + 86400, '', $HTTP_HOST);
              SetCookie("user_pass", $password, time() + 86400, '', $HTTP_HOST);

              /* Set their login status */
              $logged_in = 1;
 
              /* Finish off the registration */
              echo "            <CENTER><B>New user registred</B><BR></CENTER><BR>\n";
              require("./forums/content/view_forums.php");
              return;
            }
          else
            ShowRegistrationForm();
        }
      else
        {
          /* If they didn't POST it, then error out */
          echo "            <CENTER>Malformed request detected!</CENTER><BR>\n";
          ShowRegistrationForm();
        }
      break;
  }

/*
 * Show the registration form
 */

function ShowRegistrationForm() {
  /* Start displaying the damned thing */
	echo "          <SCRIPT language=\"JavaScript\">\n"
     . "              function\n"
     . "              CheckForm()\n"
     . "              {\n"
     . "                if (document.registration.username.value == '')\n"
     . "                  {\n"
     . "                    alert('The \'Username\' field is mandatory!');\n"
     . "                    document.registration.username.focus();\n"
     . "                    return false;\n"
     . "                  }\n"
     . "                if (document.registration.password.value == '')\n"
     . "                  {\n"
     . "                    alert('The \'Password\' field is mandatory!');\n"
     . "                    document.registration.password.focus();\n"
     . "                    return false;\n"
     . "                  }\n"
     . "                if (document.registration.password.value.length < 6)\n"
     . "                  {\n"
     . "                    alert('The \'Password\' field must be at least 6 characters!');\n"
     . "                    document.registration.password.focus();\n"
     . "                    document.registration.password.select();\n"
     . "                    return false;\n"
     . "                  }\n"
     . "                if (document.registration.email.value == '')\n"
     . "                  {\n"
     . "                    alert('The \'Email\' field is mandatory!');\n"
     . "                    document.registration.email.focus();\n"
     . "                    return false;\n"
     . "                  }\n"
     . "                if (!ValidateEmail(document.registration.email.value))\n"
     . "                  {\n"
     . "                    alert('You must supply a valid email address.');\n"
     . "                    document.registration.email.focus();\n"
     . "                    document.registration.email.select();\n"
     . "                    return false;\n"
     . "                  }\n"
     . "                return true;\n"
     . "              }\n"
     . "              function\n"
     . "              ValidateEmail(address)\n"
     . "              {\n"
     . "                if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(address))\n"
     . "                  {\n"
     . "                    return true;\n"
     . "                  }\n"
     . "                return false;\n"
     . "              }\n"
     . "            </SCRIPT>\n"
     . "            <FORM action=\"index.php?location=forum&pid=new_user&step=2\" method=\"POST\" name=\"registration\">\n"
     . "              <TABLE cellpadding=\"5\" cellspacing=\"0\" width=\"100%\" border>\n"
     . "                <TR class=\"table_header\">\n"
     . "                  <TD colspan=\"2\">Required Information</TD>\n"
     . "                </TR>\n"
     . "                <TR bgcolor=\"" . TABLE_COLOR_1 . "\">\n"
     . "                  <TD width=\"25%\" nowrap><B>Username:</B></TD>\n"
     . "                  <TD width=\"50%\" nowrap><INPUT type=\"text\" name=\"username\" value=\"$username\" maxlength=\"64\" size=\"50\"> Max: 64 characters</TD>\n"
     . "                </TR>\n"
     . "                <TR bgcolor=\"" . TABLE_COLOR_2 . "\">\n"
     . "                  <TD width=\"25%\" nowrap><B>Mot de passe :</B></TD>\n"
     . "                  <TD width=\"50%\" nowrap><INPUT type=\"text\" name=\"password\" maxlength=\"64\" size=\"50\" value=\"" 
	 . uniqid ("") . "\"> Min 6 characters - Max: 64 characters</TD>\n"
     . "                </TR>\n"
     . "                <TR bgcolor=\"" . TABLE_COLOR_2 . "\">\n"
     . "                  <TD width=\"25%\" nowrap><B>Email:</B></TD>\n"
     . "                  <TD width=\"50%\" nowrap><INPUT type=\"text\" name=\"email\" maxlength=\"128\" size=\"50\"> Max: 128 characters</TD>\n"
     . "                </TR>\n"
     . "              </TABLE>\n"
     . "              <CENTER><BR><INPUT type=\"Submit\" value=\"Preview Information\" onClick=\"return CheckForm();\"></CENTER>\n"
     . "            </FORM>\n";
}

/*
 * Display the portion that is being previewed
 */
function PreviewSection ( $section_value, $section_title) {
  /* Display the start of the section */
  echo "<B>$section_title : </B> $section_value\n";
 $section_title = strtolower($section_title);
  /* And, we're out */
  echo "                    <INPUT type=\"hidden\" name=\"$section_title\" value=\"$section_value\"><br>\n";
}
?>
