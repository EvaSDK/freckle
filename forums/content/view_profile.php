<?

/******************************************************************************\
 * Copyright (C) 2002 B Squared (b^2) by Josh Sherman <josh@cleancode.org>    *
 *                                                                            *
 * This script displays the contents for the 'View Profile' page.  Don't      *
 * forget the 12 space indent for all content pages.                          *
 *                                                                            *
 *                                 Last modified : September 24th, 2002 (JJS) *
\******************************************************************************/

/* Stop all direct access to this file!!! */
$file_name = "view_profile.php";

/* Get the negative length of $file_name */
$file_name_length = -(strlen($file_name));

/* Check if the values match, if so, redirect */
if (substr($_SERVER['SCRIPT_NAME'], $file_name_length) == $file_name)
  header("Location: ../forum.php");

// Grab the veriables held by superglobals 
$user  = GetVars("user");

/* Parse any user input */
CheckVars(&$user, 64);

/* Pull the number of accounts with the specified username */
$SQL     = "SELECT COUNT(*) AS user_exists FROM " . TABLE_PREFIX . "users WHERE user_name='$user';";
$results = ExeSQL($SQL);

/* Grab the data and add it to a variable */
while ($row = mysql_fetch_array($results))
  $user_exists = $row["user_exists"];

/* If the user doesn't exist then ... */
if ($user_exists == 0)
  {
    /* Let the user know what's up, then redirect to the view forums page */
    echo "            <CENTER>Sorry, there are no users by that name!</CENTER><BR><BR>\n";
    require("view_forums.php");
  }
else
  {
    /* Pull the information for the specified username */
    $SQL     = "SELECT * FROM " . TABLE_PREFIX . "users WHERE user_name='$user';";
    $results = ExeSQL($SQL);

    /* Grab the data, and add it to variables */
    while ($row = mysql_fetch_array($results))
      {
        $username   = $row["user_name"];
        $email      = $row["user_email"];
        $city   = $row["user_city"];
        $occupation = $row["user_occupation"];
        $homepage   = $row["user_homepage"];
        $interests  = $row["user_interests"];
      }

    /* Display the table header */
    echo "            <TABLE cellpadding=\"5\" cellspacing=\"0\" width=\"100%\" border>\n"
       . "              <TR>\n"
       . "                <TD colspan=\"2\">$username's Profile</TD>\n"
       . "              </TR>\n";

    /* Set the active color to the second color */
    $the_color = TABLE_COLOR_2;

    /* Preview the email section */
    PreviewSection ( $email, "Email", &$the_color );

    /* If the city isn't NULL, then preview it */
    if ( $city != "" )
      PreviewSection( $city, "city", &$the_color );

    /* same with the occupation */
    if ( $occupation != "" )
      PreviewSection( $occupation, "Occupation", &$the_color );

    /* and the homepage */
    if ( $homepage != "" && $homepage != "http://" )
      PreviewSection( $homepage, "Homepage", &$the_color );

    /* Can't forget the interests */
    if ( $interests != "" )
      PreviewSection ( $interests, "Interests", &$the_color );

    /* Close out the fuggin' table */
    echo "            </TABLE>\n";
  }

/*
 * This function lets you preview sections, and 
 * kills a lot of repetative, and messy code
 */
function
PreviewSection ( $section_value, $section_title, $the_color )
{
  /* Swap the colors */
  if ($the_color == TABLE_COLOR_1)
    $the_color = TABLE_COLOR_2;
  else
    $the_color = TABLE_COLOR_1;

  /* Display the section name */
  echo "              <TR bgcolor=\"$the_color\">\n"
     . "                <TD width=\"25%\" valign=\"top\"><B>$section_title:</B></TD>\n"
     . "                <TD width=\"50%\">\n"
     . "                  ";

  /* Jump to the section for the appropriate section */
  switch ($section_title)
    {
      /* Email section */
      case "Email":
        echo "<A href=\"mailto:$section_value\">$section_value</A>";
        break;

      /* Homepage section */
      case "Homepage":
        echo "<A href=\"$section_value\" target=\"_blank\">$section_value</A>";
        break;


      /* Not specified, then just display the value */
      default:
        echo "$section_value";
        break;
    }
  
  /* Finish it off */
  echo "\n"
     . "                </TD>\n"
     . "              </TR>\n";
}

?>
