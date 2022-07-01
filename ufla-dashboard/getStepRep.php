<?php   

include 'db_config.php';

//Entgegennahme des Eingabewertes (Jahr) vom Dropdown-Menü
$stepSelected = $_POST['stepSelected'];

if ($stepSelected == null) {
    $year = date("Y") - 1;
  } else {
    $year = $stepSelected;
  }

try{
    $sql = "SELECT * FROM $tableRep WHERE $tableRepTime = '$year'";
    $result = $conn -> query($sql);

    if ($result -> rowCount() > 0) {
      $frequency = array();

      //Befüllen der Arrays für die Darstellung des Diagramms mit den Werten aus der Datenbank
      while ($row = $result -> fetch()) {
        $frequency[0] = $row["> 49,0"];
        $frequency[1] = $row["49,0"];
        $frequency[2] = $row["48,9"];
        $frequency[3] = $row["48,8"];
        $frequency[4] = $row["48,7"];
        $frequency[5] = $row["48,6"];
        $frequency[6] = $row["48,5"];
        $frequency[7] = $row["48,4"];
        $frequency[8] = $row["48,3"];
        $frequency[9] = $row["48,2"];
        $frequency[10] = $row["48,1"];
        $frequency[11] = $row["<= 48,0"];
      }
      unset($result);
    } else {
      echo "Es wurden keine gültigen Werte gefunden.";
      $frequency[] = 0;
    }
  } 
  catch (PDOException $e) {
    die("Connection failed : ". $e->getMessage());
  }
  unset ($conn);


  print_r(json_encode($frequency));
?>