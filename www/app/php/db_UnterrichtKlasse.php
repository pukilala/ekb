<?php
	require_once('../../../ekb/database/ClassDBC.php');

	class UnterrichtListe
	{
		public function getUnterrichtKlasseByStdId($stdId)
		{
			$dbc =  DBConnection :: getInstance();
			$dbc->connectDB();

			//$klasse=$this->getKlasseByStdId($stdId);
			$strQ="SELECT klasse, lehrerkuerzel, fach FROM stundenplan WHERE id=".$stdId.";";
			$sqlSet = $dbc->sqlStatement($strQ);

			$row[] =$sqlSet->fetch_assoc();
			$klasse = $row[0]['klasse'];
			$fach = $row[0]['fach'];

			$strQ="SELECT thema, aufgaben, fach, lehrerkuerzel, datum
					FROM stundenplan
					WHERE klasse = '".$klasse."'
						AND fach = '".$fach."'
					ORDER BY datum DESC";
			$sqlSet = $dbc->sqlStatement($strQ);

			$i=0;
			while($r=$sqlSet->fetch_assoc())
			{
				$rows[]=$r;
				if($rows[i]["aufgaben"]==NULL)
				{
					$rows[i]["aufgaben"]="keine";
				}
				$i++;
			}

			$dbc->close();
			return json_encode($rows, JSON_UNESCAPED_SLASHES);
		}
	}
?>
