<?php

function display( $long, $conn, $what )
{
  switch ( $what ) {
    case "news": display_news( $long, $conn); break;
    case "actu": display_actu( $long, $conn); break;
    case "site": display_sites( $long, $conn); break;
  }
}

function display_news( $long, $conn )
{
  if ( $long == "short" ) {
    $sql="SELECT * FROM news ORDER BY Date DESC LIMIT 0,5";
  } else {
    $sql="SELECT * FROM news ORDER BY Date DESC";
  }

  echo "<ul class='news'>\n";
  if($p = @mysql_query($sql,$conn))
  {
    while($r = @mysql_fetch_array($p))
    {
      echo " <li><span>[$r[Date]]</span> $r[Comment]</li>\n  ";
    }
  }

  if ( $long == "short" ) echo " <li><a href='display.php?what=news'>En savoir plus</a></li>\n";
  echo "</ul>\n";
}

function display_actu( $long, $conn )
{
  $i=0;
  if ( $long == "short" ) {
    $sql="SELECT * FROM actu ORDER BY Date DESC , Id DESC LIMIT 6";
  } else {
    $sql="SELECT * FROM actu ORDER BY Date DESC";
  }

  if($ptr = mysql_query($sql,$conn))
  {
    while($r = mysql_fetch_array($ptr))
    {
      echo "<h4>\n";
      echo "<img src=\"./_img_actu/";
      if ($r[Logo] == 0 ) { echo "news.gif"; } else { echo $r[Adresselogo]; }
      echo "\" alt='icon'/>\n";
      echo "<span>[$r[Date]]</span> <strong>$r[Nom]</strong>:";
      echo "</h4>\n";
      echo "<p>\n$r[Comment]\n</p>";
      $i = $i+1 ;
    }
  }
}

function display_documents( $long, $conn )
{
  if( $long == "10" ) {
    $sql = "select * from file order by id desc limit 10";
  } else {
    $sql = "select * from file where cat & $long = $long order by id desc";
  }

  if($ptr = mysql_query($sql)) {
    $lcat = getCatCode();
    while($row = mysql_fetch_object($ptr)) {
      $filename = str_replace("&", "&amp;", $row->file);
      
      if( strlen($filename) >= 35 )
      {
        $filename = "<abbr title=\"$filename\">" . substr($filename,0,20) . "...</abbr>";
      }
      
      $link = str_replace("&", "&amp;", $row->link);
      echo "<tr>\n";
      echo "  <td class='icon'><img src='" . getIcon($row->file) . "' alt='icon'/></td>\n";
      echo "  <td><a href='$link'>$filename</a></td>\n";
      echo "  <td class='right'>" . getCat($row->cat , $lcat) . "</td>\n</tr>\n";
    };
    /*if ($ptr==NULL) echo "<tr><td><p>Pas de Résultats dans cette catégorie</p></td></tr>\n";*/
  }
}


function display_sites( $long, $conn )
{
  $i=0;
  if ( $long == "short" ) {
    $sql="SELECT * FROM site ORDER BY RAND() LIMIT 0,6";
  } else {
    $sql="SELECT * FROM site ORDER BY Nom ASC";
  }

  if($ptr = @mysql_query($sql,$conn))
  {
    while($r = @mysql_fetch_array($ptr))
    {
      echo "<h4>\n";
      echo "<img src=\"./_img_site/";
      if ($r[Logo] == 0 ) { echo "logo_freckle.gif"; } else { echo $r[AdresseLogo]; }
      echo "\" alt='icon'/>\n";
       echo "<strong>$r[Nom]</strong>:\n";
      echo "</h4>\n";
      echo "<p>\n<a href=\"$r[Adresse]\">$r[Comment]</a>\n</p>";
      $i = $i+1 ;
    }
  }
}

function display_categorie() {
  $ptr = mysql_query("select * from cat order by code");
  $i=0;
  
  echo "<table>\n";
  while( $row = mysql_fetch_object($ptr) )
  {
    if( $i==0 ) echo "<tr>\n";
    echo " <td class='right'>[$row->code]</td>\n";
    echo " <td><a href='documents.php?see=4&amp;categorie=" . pow(2,$row->id) . "'>$row->nom</a></td>\n";
    if( $i==1 ) {
	  echo "</tr>\n";
	  $i=-1;
	}
    $i = $i+1;
  }
  echo "</tr>\n</table>\n";
}

?>
