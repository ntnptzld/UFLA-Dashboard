<?php 
  session_start();

  include 'db_config.php';

  if (isset($_SESSION['user_id']) && isset($_SESSION['user_email'])) {
    if ($_SESSION['user_groupe'] == 'admin') { 
?>

<!DOCTYPE html>

<html lang="de">

    <head>

        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Reporting</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&family=Signika:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <link rel="stylesheet" href="stylesheet.css">

    </head>

    <body background="bilder/background.png">

        <!-- Überschrift & Menüpunkte -->
        <h1 class="schrift1">Administration</h1>


        <div class="home-icon">
        <a href="index.php">
            <img src="bilder/home_icon.png" height="50px" width="50px" alt="Startmenü">
        </a>
        </div>

        <div class="back-icon">
            <a href="javascript:history.back()">
                <img src="bilder/back_icon.png" height="50px" width="50px" alt="Zurück">
            </a>
        </div>

        <div class="logout-icon">
            <a href="logout.php">
                <img src="bilder/logout_icon.png" height="50px" width="50px" alt="Ausloggen">
            </a>
        </div>

        


</body>

<?php 
    } else {
        header("Location: index.php");
    }
} else {
   header("Location: login.php");
}
 ?>