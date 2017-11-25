<?php

	/*
	*	Lädt die Schülerliste einer Klasse, deren Anwesenheit eingetragen werden soll.
	*/

	session_start();


	require_once(__DIR__.'/../../../../ekb/database/ClassDBC.php');

	$input = file_get_contents("php://input");
	$input =substr($input,12,-1);



	$aktSchuelerDaten	= json_decode($input,true);

	$dbc =  DBConnection :: getInstance();
	$dbc->connectDB();

 	$matnr=$aktSchuelerDaten['matnr'];

	if($matnr=="0")
	{
		$date = new DateTime($aktSchuelerDaten['geburtsdatum']);
		$date->modify("+24 hours");
		$aktSchuelerDaten['geburtsdatum'] = $date->format("Y-m-d");

		$date = new DateTime($aktSchuelerDaten['eingeschult']);
		$date->modify("+24 hours");
		$aktSchuelerDaten['eingeschult'] = $date->format("Y-m-d");

		$strQ = "SELECT max(matnr) as matnr from schueler;";
		$result = $dbc->sqlStatement($strQ);
		$new=$result->fetch_assoc();
		$matnr=$new['matnr']+1;
		$strQ = "INSERT INTO schueler (matnr,vorname,nachname,klasse,image) VALUES($matnr,'','','','30.png');";
		$dbc->sqlStatement($strQ);
	}

		$vorname = $dbc->saveInput($aktSchuelerDaten['vorname']);
		$nachname = $dbc->saveInput($aktSchuelerDaten['nachname']);
		//existierender Schueler bearbeiten
		//save input fehlt

		$strQ="UPDATE schueler SET
		vorname = '$vorname',
		nachname = '$nachname',
		klasse = UPPER('".$aktSchuelerDaten['klasse']."'),
		active = ".$aktSchuelerDaten['active'].",
		Religion = ".$aktSchuelerDaten['Religion'].",
		geburtsdatum = '".$aktSchuelerDaten['geburtsdatum']."',
		attestpflicht = '".$aktSchuelerDaten['attestpflicht']."',
		eingeschult = '".$aktSchuelerDaten['eingeschult']."',
		ausgeschult = '".$aktSchuelerDaten['ausgeschult']."'
		WHERE matnr = $matnr;";
		$dbc->sqlStatement($strQ);

	$dbc->close();


?>
