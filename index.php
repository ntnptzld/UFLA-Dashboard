<?php 
  session_start();

  if (isset($_SESSION['user_id']) && isset($_SESSION['user_email'])) { 
?>

<!DOCTYPE html>
<html lang="de">
<head>

	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Startseite</title>

	<link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&family=Signika:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="stylesheet.css">
    <link rel="icon" href="bilder/UFLA_Logo.png">

    <style>
        #image-map {
            position: absolute;
            bottom: 30px;
            left: 220px;
        }

        #admin_page {
            position: absolute;
            top: 90px;
            left: 220px;
        }

        #logout-home {
            font-size: 20px;
            position: absolute;
            top: 90px;
            left: 280px;
        }
    </style>

</head>

<body background="bilder/background.png">
	 
    <!-- Überschrift und graue Hintergrundbilder zur Hervorhebung der möglichen Eingabe-Dashes -->
    <h1 class="schrift1">UFLA-Dashboard MITNETZ Strom</h1>

    <div id="home-background1">
        <img src="bilder/background_grey.png">
    </div>

    <div id="home-background2">
        <img src="bilder/background_grey_half.png">
    </div>

    <!-- Menüpunkte Reporting, Monitoring, Logout -->
    <div class="home-menu1">
        <a href ="reporting.php">
            <img src="bilder/reporting.png" alt="Reporting">
        </a>
    </div>
    <div class="home-menu2">
        <a href ="monitoring.php">
            <img src="bilder/monitoring.png" alt="Monitoring">
        </a>
    </div>
    <div id="image-map">
            <a href="map.php">
                <img src="bilder/Netzkarte.png" width="630px" height="200px" alt="Monitoring">
            </a>
        </div>

    <div id="admin_page">
        <a href="admin.php">
            <img src="bilder/admin_icon.png" height="50" width="50" alt="Log out">
        </a>
    </div>
    <div id="logout-home">
        <a href="logout.php">
            <img src="bilder/logout_icon.png" height="50" width="50" alt="Log out"><strong>Log out</strong>
        </a>
    </div>

    <!-- Logos der zusammenarbeitenden Partner (Mitnetz Strom & Hochschule Mittweida) -->
    <span id="partner-text">in Arbeit von:</span>
    <div id="partner-logo">        
        <a href="https://www.mitnetz-strom.de/" target="_blank">
            <img src="bilder/Mitnetz Logo.png" height="100" alt="mitNetz Strom">
        </a>
        <span class="placeholder-text">..........</span>
        <a href="https://www.hs-mittweida.de/" target="_blank">
            <img src="bilder/HSMW_Logo.png" height="100" alt="Hochschule Mittweida">
        </a>
    </div>


</body>
</html>


<?php 
} else {
   header("Location: login.php");
}
 ?>