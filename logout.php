<?php 
session_start();

//Löschen der SESSION-Variablen
//Kein weiteres Besuchen einer Dashboard-Seite möglich
session_unset();
session_destroy();

//Nach erfolgreichem Logout wird auf "login.php" weitergeleitet
header("Location: login.php");

?>