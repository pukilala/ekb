<?php

	//Lädt den Stundenplan eines Lehrers für einen bestimmten Tag.

	session_start();

	require_once(__DIR__.'/../php/db_AnwesendListe.php');
	//require_once('../php/ClassDBC.php');

	$datum=file_get_contents("php://input");

	$datum=substr($datum,10,10);

	$date = new DateTime($datum);
	//$date->modify("+24 hours");
	$datum = $date->format("Y-m-d");

	/*----------------Datum Auswerten-------------------------*/
	$aktKlasse = new SchuelerListe();
	$stdPlan =  $aktKlasse->getStdPlanTagLehrer($_SESSION['bntzr'],$datum);


	echo $stdPlan;

		//Testfile
		//$file = 'test.txt';
		//file_put_contents($file,$stdPlan);
		//file_put_contents($file,$datum);

?>
