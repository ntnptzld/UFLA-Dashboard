<?php
include 'db_config.php';

//Entgegennahme der Eingabewerte (Start/Enddatum) von den Kalendern
$startdateSelected = $_POST['startdateSelected'];
$enddateSelected = $_POST['enddateSelected'];

//Sicherheitsüberpürfung, dass tatsächlich gültige Werte übergeben wurden, ansonsten Festlegung der ursprünglichen Standardwerte
if ($startdateSelected == null) {
    $startdate = "2020-01-01";
  } else {
    $startdate = $startdateSelected;
  }

if ($enddateSelected == null) {
    $enddate = "2020-02-01";
  } else {
    $enddate = $enddateSelected;
}

//Laden der Monitoring-Werte aus der Datenbank für das Aktualisieren des Diagramms
try{
  $sql = "SELECT * FROM $tableMon WHERE $tableMonTime BETWEEN DATE '$startdate' AND DATE '$enddate'";
  $result = $conn -> query($sql);

  if ($result -> rowCount() > 0) {
    
    //Befüllen der Arrays für die Darstellung des Diagramms mit den Werten aus der Datenbank
    while ($row = $result -> fetch()) {
      $dates[] = $row["Zeitschritt"];
      $firstStep[] = $row["> 49,0 Hz"];
      $step[] = $row["49,0 Hz"];
      $stepSum[] = $row["49,0 Hz bis 48,1 Hz"];
      $lastStep[] = $row["<= 48,0 Hz"];
    }
    unset($result);
  } else {
    echo "Es wurden keine gültigen Werte gefunden.";
  }
} 
catch (PDOException $e) {
  die("Verbindung fehlgeschlagen : ". $e->getMessage());
}
unset ($conn);

//Befüllen des Übergabearrays "$array" mit den aus der SQL-Abfrage zuvor befüllten Arrays
$array = array(
          "dates"    => $dates,
          "firstStep" => $firstStep,
          "step"    => $step,
          "stepSum" => $stepSum,
          "lastStep"  => $lastStep);

//Übergabe des Übergabearrays "$array" an eine entgegennehmende Instanz
//in diesem Fall die Funktion "getData()" in "monitoring.php"
print_r(json_encode($array)); 
?>