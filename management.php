<?php
  session_start();
  include("./inc/auth.inc.php");
  include("./inc/general.inc.php");
  include("./inc/management.inc.php");
  
  $password = $_POST['password'];
  $username = $_POST['username'];
 
  if ($_GET['logout']==TRUE) {
    logout_admin();
  }

  if ( ($password=="coin" and $username=="freckle_admin") or $_SESSION['admin']==TRUE) {
    $_SESSION['admin']=TRUE;
  } else {
    $_SESSION['admin']=FALSE;
  }
  
  echo "<?xml version='1.0' encoding='ISO-8859-1'?>\n";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html  xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr">
<head>
 <title>FRECKLE</title>
 <link href="./style.css" type="text/css" media="screen" rel="stylesheet" />

 <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
</head>

<body>
 
 <div class="head">
 <h1>Freckle/Admin</h1>
 <img src="./freckle.png" title="bannière" alt="logo freckle" />
 </div>

 <div class="menu">
  <?php include("./inc/menu.inc.php"); ?>
 </div>

 <div class="content">
  <h2 class="titre-page">Administration</h2>

  <div class="box">
  <?php
  if ($_SESSION['admin']==FALSE)
  {
    login_admin();
  } else
  {
    $what = $_GET['what'];
    $current = $_GET['current'];

    if ($what=='') { $what="types"; }
    if ($current=='') { $current = 0; }

    echo "<h3>Vue des données</h3>\n";
    echo "<h4>\n";
    echo "\t<div class='admin-datalist'><a href='management?what=types'>Types</a>";
    echo "<a href='management.php?what=fichiers'>Fichiers</a>";
    echo "<a href='management.php?what=categorie'>Catégorie</a>";
    echo "<a href='management.php?what=affect'>Classer</a>\n";
    echo "<a href='management.php?what=defect'>Désaffecter</a></div>\n";
    display_list_access($what,$current,15);
    echo "\n</h4>\n";

    $msg = $_SESSION['message'];
    unset($_SESSION['message']);
    if($msg!='')
    {
      echo "<p id='message'>$msg</p>\n";
    }
    
    display_list_entries($what,$current,15);
    echo "</div>\n<div class='box'>\n<h3>Actions</h3>\n";
    get_form($what); 
  }
  ?> 
  </div>
 </div>

 <hr />

 <div class="foot">
  <?php include("./inc/foot.inc.php"); ?>
 </div>

</body>
</html>

  
