<?
/******************************************************************************\
 * Copyright (C) 2002 B Squared (b^2) by Josh Sherman <josh@cleancode.org>    *
\******************************************************************************/

/* Redirect the person if they call this file directly */
$file_name = "view_message.php";

/* Get the negative length of $file_name */
$file_name_length = -(strlen($file_name));

/* Check if the values match, if so, redirect */
if (substr($_SERVER['SCRIPT_NAME'], $file_name_length) == $file_name)
  header("Location: ../index.php");

?>
<TABLE cellpadding=5 cellspacing=0 width=100% border>
  <TR> 
    <th align="center" nowrap>QUESTION &amp; RÉPONSES</th>
  </TR>
  <TR> 
    <TD bgcolor=#CCCCCC><P><B>Questions</B></P>
      <P><B>[1]</B> <A 
            href="#1">Comment puis je recuperer mon password ?</A></P>
      <P><B>[2]</B> <A 
            href="#2">Je n'arrive pas &agrave; me connecter, pourquoi ?</A></P>
      <P><B>Réponse</B></P>
      <p><B>[1] Comment puis-je recuperer mon password ?<A 
            name=1></A></B><BR>
      Si vous avez oubli&eacute; votre password, il n'est pas possible de le recuperer
        cepandant il est possible d'en obtenir un nouveau par <?php echo $mymail; ?> en
      precisant votre e-mail et login.</p>
      <p><B>[2] Je n'arrive pas &agrave; me connecter, pourquoi ?<A 
            name=2></A></B><BR>
Pour pouvoir se connecter, il faut activer les cookies sur votre navigateur.</p>
      <p></p>
    </TD>
  </TR>
</TABLE>