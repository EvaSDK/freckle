  <ul>
  <li><h2>G�n�ral</h2>
  <ul>
  <li><a href="index.php">Accueil</a></li>
  <li><a href="documents.php">Documents</a></li>
  <li><a href="display.php?what=site">Autres sites ESIEE</a></li>
  <li><a href="tools.php">Outils et recommandations</a></li>
  </ul></li>
  <li><h2>News</h2>
  <ul><li>
  <?php display_news( "short", $conn ); ?></li>
  <li><a href="display.php?what=news">En savoir plus</a></li>
  </ul></li>
  <li><h2>Statistiques</h2>
  <ul class="pop">
  <li>Visiteurs:      <?php visiteurs();          ?></li>
  <li>Pages Affich�es: <?php viewed_pages();        ?></li>
  <li>Documents:      <?php registred_documents(); ?></li>
  <li>Sites List�s:    <?php registred_sites();    ?></li>
  </ul></li>
  <li><h2>Webmaster</h2>
  <ul class="pop">
  <li><a href="./_admin/auth.php?what=login">Administration</a></li>
  <li><a href="http://www.esiee.fr">H�bergement</a></li>
  <li><a href="http://piartt.free.fr">Mainteneur Pr�c�dent</a></li>
  </ul></li>
  </ul>
