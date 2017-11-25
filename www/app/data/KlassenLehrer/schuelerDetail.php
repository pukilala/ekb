<?php

	/*
	*	Lädt die Schülerliste einer Klasse, deren Anwesenheit eingetragen werden soll.
	*/

	session_start();
	
	require_once(__DIR__.'/../../php/db_SchuelerDetail.php');


	$aktSchueler = file_get_contents("php://input");
	$aktSlr	= json_decode($aktSchueler,true);

	//echo $aktSlr['schuelerId'];
	$schueler = new SchuelerDaten();

	$daten = array();
	$daten = $schueler->getSchuelerDaten($aktSlr['schuelerId']);
	$klassen = $schueler->getKlassen();

  /*$fehlstunden_Fach =
		 $schueler->fehlstundenFachQuartal($aktSlr['schuelerId'],$aktSlr['stdId']);
	$verspaetung_Fach =
		$schueler->verspaethungenFachQuartal($aktSlr['schuelerId']);

	/*$stunden_Fach = $schueler->getGesamtStundenFach($aktSlr['schuelerId']);
	$fehlstunden_Fach_Ent = $schueler->getStundenEnstschuldigtFach($aktSlr['schuelerId']);
	$verspaetung_Fach_Ent = $schueler->getVerspaetungEntschuldigtFach($aktSlr['schuelerId']);

	$fehlzeiten_Detail_Fach = $schueler->getFehlzeitenDetailFach($aktSlr['schuelerId']);

	$tmp = round(
		100*($fehlstunden_Fach['fehlstd_fach']*45+$verspaetung_Fach['versp_fach'])
		/($stunden_Fach['gesamt_std']*45),2);

	$fehlzeit_Fach_Prozent = array("fehlzeit_prozent"=>(string)$tmp);*/

	$detail = array_merge($daten,$klassen);
						 /* $fehlstunden_Fach,
						  $fehlstunden_Fach_Ent,
						  $verspaetung_Fach,
						  $verspaetung_Fach_Ent,
						  $stunden_Fach,
						  $fehlzeit_Fach_Prozent,
						  $fehlzeiten_Detail_Fach
						);*/



	echo json_encode($detail,JSON_UNESCAPED_SLASHES);

?>
