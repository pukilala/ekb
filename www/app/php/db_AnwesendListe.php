<?php
	/**
	*Stundenplan Lehrer und
	*Anwesenheitsstatus einer Klasse
	*Rügabewert: Json Array
	*/

	require_once(__DIR__.'/../../../ekb/database/ClassDBC.php');

	class SchuelerListe
	{
		public function getStdPlanTagLehrer($lehrer,$datum)
		{
			$dbc =  DBConnection :: getInstance();
			$dbc->connectDB();

			$strQ="SELECT DISTINCT id, klasse, fach, von, bis, (bis-von+1)*45 AS dauer
			FROM stundenplan
			INNER JOIN klasse
			ON stundenplan.klasse = klasse.klassenname
			WHERE (lehrerkuerzel = '".$lehrer."' OR klasse.klassenlehrer = '".$lehrer."')  AND datum ='".$datum."';";
			$sqlSet = $dbc->sqlStatement($strQ);

			$i = 0;

			while($r = $sqlSet->fetch_assoc())
			{
     				$rows[] = $r;
				$rows[$i]["item"] = $i;
				$i = $i+1;
			}
			$dbc->close();
			//return $strQ;
			return json_encode($rows, JSON_UNESCAPED_SLASHES);

		}

		public function getKlassseById($stdId)
		{
			$dbc =  DBConnection :: getInstance();
			$dbc->connectDB();

			$strQ="SELECT schueler.matnr, schueler.vorname, schueler.nachname, schueler.klasse,
			stundenplan.fach, stundenplan.lehrerkuerzel, stundenplan.von, stundenplan.bis,
			day(stundenplan.datum) as Tag, month(stundenplan.datum) as Monat, year(stundenplan.datum) as Jahr,
				anwesenheit.anwesend, anwesenheit.verspaetet,
				anwesenheit.fehlminuten, image, stundenplan.id

				FROM schueler INNER JOIN anwesenheit
				ON schueler.matnr = anwesenheit.matnr
				INNER JOIN stundenplan
				ON anwesenheit.unterrichtstunde=stundenplan.id
				WHERE stundenplan.id='$stdId'
				AND active=1
				ORDER BY 3;";

				$sqlSet = $dbc->sqlStatement($strQ);

				$i = 0;
				while($r = $sqlSet->fetch_assoc())
				{
     				$rows[] = $r;
					if($rows[$i]['anwesend']==3)
					{
  		 				$rows[$i]['anwesend']='1';
					}
					//$tmp=$rows[$i]['image'];
					//$rows[$i]['image']="'../../img/".$tmp."'";
					$i++;
				}
			$dbc->close();


			return json_encode($rows, JSON_UNESCAPED_SLASHES);
		}

		public function getKlasseByName($klasse)
		{
			$dbc =  DBConnection :: getInstance();
			$dbc->connectDB();

			$strQ = "SELECT DISTINCT schueler.matnr,
											schueler.vorname,
											schueler.nachname,
											schueler.image,
											schueler.active,
											schueler.geburtsdatum,
											schueler.attestpflicht,
											schueler.Religion,
											schueler.eingeschult,
											schueler.ausgeschult
							FROM schueler
							WHERE (schueler.klasse = '$klasse')
							OR schueler.matnr = 0
							ORDER BY 3;";

				$sqlSet = $dbc->sqlStatement($strQ);

				while($r=$sqlSet->fetch_assoc())
				{
						$row[]=$r;
				}

				$dbc->close();
				return json_encode($row);
		}

	}
?>
