<?php
	/*
	*		1. Aus der .txt werden alle benötigten Klassennamen ausgelesen.
	*   	2. Alle Schülernamen werden aus klassenname.csv ausgelesen und in die Datenbank eingetragen.
	*/

	require_once(__DIR__.'/../../../ekb/database/ClassDBC.php');
	//$handleKL = fopen("/home/ekbadmin/ekb/alleKlassen.txt","r");
	$dbc = new DBConnection();
	$dbc->connectDB();

	while($inhalt = fgets($handleKL,4096))
	{

		$inhalt = trim($inhalt);
		$inhalt = $inhalt.".csv";
		echo $inhalt;
		if (($handle = fopen("/home/ekbadmin/ekb/csv/".$inhalt, "r")) === FALSE)
		{
			die('Error opening file');
		}
			else
		{
	 		$headers = fgetcsv($handle, 1024, ',');
			while ($row = fgetcsv($handle, 1024, ","))
			{
				$strQ="INSERT INTO schueler (matnr, vorname, nachname, klasse, image)
				VALUES('".$row[2]."','".$row[1]."','".$row[0]."','".$row[3]."','30.png');";
				$dbc->sqlStatement($strQ);
			}
		}
		fclose($handle);
	}

?>
