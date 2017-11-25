<?php
	/*
	* Updaten der Anwesemheitsliste
	*
	*/
	session_start();
	require_once(__DIR__.'/../../../ekb/database/ClassDBC.php');

	$data = file_get_contents("php://input");
	$data=substr($data,10,-1); //Problem mit json
	$arr=json_decode($data,true);


	$dbc =  DBConnection :: getInstance();
	$dbc->connectDB();

	//klasse Datum bestimmen
	$strQ="SELECT datum, klasse FROM stundenplan WHERE id=".$arr[0]['id'].";";

	$sqlSet=$dbc->sqlStatement($strQ);
	$stdInfo = array();
	$stdInfo=$sqlSet->fetch_assoc();

	//Quartal
	$strQ="SELECT id FROM quartal WHERE anfang < '".$stdInfo['datum']."' AND ende >'".$stdInfo['datum']."';";
	$sqlSet=$dbc->sqlStatement($strQ);
	$quartal=$sqlSet->fetch_assoc();

	//nachfolgende Stunden in der Klasse
	$strQ = "SELECT id
					from stundenplan
					where klasse='".$stdInfo['klasse']."'
					AND datum = '".$stdInfo['datum']."'
					AND id >".$arr[0]['id'].";";

	$sqlSet=$dbc->sqlStatement($strQ);

	while($r=$sqlSet->fetch_assoc())
	{
		$idFolgeStunde[]=$r;
	}

	$minuten = $arr[0]['bis']-$arr[0]['von'];


	$i=0;

	for($i;$i<count($arr);$i=$i+1)
	{
		if ($arr[$i]['anwesend']==0 )
		{
			if ($minuten>0)
			{
				$arr[$i]['fehlminuten'] = 90;
			}
			else {
				$arr[$i]['fehlminuten'] = 45;
			}
		}

		if ($arr[$i]['anwesend']==1 && ($arr[$i]['fehlminuten']!=0))// and $arr[$i]['fehlminuten']!=90) )
		{
			$arr[$i]['verspaetet']=1;
		}
		else
		{
			$arr[$i]['verspaetet']=0;
		}

		if ($arr[$i]['fehlminuten']!=0)
		{
			$entschuldigt=1;
		}
		else
		{
		  $entschuldigt=0;
		}

		$strQ = "UPDATE anwesenheit SET
			anwesend = ".$arr[$i]['anwesend'].",
			verspaetet = ".$arr[$i]['verspaetet'].",
			fehlminuten = ".$arr[$i]['fehlminuten'].",
			quartal = ".$quartal['id'].",
			entschuldigt = ".$entschuldigt.",
			lehrerkuerzel='".$_SESSION['bntzr']."'
			WHERE matnr=".$arr[$i]['matnr']."
			AND unterrichtstunde = ".$arr[$i]['id'].";";

		$dbc->sqlStatement($strQ);
		$j=0;
		for($j;$j<count($idFolgeStunde);$j=$j+1)
		{
			$strQ = "UPDATE anwesenheit SET
				anwesend = ".$arr[$i]['anwesend']."
				WHERE matnr=".$arr[$i]['matnr']."
				AND unterrichtstunde = ".$idFolgeStunde[$j]['id']."
				AND lehrerkuerzel='';";
				$dbc->sqlStatement($strQ);
		}
	}
	$dbc->close();
?>
