<?php
	session_start();
	$inactive = 120;
	$livetime = time() - $_SESSION['timeout'];
	if(isset($_SESSION['uid']) && $livetime<$inactive )
	{
		$_SESSION['timeout']=time();
		echo $_SESSION['bntzr'];
	}
?>
