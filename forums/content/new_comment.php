<?
/* Disallow direct access to this file */
$file_name = "new_comment.php";

/* Get the negative length of $file_name */
$file_name_length = -(strlen($file_name));

/* Check if the values match, if so, redirect */
if (substr($_SERVER['SCRIPT_NAME'], $file_name_length) == $file_name)
  header("Location: ../index.php");

// Get the variables we need
$user_ip = GetVars("REMOTE_ADDR");

CheckVars(&$user_ip, 15);

/* Make sure the form was POSTed */
if ( $_SERVER["REQUEST_METHOD"] == "POST" ) {
	/* Pull the user's signature */
    $sql     = "SELECT * FROM file WHERE id=$id";
    $results = ExeSQL($sql);
	$row = mysql_fetch_object($results);
	if ($row!=false) {
		$sql = "select fileid from " . TABLE_PREFIX . "threads where fileid=$id";
	    $results = ExeSQL($sql);
		if ( mysql_fetch_object($results)==false) {	
			$sql = "INSERT INTO " . TABLE_PREFIX . "threads "
				. "(thread_id, thread_time , thread_title, thread_body, user_id, user_ip, forum_id , isfile , fileid)"
				. "VALUES ( " .  getUId() . " , " . time() . " , 'Commentaires sur $row->file',"
				. " 'Le fichier : <a href=\"$row->link\">$row->file</a>',"
				. " '1', '$user_ip', '3' , '1' , $id)";
			
	        ExeSQL($sql);
			$sql = "select thread_id from " . TABLE_PREFIX . "threads where fileid=$id";
			$results = ExeSQL($sql);
			$row = mysql_fetch_object($results);
			if ($row!=false) {
				$sql = "update file set forumid=$row->thread_id where id=$id";	
				ExeSQL($sql);
			}
			/* Give 'em props */
        	echo "<CENTER>Vous pouvez maintenant ajouter vos commentaires</CENTER><BR>\n";

	        /* Show the thread list */          
			$thread_id=$row->thread_id;
			$forum_id=3;
    	    require ("./forums/content/view_replies.php");
		}
	}
}
else {
        /* If not POSTed, then error out */
        echo "<CENTER>Malformed request detected!</CENTER><BR>\n";
}

?>
