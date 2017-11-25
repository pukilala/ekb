<?php
  session_start();
  
  require_once(__DIR__.'/../../../../ekb/database/ClassDBC.php');


    $dbc =  DBConnection :: getInstance();
    $dbc->connectDB();

    $uid = "'".$_SESSION['bntzr']."'";

    //$strQ="SELECT  "

    $strQ = "SELECT DISTINCT schueler.klasse AS myKlasse
      FROM schueler
      INNER JOIN klasse
      ON klasse.klassenname = schueler.klasse
      WHERE klasse.klassenlehrer = $uid
      ORDER BY 1;";

    $sqlSet = $dbc->sqlStatement($strQ);

    $i=0;
    while ($r=$sqlSet->fetch_assoc())
    {
      $rows[]=$r;
      $rows[$i]["item"] = $i;
      $i = $i+1;
    }

    $dbc->close();
  echo json_encode($rows,JSON_UNESCAPED_SLASHES);


?>
