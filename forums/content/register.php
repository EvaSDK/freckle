<?

/******************************************************************************\
 * Copyright (C) 2002 B Squared (b^2) by Josh Sherman <josh@cleancode.org>    *
\******************************************************************************/

/* Call this file directly, get sent back */
$file_name = "register.php";

/* Get the negative length of $file_name */
$file_name_length = -(strlen($file_name));

$step			= GetVars("step");
$loginesiee		= GetVars("loginesiee");
$loginfreckle	= GetVars("loginfreckle");

/* Check if the values match, if so, redirect */
if (substr($_SERVER['SCRIPT_NAME'], $file_name_length) == $file_name)
  header("Location: ../index.php");

if ( $step != 2 ) 
	$step = 1;
	
/* Make sure the user isn't feeding information via the query string, thwart all attempts!! */
if ( ($QUERY_STRING != "location=forum&pid=register" ) || 
	($QUERY_STRING != "location=forum&pid=register" && $step == 2 ) ) {
	$step=1;
    echo "<CENTER>Malformed request detected!</CENTER><BR>\n";
}
if ($step==1){
?>
<TABLE cellpadding=5 cellspacing=0 width=100% border>
  <TR> 
    <th align="center" nowrap>Enregistrement - R&egrave;glement</th>
  </TR>
  <TR> 
    <TD bgcolor=#CCCCCC> <p>Les administrateurs et mod&eacute;rateurs de ce forum 
        s'efforceront de supprimer ou &eacute;diter tous les messages &agrave; 
        caract&egrave;re r&eacute;pr&eacute;hensible aussi rapidement que possible. 
        Toutefois, il leur est impossible de passer en revue tous les messages. 
        Vous admettez donc que tous les messages post&eacute;s sur ces forums 
        expriment la vue et opinion de leurs auteurs respectifs, et non pas des 
        administrateurs, ou mod&eacute;rateurs, ou webmestres (except&eacute; 
        les messages post&eacute;s par eux-m&ecirc;me) et par cons&eacute;quent 
        ne peuvent pas &ecirc;tre tenus pour responsables. </p>
      <p>Vous consentez &agrave; ne pas poster de messages injurieux, obsc&egrave;nes, 
        vulgaires, diffamatoires, mena&ccedil;ants, sexuels ou tout autre message 
        qui violerait les lois applicables. Le faire peut vous conduire &agrave; 
        &ecirc;tre banni imm&eacute;diatement de fa&ccedil;on permanente. Vous 
        &ecirc;tes d'accord sur le fait que le webmestre, l'administrateur et 
        les mod&eacute;rateurs de ce forum ont le droit de supprimer ou &eacute;diter 
        n'importe quel sujet de discussion &agrave; tout moment. En tant qu'utilisateur, 
        vous &ecirc;tes d'accord sur le fait que toutes les informations que vous 
        donnerez ci-apr&egrave;s seront stock&eacute;es dans une base de donn&eacute;es. 
        Cependant, ces informations ne seront divulgu&eacute;es &agrave; aucune 
        tierce personne ou soci&eacute;t&eacute; sans votre accord. Le webmestre, 
        l'administrateur, et les mod&eacute;rateurs ne peuvent pas &ecirc;tre 
        tenus pour responsables si une tentative de piratage informatique conduit 
        &agrave; l'acc&egrave;s de ces donn&eacute;es.</p>
      <p>Ce forum utilise les cookies pour stocker des informations sur votre 
        ordinateur. Ces cookies ne contiendront aucune information que vous aurez 
        entr&eacute; ci-apr&egrave;s, ils servent uniquement &agrave; am&eacute;liorer 
        le confort d'utilisation. L'adresse email est uniquement utilis&eacute;e 
        afin de confirmer les d&eacute;tails de votre enregistrement ainsi que 
        votre mot de passe (et aussi pour vous envoyer un nouveau mot de passe 
        dans la cas o&ugrave; vous l'oublieriez).</p>
      <p>Il s'agit, dans le cadre de votre participation aux forums de discussions, 
        de respecter les lois en vigueur ainsi qu'un certain nombre de r&egrave;gles 
        de biens&eacute;ance et de respect d'autrui qui se sont progressivement 
        impos&eacute;es sur le r&eacute;seau : la <a href="http://netiquette.afa-france.com/">Netiquette</a>, 
        &agrave; qui la jurisprudence reconna&icirc;t une valeur juridique au 
        titres des usages vis&eacute;s par l'article 1135 du Code Civil.</p>
      <p>En vous enregistrant, vous vous portez garant du fait d'&ecirc;tre en 
        accord avec le r&egrave;glement ci-dessus.</p>      
</TD>
  </TR>
  <TR>
    <form action="index.php?location=forum&pid=register" method="post"><TD><table border="0" align="center" cellpadding="1" cellspacing="1">
      <tr>
        <td nowrap><strong>Login ESIE</strong>E (correspondant &agrave; votre adresse mail &quot;@esiee.fr&quot;) </td>
        <td align="right"> <input name="loginesiee" type="text" id="loginesiee" value="" size="10" maxlength="10"></td>
      </tr>
      <tr>
        <td nowrap><strong>Login sur le forum</strong> (de 6 &agrave; 10 lettres maximum) </td>
        <td align="right"><input name="loginfreckle" type="text" id="loginfreckle" value="" size="10" maxlength="10"></td>
      </tr>
      <tr>
        <td><input name="location" type="hidden" id="location" value="forum">
          <input name="step" type="hidden" id="step" value="2">
          <input name="pid" type="hidden" id="pid" value="register"></td>
        <td align="right"><input type="submit" name="Submit" value="Envoyer"></td>
      </tr>
    </table>    <p>La confirmation de votre inscription ainsi que votre mot de
      passe vous sera envoy&eacute;e via votre e-mail ESIEE.</p>
    <p>Si vous voulez vous inscrire avec une adresse mail n'appartenant pas au
      domaine esiee.fr, faites en la demande par <?php echo $mymail; ?></p></TD>
 </form> </TR>
</TABLE>
<?php 
}
if ($step == 2)  {
	$sql="select * from " . TABLE_PREFIX . "users where user_email='$loginesiee@esiee.fr'";
	$res = ExeSQL($sql);
	$row = mysql_fetch_object($res);
	if ( $row ) {
		echo "<center>Cet Email est deja utilisé. Si vous avez perdu votre mot de passe, demandez en un nouveau par $mymail.</center>";
	}
	else {
		$sql="select * from " . TABLE_PREFIX . "users where user_name='$loginfreckle.fr'";
		$res = ExeSQL($sql);
		$row = mysql_fetch_object($res);
		if ( $row ) {
			echo "<center>Ce nom d'utilisateur est deja utilisé. Si vous avez perdu votre mot de passe, demandez en un nouveau par $mymail. <center>";
		}
		else {
			$newpwd=uniqid("");
		   
		    $SQL = "INSERT INTO " . TABLE_PREFIX . "users (user_id, user_name, user_email, user_pass) VALUES ("
			  	 .  getUId() . ", '$loginfreckle', '$loginesiee@esiee.fr', '$newpwd')";
            $res = ExeSQL($SQL);
			mail( "$loginesiee@esiee.fr" , "Inscription à FRECKLE" , "Vous etes bien inscrit à freckle \n Votre login : $loginfreckle \n Votre password : $newpwd \n\n A bientot sur le site ... \n\n Adresse du site : http://www.esiee.fr/~freckle \n Mail : freckle@esiee.fr"  );
			echo "<center>Votre inscription s'est bien deroulé, vous allez recevoir votre password par mail dans quelques minutes...<br> Votre mail : $loginesiee@esiee.fr <br> Votre login de forum : $loginfreckle</center>";
		}
		
	}
}
?>