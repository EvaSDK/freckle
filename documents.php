<?php
  $start_time = microtime();
  ob_start();

  @include("_admin/param.php");
  include("forums/include.php");
  include("./inc/stats.inc");
  include("./inc/news.inc");

  $countup=0;
  
  if ( $_COOKIE["freckle-esiee"] != "1" )
  {
    SetCookie("freckle-esiee","1",time()+1500);
    $countup=1;
  }

  $conn = mysql_pconnect($dns,$usr,$pwd);
  mysql_select_db ( $db , $conn );

  echo "<?xml version='1.0' encoding='ISO-8859-1'?>\n";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html  xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr">
<head>
 <title>FRECKLE - Documents</title>
 <link href="./style.css" type="text/css" media="screen" rel="stylesheet" />
 <!-- script from www.alistapart.com -->
 <!-- <script type="text/javascript" src="styleswitcher.js"></script> -->

 <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
</head>

<body>

 <div class="head">
 <h1>Freckle</h1>
 <img src="essai-freckle.png" title="bann" alt="logo" />
 </div>

 <div class="menu">
  <?php include("menu.inc.php"); ?>
 </div>

 <div class="content">
  <h2 class="titre-page">Documents</h2>

  <div class="box">
   <h3>Trouver...</h3>
   <p>Afficher les documents de la catégorie:</p>
   <form action="#" method='post'>
    <fieldset>
     <input name="see" type="hidden" value="4" />
     <?php
      $ptr = mysql_query("select * from cat order by code");
      echo "<select name='categorie'>\n";
      while($row = mysql_fetch_object($ptr)) {
        echo "<option value='" . pow(2,$row->id) ."'>[$row->code] $row->nom</option>\n";
 	}
      echo "</select>";
     ?>
    <input type="submit" name="Submit2" value="Voir" />
   </fieldset>
<!--
	  <a href=\"index.php?location=file&focus=yes&focus1="
         . pow(2,$row->id) . "\">
       echo "<td class=\"$class\"><a href=\"index.php?location=file&focus=yes&focus1="
         . pow(2,$row->id) . "\">$row->nom</a></td></tr>";
-->
  </form>
 </div>

 <div class="box">
   <?php if( $see != 4 )
     {
       echo "<h3>Nouveaux</h3>\n";
       echo "<h4>Les 10 documents les plus récents</h4>\n";
       echo "<table>\n";
       display_documents( "10", $conn );
     } else {
       echo "<h3>Résultats</h3>\n";
       echo "<table>\n";
       display_documents( $categorie, $conn );
     }
   ?>
   </table>
  </div>

  <div class="box" id="gene">
   Freckle - Mis à jour le 19/02/2004 - Généré en <?php echo round(microtime() - $start_time, 3); ?> secondes.
  </div>

 </div>

 <hr />

 <div class="foot" >

  <?php include("foot.inc.php"); ?>
 </div>

</body>
</html>
