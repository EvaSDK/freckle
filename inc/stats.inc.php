<?php

/* Affiche le nombre de visiteurs */
function visiteurs() {
  if ($countup==1) mysql_query( "UPDATE visit SET clicks=clicks+1");

  $res = @mysql_query( "SELECT clicks FROM visit");
  $row = @mysql_fetch_object($res);

  if ($row) {
    echo $row->clicks;
  } else {
    echo 0;
  }
}

/* Affiche le nombre de pages vues == pages affiches */
function viewed_pages() {
  $sql="UPDATE hit SET clicks=clicks+1";
  mysql_query($sql);
  $res = @mysql_query("SELECT clicks FROM hit");
  $row = @mysql_fetch_object($res);

  if ($row) {  
    echo $row->clicks;
  } else {
    echo 0;
  }
}

/* Compte le nombre de fichiers dans la base */
function registred_documents() {
  $res = @mysql_query("SELECT count(*) as cc from file");
  $row = @mysql_fetch_object($res);
  if ($row) {
    echo $row->cc;
  } else {
    echo 0;
  }
}

/* nombre de sites enregistres dans la base */
function registred_sites() {
  $res = @mysql_query("SELECT count(*) as cc from site");
  $row = @mysql_fetch_object($res);
  if ($row) {
    echo $row->cc;
  } else {
    echo 0;
  }
}

?>
