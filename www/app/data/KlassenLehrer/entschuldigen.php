<?php
	/*
	* Updaten der Anwesemheitsliste
	*
	*/
	session_start();

  require_once(__DIR__.'/../../../../ekb/database/ClassDBC.php');
  $dbc =  DBConnection :: getInstance();
	$dbc->connectDB();


	$input = file_get_contents("php://input");
	$input =substr($input,17,-1);
	$arr=json_decode($input,true);

  $i=0;
  for($i;$i<count($arr);$i=$i+1)
  {
    $strQ = "UPDATE anwesenheit
          SET entschuldigt = ".$arr[$i]['entschuldigt']."
          WHERE matnr = ".$arr[$i]['matnr']."
          AND unterrichtstunde = ".$arr[$i]['id'].";";

    $sqlSet=$dbc->sqlStatement($strQ);
  }

  $dbc->close();
?>
