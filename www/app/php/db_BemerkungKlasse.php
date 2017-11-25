<?php
	require_once('../../../ekb/database/ClassDBC.php');

	class BemerkungListe
	{
		public function getBemerkungKlasseByStdId($stdId)
		{
			$dbc =  DBConnection :: getInstance();
			$dbc->connectDB();

			//$klasse=$this->getKlasseByStdId($stdId);
			$strQ="SELECT klasse FROM stundenplan WHERE id=".$stdId.";";
			$sqlSet = $dbc->sqlStatement($strQ);

			$row[] =$sqlSet->fetch_assoc();
			$klasse = $row[0]['klasse'];

			$strQ="SELECT bemerkung, fach, lehrerkuerzel, datum, klasse
					FROM stundenplan
					WHERE klasse = '".$klasse."' AND bemerkung IS NOT NULL
					ORDER BY datum DESC";
			$sqlSet = $dbc->sqlStatement($strQ);

			while($r=$sqlSet->fetch_assoc())
			{
				$rows[]=$r;
			}

			$dbc->close();

			return json_encode($rows, JSON_UNESCAPED_SLASHES);
		}
	}
?>
