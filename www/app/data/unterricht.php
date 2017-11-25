<?php

	/*
	*	Lädt die Schülerliste einer Klasse, deren Anwesenheit eingetragen werden soll.
	*/

	session_start();
	
	require_once('../php/db_UnterrichtKlasse.php');

	$aktKlasse = new UnterrichtListe();
	$aktUnterricht = file_get_contents("php://input");
	$aktStd	= json_decode($aktUnterricht,true);

	if((strpos($aktStd['stdId'],"current_")!==false) )
	{
		$unterrichtListe = json_encode(["vorname"=>"Fehler:"]);
	}
	else
	{
		$unterrichtListe = $aktKlasse->getUnterrichtKlasseByStdId($aktStd['stdId']);
	}
	echo $unterrichtListe;
?>
