<?php 
//Namen der Datenbank-Tabellen
$tableMon = "monitoring";             //Name der Datenbank-Tabelle für Monitoring  
$tableRep = "reporting";              //Name der Datenbank-Tabelle für Reporting
$tableUFS = "unterfrequenzstufen";    //Name der Datenbank-Tabelle für Reporing der einzelnen Unterfrequenzstufen
$tableUsers = "users";                //Name der Datenbank-Tabelle für die Dashboard-Benutzerkonten

$tableRepTime = "jahr";               //Spaltenname der Datenbanktabelle Reporting zur Entnahme der zeitlich korrekten Daten
$tableMonTime = "Zeitschritt";        //Spaltenname der Datenbanktabelle Monitoring zur Entnahme der zeitlich korrekten Daten

//Bezeichnungen für Datenbankverbindung
$sName = "localhost";                 //Name des Webservers
$uName = "ufla-viewer";               //Name des Benutzers, mit dem der Zugriff auf die Datenbank erfolgen soll
$pass = "lQAiQ0w2p(alK@2C";           //Password des Benutzers, mit dem der Zugriff auf die Datenbank erfolgen soll
$db_name = "ufla-dashboard";          //Name der Datenbank


//Datenbank-Verbindung herstellen
try {
    $conn = new PDO("mysql:host=$sName;dbname=$db_name", 
                    $uName, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch(PDOException $e){
  echo "Verbindung fehlgeschlagen: ". $e->getMessage();
}

?>