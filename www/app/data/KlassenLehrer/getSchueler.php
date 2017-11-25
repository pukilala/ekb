<?php
  session_start();
  
  require_once(__DIR__.'/../../php/db_AnwesendListe.php');

  $aktKlasse = new SchuelerListe();
  $input = file_get_contents("php://input");
  $klasse	= json_decode($input,true);

  $schuelerListe=$aktKlasse->getKlasseByName($klasse['name']);

  echo $schuelerListe;

?>
