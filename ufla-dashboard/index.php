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
            .image-map {
                position: absolute;
                bottom: 30px;
                left: 250px;
                cursor: pointer;
            }

            .user_pw_change {
                position: absolute;
                top: 90px;
                left: 220px;
                cursor: pointer;
            }

            .admin_page {
                position: absolute;
                top: 90px;
                left: 220px;
                cursor: pointer;
            }

            .ticket-formular {
                position: absolute;
                top: 90px;
                left: 280px;
                cursor: pointer;
            }

            .logout-home-user {
                font-size: 20px;
                position: absolute;
                top: 90px;
                left: 340px;
                cursor: pointer;
            }

            .logout-home-admin {
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
                
                display: flex;
                flex-direction: row;
                align-items: center;
                text-align: center;

                z-index: 2;
                padding: 20px;
                box-sizing: border-box;
                background-color: white;
                border-radius: 20px;
                padding: 30px;
                box-shadow: 50px 50px 50px -50px darkslategray;
            }

            .popupUPC .popupUPC-closeBtn {
                position: absolute;
                left: 20px;
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

            .myform {
                width: 270px;
                margin-right: 30px;
            }

            input {
                border:  none;
                outline: none;
                border-radius: 0;
                width: 100%;
                border-bottom: 2px solid #1c1c1e;
                margin-bottom: 25px;
                padding: 7px 0;
                font-size: 14px;
            }

            button {
                color: white;
                background-color: #1c1c1e;
                border: none;
                outline: none;
                border-radius: 2px;
                font-size: 14px;
                padding: 5px 12px;
                font-weight: 500;
            }

            textarea:focus,
            input:focus {
                outline: none;
            }
        </style>

    </head>

    <body background="bilder/background.png">
        
        <!-- Überschrift und graue Hintergrundbilder zur Hervorhebung der möglichen Eingabe-Dashes -->
        <h1 class="schrift1">UFLA-Dashboard Mitnetz Strom</h1>

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
        <div class="image-map">
                <a href="map.php">
                    <img src="bilder/mapIcon.png" width="400px" height="200px" alt="Monitoring">
                </a>
            </div>

        <?php 
             if ($_SESSION['user_groupe'] == 'admin') {
        ?>

        <div class="admin_page">
            <a href="admin.php">
                <img src="bilder/admin_icon.png" height="50" width="50" alt="Adminstration">
            </a>
        </div>
        <div class="logout-home-admin">
            <a href="logout.php">
                <img src="bilder/logout_icon.png" height="50" width="50" alt="Ausloggen"><span style="vertical-align: middle;"><strong>Ausloggen</strong></span>
            </a>
        </div>

        <?php 
            } if ($_SESSION['user_groupe'] == 'user') {
        ?>

        <div class="user_pw_change" onclick="togglePopup('popup-userPwChange')">
            <img src="bilder/pw_change_icon.png" height="50" width="50" alt="Password ändern">
        </div>
        <div class="ticket-formular" onclick="togglePopup('popup-ticketFormular')">
            <img src="bilder/ticket_icon.png" height="50" width="50" alt="Ticketformular">
        </div>
        <div class="logout-home-user">
            <a href="logout.php">
                <img src="bilder/logout_icon.png" height="50" width="50" alt="Ausloggen"><strong>Ausloggen</strong>
            </a>
        </div>

        <?php
            }
        ?>       

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
            <div class="popupUPC-overlay" onclick="togglePopup('popup-userPwChange')"></div>
            <div class="popupUPC-content">
                <div class="popupUPC-closeBtn" onclick="togglePopup('popup-userPwChange')">&times;</div>
                <div class="myform">
                    <form action="index.php" method="post">
                        <h2 class="schrift2">Passwort ändern</h2>

                        <?php if (isset($_GET['error'])) { ?>
                        <div class="login-error" role="alert">
                        <?=htmlspecialchars($_GET['error'])?>
                        </div>
                        <?php } ?><br>

                        <input type="password" name="oldPassword" value="" placeholder="Aktuelles Passwort"><br><br>
                        <input type="password" name="newPassword" value="" placeholder="Neues Passwort"><br><br>
                        <input type="password" name="newPasswordWdh" value="" placeholder="Passwort wiederholen"><br><br>
                        <button name="newUserPw" type="submit">Passwort ändern</button>
                    </form>
                </div>
                <div class="image">
                    <img src="bilder/office.png" width="600px">
                </div>
            </div>
        </div>

        <div class="popupUPC" id="popup-ticketFormular">
            <div class="popupUPC-overlay" onclick="togglePopup('popup-ticketFormular')"></div>
            <div class="popupUPC-content">
                <div class="popupUPC-closeBtn" onclick="togglePopup('popup-ticketFormular')">&times;</div>
                <h2>Ticketformular</h2>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit.<br>
                    Expedita debitis asperiores laborum ratione ad non cum <br>
                    dicta modi culpa suscipit impedit, excepturi tenetur dolore <br>
                    quisquam dolorem voluptatibus aliquam minima qui!</p>
                <a href="tickets/overview.php" target="_blank"><h3>Deine Tickets</h3></a>
            </div>
        </div>

        <script type="text/javascript">
            function togglePopup(id) {
                document.getElementById(id).classList.toggle("active");
            }
        </script>

    </body>
</html>

<?php
} else {
   header("Location: login.php");
}
 ?>