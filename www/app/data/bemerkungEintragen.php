<?php
	/*
	* Updaten der Anwesemheitsliste
	*
	*/
	session_start();

	require_once('../../../ekb/database/ClassDBC.php');

	$data = file_get_contents("php://input");
	//$data=substr($data,10,-1); //Problem mit json

	$dbc =  DBConnection :: getInstance();
	$dbc->connectDB();

	$arr=json_decode($data,true);

	$txt= $dbc->saveInput($arr['Bemerkung']);
	echo $txt;
	$strQ = "UPDATE stundenplan
			SET bemerkung = '".$txt."'
			WHERE id=".$arr['Id'].";";
	if($arr["Bemerkung"]!="")
	{

		$dbc->sqlStatement($strQ);
		$dbc->close();
	}

	//Testfile
	$file = 'test.txt';
	file_put_contents($file,$txt);
	echo $bemerkungListe;
?>
