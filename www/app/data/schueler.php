<?php

	/*
	*	Lädt die Schülerliste einer Klasse, deren Anwesenheit eingetragen werden soll.
	*/

	session_start();
	
	require_once(__DIR__.'/../php/db_AnwesendListe.php');

	$aktKlasse = new SchuelerListe();
	$aktUnterricht = file_get_contents("php://input");
	$aktStd	= json_decode($aktUnterricht,true); //interne Stundendentifikationsnummer $aktStd["item"]

	//$aktStd = 1253;
	//var_dump($aktStd);

	if (strpos($aktStd['stdId'],"current_")!==false)  //Aktueller Unterricht? oder Nachtragen
	{
		//aktuelle Anwesenheit eintragen
		$datum = date("Y-m-d");
		echo $datum;
		//lese Tagesaktuellen Stundnenplan
		$stdPlan =  $aktKlasse->getStdPlanTagLehrer($_SESSION['bntzr'],$datum);
		//vardump*()
		$std = json_decode($stdplan,true); //assoziatives Array
		/* Erhalte von der DB in $stunde gespeichert:

			[{"id":"210","klasse":"AIT30V","fach":"DB","von":"5","bis":"6","item":0},
			{"id":"213","klasse":"AIT21V","fach":"PGR","von":"3","bis":"4","item":1}]

		*/
		// Aktuellen Unterricht berechnen
		if(date('N')==6) // Samstag ?
		{
			$von = [
				"1"=>date('H:i',mktime(7,40)),
				"3"=>date('H:i',mktime(9,20)),
				"5"=>date('H:i',mktime(11,00)),
			];

			$bis = [
				"1"=>date('H:i',mktime(9,15)),
				"2"=>date('H:i',mktime(10,55)),
				"3"=>date('H:i',mktime(12,35)),
			];
		}
		else
		{
			$von = [
				"1"=>date('H:i',mktime(7,40)),
				"2"=>date('H:i',mktime(8,30)),
				"3"=>date('H:i',mktime(9,35)),
				"4"=>date('H:i',mktime(10,25)),
				"5"=>date('H:i',mktime(11,25)),
				"6"=>date('H:i',mktime(12,15)),
				"7"=>date('H:i',mktime(13,10)),
				"8"=>date('H:i',mktime(14,00)),
				"9"=>date('H:i',mktime(14,55)),
				"10"=>date('H:i',mktime(15,45)),
				"11"=>date('H:i',mktime(16,55)),
				"12"=>date('H:i',mktime(17,45)),
				"13"=>date('H:i',mktime(18,35)),
				"14"=>date('H:i',mktime(19,20)),
				"15"=>date('H:i',mktime(20,15)),
				"16"=>date('H:i',mktime(21,00)),
			]; //Unteriicht Startzeiten

			$bis = [
				"1"=>date('H:i',mktime(8,30)),
				"2"=>date('H:i',mktime(9,20)),
				"3"=>date('H:i',mktime(10,25)),
				"4"=>date('H:i',mktime(11,15)),
				"5"=>date('H:i',mktime(12,15)),
				"6"=>date('H:i',mktime(13,05)),
				"7"=>date('H:i',mktime(14,00)),
				"8"=>date('H:i',mktime(14,50)),
				"9"=>date('H:i',mktime(15,45)),
				"10"=>date('H:i',mktime(16,35)),
				"11"=>date('H:i',mktime(17,45)),
				"12"=>date('H:i',mktime(18,30)),
				"13"=>date('H:i',mktime(19,25)),
				"14"=>date('H:i',mktime(20,15)),
				"15"=>date('H:i',mktime(21,05)),
				"16"=>date('H:i',mktime(21,45)),
			]; //Unterricht Endzeiten
		}
		$anzKlassen = -2;
		var_dump($stdplan);
		foreach($stdPlan as &$std)
		{
			$uhrzeit = date("H:i");
			echo $uhrzeit;
			if($von["'".$std['von']."'"] >= $uhrzeit && $bis["'".$std['bis']."'"] <= $uhrzeit)
			{
				$akt=$std["id"];
				$anzKlassen++;
			}
		}
		echo $anzKlassen;

		if ($anzKlassen == 1)
		{
			$schuelerListe = $aktKlasse->getKlassseById($akt['id']);
		}
		elseif ($anzKlassen == 0)
		{
			$schuelerListe = json_encode(["vorname"=>"Fehler: Laut Stundenplan sind Sie im Moment für keinen Unterricht eingeplant.
			 Bitte Verwenden Sie den Zugang  Home -> Anwesenheit Nachtragen um ihren Unterricht im Klassenbuch einzutragen."]);
			$schuelerListe="[".$schuelerListe."]";
		}
		elseif ($anzKlassen >= 2)
		{
			$schuelerListe = json_encode(["vorname"=>"Fehler: Laut Stundenplan werden Sie im Moment in mehr als einer Klasse eingesetzt.
			Bitte verwenden sie den Zugang  Home -> Anwesenheit Nachtragen um ihren Unterricht im Klassenbuch einzutragen."]);
			$schuelerListe="[".$schuelerListe."]";
		}
		else
		{
			$schuelerListe = json_encode(["vorname"=>"\t\n\r Ein unbekannter Fehler ist aufgetreten:
			Bitte verwenden Sie den Zugang Home -> Anwesenheit Nachtragen um ihren Unterricht im Klassenbuch einzutragen."]);
			$schuelerListe="[".$schuelerListe."]";
		}
	}
	else
	{
		//nachtragen
		$schuelerListe = $aktKlasse->getKlassseById($aktStd['stdId']);

	}
	echo $schuelerListe;
?>
