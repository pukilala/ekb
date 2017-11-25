<?php
	require_once(__DIR__.'/../../../ekb/database/ClassDBC.php');

	class SchuelerDaten
	{
		private $fach;
		private $quartal;

		public function getSchuelerDaten($schuelerId)
		{
			$dbc =  DBConnection :: getInstance();
			$dbc->connectDB();

			$strQ = "SELECT id FROM quartal WHERE anfang<=CURDATE() AND ende>=CURDATE();";
			$sqlSet = $dbc->sqlStatement($strQ);
			$r = $sqlSet->fetch_assoc();
			$this->quartal = $r['id'];



			$strQ = "SELECT matnr, vorname, nachname, klasse, geburtsdatum,
			datediff(now(),geburtsdatum)/365 AS voll, image,
			attestpflicht, active, Religion, eingeschult, ausgeschult
			FROM schueler
			WHERE matnr=".$schuelerId.";";

			$sqlSet = $dbc->sqlStatement($strQ);

			$i=0;
			$r=$sqlSet->fetch_assoc();


			if($r['voll']==null)
			{
				$r['voll']="unbekannt";
			}
			else if($r['voll']<18)
			{
				$r['voll']="nein";
			}
			else
			{
				$r['voll']="ja";
			}

			$dbc->close();
			return $r;//json_encode($r,JSON_UNESCAPED_SLASHES);

		}

		public function fehlstundenFach($schuelerId,$fach)
		{
			$dbc =  DBConnection :: getInstance();
			$dbc->connectDB();

			$strQ = "SELECT floor(sum(fehlminuten)/45) AS fehlstd_fach
					FROM anwesenheit
					INNER JOIN stundenplan
					ON anwesenheit.unterrichtstunde = stundenplan.id
					INNER JOIN quartal
					ON quartal.id = anwesenheit.quartal
					WHERE matnr = ".$schuelerId."
					AND stundenplan.fach = '".$fach."'
					AND anwesend = 0 
					AND quartal=".$this->quartal.";";
			$sqlSet = $dbc->sqlStatement($strQ);

			$dbc->close();

			if($sqlSet != NULL){
				$r = $sqlSet->fetch_assoc();
				if($r["fehlstd_fach"]=="")
				{
					$r["fehlstd_fach"]=0;
				}
				return $r;
			}
			else
			{
				$r = array("fehlstd_fach"=>0);
				return $r;
			}
		}

		public function stdEnstschuldigtFach($schuelerId,$fach)
		{
			$dbc =  DBConnection :: getInstance();
			$dbc->connectDB();

			$strQ = "SELECT floor(sum(fehlminuten)/45) AS std_entschuldigt_fach
					FROM anwesenheit
					INNER JOIN stundenplan
					ON anwesenheit.unterrichtstunde = stundenplan.id
					INNER JOIN quartal
					ON quartal.id = anwesenheit.quartal
					WHERE matnr = ".$schuelerId."
					AND stundenplan.fach = '".$fach."'
					AND verspaetet = 0
					AND entschuldigt > 1
					AND quartal=".$this->quartal.";";

			$sqlSet = $dbc->sqlStatement($strQ);
			$dbc->close();

			if($sqlSet != NULL){
				$r = $sqlSet->fetch_assoc();
				if($r["std_entschuldigt_fach"]=="")
				{
					$r["std_entschuldigt_fach"]=0;
				}
				return $r;
			}
			else
			{
				$r = array("std_entschuldigt_fach"=>0);
				return $r;
			}


		}

		public function verspaethungenFach($schuelerId,$fach)
		{
			$dbc =  DBConnection :: getInstance();
			$dbc->connectDB();

			$strQ = "SELECT sum(fehlminuten) AS versp_fach, count(fehlminuten) AS anz_versp
					FROM anwesenheit
					INNER JOIN stundenplan
					ON anwesenheit.unterrichtstunde = stundenplan.id
					INNER JOIN quartal
					ON quartal.id = anwesenheit.quartal
					WHERE matnr = ".$schuelerId."
					AND stundenplan.fach = '".$fach."'
					AND verspaetet = 1
					AND quartal=".$this->quartal.";";
			$sqlSet = $dbc->sqlStatement($strQ);
			$dbc->close();

				$r = $sqlSet->fetch_assoc();

			if($r["anz_versp"]==0)
			{
				$r["versp_fach"] = 0;
			}
			return $r;
		}

		public function verspaetungEntschuldigtFach($schuelerId,$fach)
		{
			$dbc =  DBConnection :: getInstance();
			$dbc->connectDB();

			$strQ = "SELECT floor(sum(fehlminuten)) AS ver_entschuldigt_fach,
								count(fehlminuten) AS anz_versp_Ent
					FROM anwesenheit
					INNER JOIN stundenplan
					ON anwesenheit.unterrichtstunde = stundenplan.id
					INNER JOIN quartal
					ON quartal.id = anwesenheit.quartal
					WHERE matnr = ".$schuelerId."
					AND stundenplan.fach = '".$fach."'
					AND verspaetet = 1
					AND entschuldigt > 1
					AND quartal=".$this->quartal.";";

			$sqlSet = $dbc->sqlStatement($strQ);
			$dbc->close();

			if($sqlSet != NULL){
				$r = $sqlSet->fetch_assoc();
				if($r["ver_entschuldigt_fach"]=="")
				{
					$r["ver_entschuldigt_fach"]=0;
				}
				return $r;
			}
			else
			{
				$r["ver_entschuldigt_fach"]=0;
				return $r;
			}
		}

		public function stundenFach($schuelerId,$fach)
		{
			$dbc =  DBConnection :: getInstance();
			$dbc->connectDB();

			$strQ = "SELECT SUM(bis-von+1) AS gesamt_std
					FROM anwesenheit
					INNER JOIN stundenplan
					ON anwesenheit.unterrichtstunde = stundenplan.id
					INNER JOIN quartal
					ON quartal.id = anwesenheit.quartal
					WHERE matnr = ".$schuelerId."
					AND stundenplan.fach = '".$fach."'
					AND quartal=".$this->quartal.";";

			$sqlSet = $dbc->sqlStatement($strQ);
			$dbc->close();

			if($sqlSet != NULL){
				$r = $sqlSet->fetch_assoc();
				return $r;
			}
			else
			{
				$r = array("gesamt_std"=>0);
				return $r;
			}
		}

		public function fehlzeitenDetailFach($schuelerId)
		{
			$dbc =  DBConnection :: getInstance();
			$dbc->connectDB();

			$strQ = "SELECT DISTINCT fehlminuten, thema, datum, entschuldigt
					FROM anwesenheit
					INNER JOIN stundenplan
					ON anwesenheit.unterrichtstunde = stundenplan.id
					INNER JOIN quartal
					ON quartal.id = anwesenheit.quartal
					WHERE matnr = ".$schuelerId."
					AND fehlminuten != 0
					AND stundenplan.fach = '".$this->fach."'
					AND quartal=".$this->quartal.";";

			$sqlSet = $dbc->sqlStatement($strQ);
			$dbc->close();
			$i=0;

			while($r = $sqlSet->fetch_assoc())
			{
     			$rows[] = $r;
				if($rows[$i]["entschuldigt"] == 1)
				{
					$rows[$i]["entschuldigt"] = "|";
				}
				else if($rows[$i]["entschuldigt"] == 2)
				{
					$rows[$i]["entschuldigt"] = "K";
				}
				else if($rows[$i]["entschuldigt"] == 3)
				{
					$rows[$i]["entschuldigt"] = "B";
				}
				else if($rows[$i]["entschuldigt"] == 4)
				{
					$rows[$i]["entschuldigt"] = "A";
				}

				$i = $i+1;
			}

			$dbc->close();

			return $rows;
		}
//-----------------------------------------------------

		public function fehlstundenGesamt($schuelerId)
		{
				$dbc =  DBConnection :: getInstance();
				$dbc->connectDB();

				$strQ = "SELECT SUM(bis-von+1) AS fehlstunden
									FROM anwesenheit
									INNER JOIN stundenplan
									ON anwesenheit.unterrichtstunde = stundenplan.id
									INNER JOIN quartal
									ON quartal.id = anwesenheit.quartal
									WHERE matnr = ".$schuelerId."
									AND anwesend = 0
									AND quartal=".$this->quartal.";";

				$sqlSet = $dbc->sqlStatement($strQ);
				$dbc->close();

				if($sqlSet != NULL){
					$r = $sqlSet->fetch_assoc();
					if($r["fehlstunden"]=="")
					{
						$r["fehlstunden"]=0;
					}
					return $r;
				}
				else
				{
					$r = array("fehlstunden"=>0);
					return $r;
				}
			}

			public function fehlstundenGesamtEntschuldigt($schuelerId)
			{
					$dbc =  DBConnection :: getInstance();
					$dbc->connectDB();

					$strQ = "SELECT SUM(bis-von+1) AS fehlstunden_Entschuldigt
										FROM anwesenheit
										INNER JOIN stundenplan
										ON anwesenheit.unterrichtstunde = stundenplan.id
										INNER JOIN quartal
										ON quartal.id = anwesenheit.quartal
										WHERE matnr = ".$schuelerId."
										AND anwesend = 0
										AND entschuldigt > 1
										AND quartal=".$this->quartal.";";

					$sqlSet = $dbc->sqlStatement($strQ);
					$dbc->close();

					if($sqlSet != NULL)
					{
						$r = $sqlSet->fetch_assoc();
						if($r["fehlstunden_Entschuldigt"]=="")
						{
							$r["fehlstunden_Entschuldigt"]=0;
						}
						return $r;
					}
					else
					{
						$r = array("fehlstunden_Entschuldigt"=>0);
						return $r;
					}
				}

		public function verspaetungGesamt($schuelerId)
		{
				$dbc =  DBConnection :: getInstance();
				$dbc->connectDB();

				$strQ = "SELECT SUM(fehlminuten) AS fehlmintuen_ver,
									count(fehlminuten) AS anz_versp_Gesamt
									FROM anwesenheit
									INNER JOIN stundenplan
									ON anwesenheit.unterrichtstunde = stundenplan.id
									INNER JOIN quartal
									ON quartal.id = anwesenheit.quartal
									WHERE matnr = ".$schuelerId."
									AND verspaetet = 1

									AND quartal=".$this->quartal.";";

				$sqlSet = $dbc->sqlStatement($strQ);
				$dbc->close();


				$r = $sqlSet->fetch_assoc();
				if($r['fehlmintuen_ver']=="")
				{
					$r['fehlmintuen_ver']=0;
					return $r;
				}
				else {
					return $r;
				}
				return $r;
			}

			public function verspaetungEntschuldigt($schuelerId)
			{
					$dbc =  DBConnection :: getInstance();
					$dbc->connectDB();

					$strQ = "SELECT SUM(fehlminuten) AS fehlmintuen_Entschuldigt
										FROM anwesenheit
										INNER JOIN stundenplan
										ON anwesenheit.unterrichtstunde = stundenplan.id
										INNER JOIN quartal
										ON quartal.id = anwesenheit.quartal
										WHERE matnr = ".$schuelerId."
										AND verspaetet = 1
										AND entschuldigt > 1
										AND quartal=".$this->quartal.";";

					$sqlSet = $dbc->sqlStatement($strQ);
					$dbc->close();


					$r = $sqlSet->fetch_assoc();
					if($r['fehlmintuen_Entschuldigt']=="")
					{
						$r['fehlmintuen_Entschuldigt']=0;
						return $r;
					}
					else {
						return $r;
					}
				}

		public function fehlzeitenUnentschuldigt20Tage($schuelerId)
		{
			$strQ = "SELECT floor(SUM(fehlminuten)/45) AS fehlstunden20
								FROM anwesenheit
								INNER JOIN stundenplan
								ON anwesenheit.unterrichtstunde = stundenplan.id
								WHERE matnr = ".$schuelerId."
								AND entschuldigt = 1
								AND datum > curdate()- INTERVAL 20 DAY;";

			$dbc =  DBConnection :: getInstance();
			$dbc->connectDB();
			$sqlSet = $dbc->sqlStatement($strQ);
			$dbc->close();

			if($sqlSet != NULL){
				$r = $sqlSet->fetch_assoc();
				return $r;
			}
			else
			{
				$r = array("fehlminuten"=>0);
				return $r;
			}
		}

		public function entschuldigen($schuelerId)
		{
			$strQ="SELECT matnr, datum, fach, fehlminuten, thema, entschuldigt,
			 anwesend, verspaetet, stundenplan.lehrerkuerzel, stundenplan.id
			FROM anwesenheit
			INNER JOIN stundenplan
			ON anwesenheit.unterrichtstunde = stundenplan.id
			WHERE matnr = ".$schuelerId."
			AND entschuldigt>0;";
			//AND entschuldigt < 2);";
			//AND datum > curdate()- INTERVAL 28 DAY;";

			$dbc =  DBConnection :: getInstance();
			$dbc->connectDB();
			$sqlSet = $dbc->sqlStatement($strQ);
			$dbc->close();

			while($r = $sqlSet->fetch_assoc())
			{
     			$rows[] = $r;
			}

			return $rows;
			//return $strQ;
		}

		public function getFehlzeitenListe($schuelrId)
		{
			$dbc =  DBConnection :: getInstance();
			$dbc->connectDB();

			$strQ = "SELECT DISTINCT fehlminuten, thema, datum, entschuldigt
					FROM anwesenheit
					INNER JOIN stundenplan
					ON anwesenheit.unterrichtstunde = stundenplan.id
					INNER JOIN quartal
					ON quartal.id = anwesenheit.quartal
					WHERE matnr = ".$schuelerId."
					AND fehlminuten != 0;";

			$sqlSet = $dbc->sqlStatement($strQ);
			$dbc->close();
			$i=0;

			while($r = $sqlSet->fetch_assoc())
			{
     			$rows[] = $r;
				if($rows[$i]["entschuldigt"] == 1)
				{
					$rows[$i]["entschuldigt"] = "|";
				}
				else if($rows[$i]["entschuldigt"] == 2)
				{
					$rows[$i]["entschuldigt"] = "K";
				}
				else if($rows[$i]["entschuldigt"] == 3)
				{
					$rows[$i]["entschuldigt"] = "B";
				}
				else if($rows[$i]["entschuldigt"] == 4)
				{
					$rows[$i]["entschuldigt"] = "A";
				}

				$i = $i+1;
			}

			$dbc->close();

			return $rows;

		}

		public function unterrichtGesamt($schuelerId)
		{
			$dbc =  DBConnection :: getInstance();
			$dbc->connectDB();

			$strQ = "SELECT SUM(bis-von+1) AS gesamt
								FROM anwesenheit
								INNER JOIN stundenplan
								ON anwesenheit.unterrichtstunde = stundenplan.id
								INNER JOIN quartal
								ON quartal.id = anwesenheit.quartal
								WHERE matnr = ".$schuelerId."
								AND quartal=".$this->quartal.";";

								$sqlSet = $dbc->sqlStatement($strQ);
								$dbc->close();

								if($sqlSet != NULL)
								{
									$r = $sqlSet->fetch_assoc();
									return $r;
								}
								else
								{
									$r = array("gesamt"=>0);
									return $r;
								}
			}

		public function getKlassen()
		{
				$strQ = "SELECT DISTINCT klassenname FROM klasse;";
				$dbc =  DBConnection :: getInstance();
				$dbc->connectDB();
				$sqlSet = $dbc->sqlStatement($strQ);
				$dbc->close();

				while($r = $sqlSet->fetch_assoc())
				{
	     			$rows[] = $r;
				}

				return $rows;
		}

		public function getKlassenlehrer($stdId,$bntzr)
		{
			$strQ = "SELECT COUNT(*) As enable
			 					FROM stundenplan
								INNER JOIN klasse
								ON stundenplan.klasse = klasse.klassenname
								WHERE klasse.klassenlehrer='$bntzr';";

								$dbc =  DBConnection :: getInstance();
								$dbc->connectDB();
								$sqlSet = $dbc->sqlStatement($strQ);
								$dbc->close();

								return $sqlSet->fetch_assoc();
		}
	}
?>
