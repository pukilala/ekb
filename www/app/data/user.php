<?php
	$benutzer = json_decode(file_get_contents("php://input")); //erhalte daten vom LoginService


	require_once(__DIR__.'/../../../ekb/database/ClassDBC.php');
	$dbc =  DBConnection :: getInstance();
  $dbc->connectDB();

	$aktUser=$dbc->saveInput($benutzer->bntzr);


  $strQ="SELECT * FROM user WHERE uname = '".$aktUser."';";

	$result=$dbc->sqlStatement($strQ);
  $dbc->close();


	$r=$result->fetch_array();


	$isValid = password_verify($benutzer->pass.$r[1],$r[0]);



	if($isValid)
	{
		session_start();
		$_SESSION['uid']=uniqid();
		$_SESSION['bntzr']=$benutzer->bntzr;

		$_SESSION['timeout']=time();
	}
?>
