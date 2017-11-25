<?php

	/*
	*	Lädt die Schülerliste einer Klasse, deren Anwesenheit eingetragen werden soll.
	*/

	session_start();
	require_once('../php/db_BemerkungKlasse.php');

	$aktKlasse = new BemerkungListe();
	$aktUnterricht = file_get_contents("php://input");
	$aktStd	= json_decode($aktUnterricht,true);

	if((strpos($aktStd['stdId'],"current_")!==false) )
	{
		$bemerkungListe = json_encode(["vorname"=>"Fehler:"]);
	}
	else
	{
		$bemerkungListe = $aktKlasse->getBemerkungKlasseByStdId($aktStd['stdId']);
	}


	//Testfile
	//$file = 'test.txt';
	//file_put_contents($file,$bemerkungListe);
	echo $bemerkungListe;
?>
