<?php 
  session_start();

  if (isset($_SESSION['user_id']) && isset($_SESSION['user_email'])) {
      if ($_SESSION['user_groupe'] == 'user') {
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
                cursor: pointer;
            }

            #user_pw_change {
                position: absolute;
                top: 90px;
                left: 220px;
                cursor: pointer;
            }

            #logout-home {
                font-size: 20px;
                position: absolute;
                top: 90px;
                left: 280px;
                cursor: pointer;
            }

            .popupUPC .popupUPC-overlay {
                position: fixed;
                top: 0px;
                left: 0px;
                width: 100vw;
                height: 100vh;
                background: rgba(0, 0, 0, 0.7);
                z-index: 1;
                display: none;
            }
            
            .popupUPC .popupUPC-content {
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%) scale(0);
                width: 450px;
                height: 220px;
                z-index: 2;
                text-align: center;
                padding: 20px;
                box-sizing: border-box;
                background: #fff;
                border-radius: 20px;
            }

            .popupUPC .popupUPC-closeBtn {
                position: absolute;
                right: 20px;
                top: 20px;
                width: 30px;
                height: 30px;
                background: #222;
                color: #fff;
                font-size: 25px;
                font-weight: 600;
                line-height: 30px;
                text-align: center;
                border-radius: 50%;
                cursor: pointer;
            }

            .popupUPC.active .popupUPC-overlay {
                display: block;
            }

            .popupUPC.active .popupUPC-content {
                transition: all 300ms ease-in-out;
                transform: translate(-50%, -50%) scale(1);
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

        <div id="user_pw_change" onclick="togglePopup()">
            <img src="bilder/pw_change_icon.png" height="50" width="50" alt="Log out">
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

        <div class="popupUPC" id="popup-userPwChange">
            <div class="popupUPC-overlay"></div>
            <div class="popupUPC-content">
                <div class="popupUPC-closeBtn" onclick="togglePopup()">&times;</div>
                <h2>Password ändern</h2>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit.<br>
                    Expedita debitis asperiores laborum ratione ad non cum <br>
                    dicta modi culpa suscipit impedit, excepturi tenetur dolore <br>
                    quisquam dolorem voluptatibus aliquam minima qui!</p>
            </div>
        </div>

        <script type="text/javascript">
            function togglePopup() {
                document.getElementById("popup-userPwChange").classList.toggle("active");
            }
        </script>

    </body>
</html>


<?php 
    } if ($_SESSION['user_groupe'] == 'admin') {
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
                cursor: pointer;
            }

            #admin_page {
                position: absolute;
                top: 90px;
                left: 220px;
                cursor: pointer;
            }

            #admin_page img {
                height: 50px; 
                width: 50px;
                cursor: pointer;
            }

            #logout-home {
                font-size: 20px;
                position: absolute;
                top: 90px;
                left: 280px;
                cursor: pointer;
            }

            #logout-home img {
                height: 50px; 
                width: 50px;
                cursor: pointer;
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
                <img src="bilder/admin_icon.png" alt="Log out">
            </a>
        </div>
        <div id="logout-home">
            <a href="logout.php">
                <img src="bilder/logout_icon.png" alt="Log out"><strong>Log out</strong>
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
    } 
} else {
   header("Location: login.php");
}
 ?>