<?php 
  session_start();

  include 'db_config.php';

  if (isset($_SESSION['user_id']) && isset($_SESSION['user_email']))   {
    if ($_SESSION['user_groupe'] == 'admin') {
?>

<!DOCTYPE html>

<html lang="de">

    <head>

        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Administration</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&family=Signika:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <link rel="stylesheet" href="stylesheet.css">

        <style>
           .admin-box {
                display: flex;
                justify-content: space-around;
                margin-top: 100px;
                margin-left: 100px;
                margin-right: 100px;
                margin-bottom: 50px;
                padding: 20px; 
                background: white;
                border-radius: 20px;
            }

            .admin-log {
                padding-left: 3%;
                padding-bottom: 3%;
                text-align: left;
                width: 50%;
                height: 80%;
                display: block;
                overflow: auto;
            }

            .admin-userTable {
                padding-left: 5%;
                padding-bottom: 3%;
                text-align: left;
                width: 70%;
                height: 80%;
                display: block;
                overflow: auto;
            }

            .schrift2 {
                text-align: center;
                font-size: 30px;
                color: black;
            }
                        
            .tableIdCell {
                width: 5%;
            }
            .tableNameCell {
                width: 20%;
            }
            .tableEmailCell {
                width: 40%;
            }
            .tableUserCell: {
                width: 15%;
            }
        
            .tableIconCell {
                width: 10%;
                text-align: center;
            }
        </style>

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
            <a href="index.php">
                <img src="bilder/back_icon.png" height="50px" width="50px" alt="Zurück">
            </a>
        </div>

        <div class="logout-icon">
            <a href="logout.php">
                <img src="bilder/logout_icon.png" height="50px" width="50px" alt="Ausloggen">
            </a>
        </div>

        <div class="admin-box">
            <a href="admin/nutzer-verwaltung.php">
                <img src="bilder/map.png">
            </a>
        </div>
        
        <script type="text/javascript" src="javascript/font.js"></script>

</body>

<?php 
    } else {
        header("Location: index.php");
    }
} else {
   header("Location: login.php");
}
 ?>