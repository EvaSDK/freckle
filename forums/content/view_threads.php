<?
/* Redirect if this file is called directly */
$file_name = "view_forums.php";

/* Get the negative length of $file_name */
$file_name_length = -(strlen($file_name));

/* Check if the values match, if so, redirect */
if (substr($_SERVER['SCRIPT_NAME'], $file_name_length) == $file_name)
  header("Location: ../forum.php");

/* Pull the forum id list from the database */
$SQL     = "SELECT forum_id FROM " . TABLE_PREFIX . "forums;";
$results = ExeSQL($SQL);

/* Grab the data and load it into an array */
while ($row = mysql_fetch_array($results))
  $forum_list[] = $row["forum_id"];

/* If the forum doesn't exist, then halt */
if (!in_array($forum_id, $forum_list))
  {
    /* Tell the user what's up */
    echo "            <CENTER>Malformed request detected!</CENTER><BR>\n";
    require ("./forums/content/view_forums.php");
    return;
  }

/* Start the table */
echo "            <TABLE width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">\n"
   . "              <TR>\n";

/* Pull the forum name from the database */
$SQL     = "SELECT * FROM " . TABLE_PREFIX . "forums WHERE forum_id='$forum_id';";
$results = ExeSQL($SQL);

/* Grab the data and display it */
while ($row = mysql_fetch_array($results))
  echo "                <TD><A href=\"?location=forum&pid=view_forums\">Forum</A> > " . $row["forum_name"]."</TD>\n";

/* Count the number of threads for the named forum */
$SQL     = "SELECT COUNT(*) AS any_threads FROM " . TABLE_PREFIX . "threads WHERE forum_id='$forum_id';";
$results = ExeSQL($SQL);

/* Grab the data, and load it in a variable */
while ($row = mysql_fetch_array($results))
  $any_threads = $row["any_threads"];

/* If there are threads then display them */
if ($any_threads != 0)
  {
    /* Display the Post new thread link */
    echo "                <TD align=\"right\"><A href=\"?location=forum&pid=post_thread&forum_id=$forum_id\">Post New Thread</A></TD>\n"
       . "              </TR>\n"
       . "            </TABLE>\n"
       . "            <BR>\n";

    /* Build the HTML table (column headings) */
    echo "            <TABLE cellspacing=\"0\" cellpadding=\"5\" width=\"100%\" border>\n"
       . "              <TR>\n"
       . "                <TD width=\"100%\">Thread</TD>\n"
       . "                <TD align=\"center\" width=\"1\">Auteur</TD>\n"
       . "                <TD align=\"center\" width=\"1\">Reponses</TD>\n"
       . "                <TD width=\"\" nowrap>Post&eacute; le</TD>\n"
       . "              </TR>\n";

    /* Pull each thread title and date/time in a nice format in time order */
    $SQL     = "SELECT * FROM " . TABLE_PREFIX . "threads WHERE forum_id='$forum_id' ORDER BY thread_time DESC;";
    $results = ExeSQL($SQL);

    /* Grab the data, and display it in the table */
    while ($row = mysql_fetch_array($results))
      {
        /* Grab the Thread ID and the User ID */
        $thread_id = $row["thread_id"];
        $user_id   = $row["user_id"];

    	/* Pull the total number of replies for each thread */
    	$SQL      = "SELECT COUNT(*) AS total_items FROM " . TABLE_PREFIX . "replies WHERE thread_id='$thread_id';";
    	$results2 = ExeSQL($SQL);

    	/* Grab the data, and load it in an array */
    	while ($row2 = mysql_fetch_array($results2))
    	  $total_items = $row2["total_items"];

        /* Grab the total number of threads */
        if ($total_items == "")
          $total_replies = "--";
        else
          $total_replies = $total_items;

	/* Pull each user name from the database */
    	$SQL      = "SELECT * FROM " . TABLE_PREFIX . "users WHERE user_id='$user_id';";
    	$results2 = ExeSQL($SQL);

    	/* Grab the data and load it into an array */
    	while ($row2 = mysql_fetch_array($results2))
	  $the_user = $row2["user_name"];

        /* Set which image to show for the thread */
        if ( date( "z y" , $row["thread_time"]) == date( "z y" ,time() ) ) {
			if ( $total_replies >= 25 ) {
				$which_image = "folder-blue-fire";
			}
			else {
				$which_image = "folder-blue";
			}
		}
        else if ( $total_replies >= 25 )
          $which_image = "folder-yellow-fire";
        else
          $which_image = "folder-yellow";

        /* Spit out the rest of the HTML */
        echo "              <TR>\n"
           . "                <TD bgcolor=\"" . TABLE_COLOR_1 . "\">\n"
           . "                  <IMG src=\"./_img_forum/$which_image.png\"> <A href=\"?location=forum&pid=view_replies&thread_id=" . $row["thread_id"] . "&forum_id=$forum_id\">" . $row["thread_title"] . "</A>\n"
           . "                </TD>\n"
           . "                <TD bgcolor=\"#CCCCCC\" align=\"center\" nowrap>\n"
           . "                  <A href=\"?location=forum&pid=view_profile&user=" . $the_user . "\">" . $the_user . "</A>\n"
           . "                </TD>\n"
           . "                <TD bgcolor=\"" . TABLE_COLOR_1 . "\" align=\"center\">\n"
           . "                  " . $total_replies . "\n"
           . "                </TD>\n"
           . "                <TD bgcolor=\"#CCCCCC\" nowrap>\n";
		$post_time = getFrDate($row["thread_time"]);
		echo $post_time[1] . $post_time[2] . "</TD>\n"
           . "              </TR>\n";
      }

    /* Close off the table, and display the key */
    echo "            </TABLE>\n"
       . "            <BR>\n"
       . "            <IMG src=\"./_img_forum/folder-yellow.png\"> = Older threads<BR>\n"
       . "            <IMG src=\"./_img_forum/folder-blue.png\"> = Today's threads<BR>\n"
       . "            <IMG src=\"./_img_forum/folder-yellow-fire.png\"> = Hot thread with 25+ replies<BR>\n"
       . "            <IMG src=\"./_img_forum/folder-blue-fire.png\"> = Hot thread from today<BR>\n";
  }
else
  {
    /* If there are no active threads, display this stuff */
    echo "              </TR>\n"
       . "            </TABLE>\n"
       . "            <BR>\n"
       . "            <CENTER>\n"
       . "              <B>There are no active threads in this forum.</B><BR>\n"
       . "              <A href=\"?location=forum&pid=post_thread&forum_id=$forum_id\">Click here if you'd like to post a new thread.</A>\n"
       . "            </CENTER>\n";
  }

?>
