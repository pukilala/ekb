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

	$txtH=$dbc->saveInput($arr['Hausaufgaben']);
	$txtU=$dbc->saveInput($arr['Unterricht']);

	$strQ = "UPDATE stundenplan
			SET thema = '".$txtU."', aufgaben ='".$txtH."'
			WHERE id=".$arr['Id'].";";

	if($arr["Unterricht"]!="")
	{
		$dbc->sqlStatement($strQ);
		$dbc->close();
	}
?>
