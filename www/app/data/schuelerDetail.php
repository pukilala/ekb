<?php

	/*
	*	Lädt die Schülerliste einer Klasse, deren Anwesenheit eingetragen werden soll.
	*/

	session_start();
	
	require_once(__DIR__.'/../php/db_SchuelerDetail.php');


	$aktSchueler = file_get_contents("php://input");
	$aktSlr	= json_decode($aktSchueler,true);

	//$aktSlr['schuelerId'] = 100158;
	//$aktSlr['fach']="WL";

	$schueler = new SchuelerDaten();

	$daten = array();
	$daten = $schueler->getSchuelerDaten($aktSlr['schuelerId']);
//------------------------Fachspezifische Fehlzeiten
 $fehlstunden_fach =
		 		$schueler->fehlstundenFach($aktSlr['schuelerId'],$aktSlr['fach']);

	$verspaetung_fach =
					$schueler->verspaethungenFach($aktSlr['schuelerId'],$aktSlr['fach']);

	//erteilte Unterrichtstunden
	$std_fach =
					$schueler->stundenFach($aktSlr['schuelerId'],$aktSlr['fach']);

	$std_entschuldigt_fach =
					$schueler->stdEnstschuldigtFach($aktSlr['schuelerId'],$aktSlr['fach']);

	$verspaetung_entschuldigt_fach = $schueler->verspaetungEntschuldigtFach($aktSlr['schuelerId'],$aktSlr['fach']);

// Gesamtfehlzeit berechnen floor(Minuten/60) + (Minuten%60)/100 + Stunden
	$gesamt_Fehlzeit_Fach["Fehlzeit_Fach_gesamt"] = floor($verspaetung_fach["versp_fach"]/60) + ($verspaetung_fach["versp_fach"]%60)/100 + $fehlstunden_fach["fehlstd_fach"];

if($std_fach["gesamt_std"]!=0)
	$prozentual_Fehlzeiten_Fach["Fehlzeiten_Fach_prozentual"]=round($gesamt_Fehlzeit_Fach["Fehlzeit_Fach_gesamt"]/$std_fach["gesamt_std"]*100,2);
else {
		$prozentual_Fehlzeiten_Fach["Fehlzeiten_Fach_prozentual"]=0;
	}


//---------------------fehlzeiten Gesamt
	$fehlstunden_gesamt = $schueler->fehlstundenGesamt($aktSlr['schuelerId']);
	$fehlstunden_gesamt_Entschuldigt = $schueler->fehlstundenGesamtEntschuldigt($aktSlr['schuelerId']);
	$verspaetung_gesamt = $schueler->verspaetungGesamt($aktSlr['schuelerId']);
	$verspaetung_Entschuldigt = $schueler->verspaetungEntschuldigt($aktSlr['schuelerId']);
	$fehlzeit_Gesamt["Fehlzeit_gesamt"] = $fehlstunden_gesamt["fehlstunden"] + floor($verspaetung_gesamt["fehlmintuen_ver"]/60) + ($verspaetung_gesamt["fehlmintuen_ver"]%60)/100;
	$erteilte_Stunden = $schueler->unterrichtGesamt($aktSlr['schuelerId']);
	$prozentual_Fehlzeit["Fehlzeiten_prozentual"] =round($fehlzeit_Gesamt["Fehlzeit_gesamt"]/$erteilte_Stunden["gesamt"]*100,2);

// Fehlstunden Anzeigen Entschuldigen
	$isKlassenLehrer = $schueler->getKlassenlehrer($aktSlr['stdId'],$_SESSION['bntzr']);
	$FehlzeitArray = $schueler->entschuldigen($aktSlr['schuelerId']);
	$fz = array( "fzt" => $FehlzeitArray );

	/*
	$fehlstundenOhne_20Tage = $schueler->fehlzeitenUnentschuldigt20Tage($aktSlr['schuelerId']);

	$isKlassenLehrer = $schueler->getKlassenlehrer($aktSlr['stdId'],$_SESSION['bntzr']);


	$FehlzeitArray = $schueler->entschuldigen($aktSlr['schuelerId']);

	$fz = array( "fzt" => $FehlzeitArray );
	/*
		Extra Darstellung
		$fehlzeiten_Detail_Fach = $schueler->getFehlzeitenDetailFach($aktSlr['schuelerId']);
	*/



	$detail = array_merge($daten,
												$fehlstunden_fach,
												$verspaetung_fach,
												$std_entschuldigt_fach,
												$verspaetung_entschuldigt_fach,
												$gesamt_Fehlzeit_Fach,
												$prozentual_Fehlzeiten_Fach,
												$fehlstunden_gesamt,
												$fehlstunden_gesamt_Entschuldigt,
												$verspaetung_gesamt,
												$verspaetung_Entschuldigt,
												$fehlzeit_Gesamt,
												$prozentual_Fehlzeit,
												$isKlassenLehrer,
												$fz
												);/*,



												$fehlstunden_gesamt,
												$verspaetung_gesamt,
												$fehlstundenOhne_20Tage,

											);*/
//var_dump($detail);
	echo json_encode($detail,JSON_UNESCAPED_SLASHES);
?>
