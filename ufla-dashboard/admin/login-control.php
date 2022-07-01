<?php 
  session_start();

  include '../db_config.php';

  if (isset($_SESSION['user_id']) && isset($_SESSION['user_email']))   {
    if ($_SESSION['user_groupe'] == 'admin') {
?>

<!DOCTYPE html>

<html lang="de">

    <head>

        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Loginüberwachung</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&family=Signika:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <link rel="stylesheet" href="../stylesheet.css">
        <link rel="icon" href="../bilder/UFLA_Logo.png">

        <style>
           .admin-box {
                display: flex;
                justify-content: center;
                margin-top: 100px;
                margin-left: 300px;
                margin-right: 300px;
                margin-bottom: 50px;
                padding: 20px; 
                background: white;
                border-radius: 20px;
            }

            .admin-userTable {
                padding-left: 50%;
                padding-bottom: 3%;
                transform: translate(-25%, 0%);
                text-align: left;
                width: 100%;
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
                width: 35%;
            }

            .tableLastLoginCell {
                width: 15%;
            }

            .tableLoggedInCell {
                width: 15%;
            }
        </style>

    </head>

    <body background="../bilder/background.png">

        <!-- Überschrift & Menüpunkte -->
        <h1 class="schrift1">Nutzerverwaltung</h1>


        <div class="home-icon">
        <a href="../index.php">
            <img src="../bilder/home_icon.png" height="50px" width="50px" alt="Startmenü">
        </a>
        </div>

        <div class="back-icon">
            <a href="../admin.php">
                <img src="../bilder/back_icon.png" height="50px" width="50px" alt="Zurück">
            </a>
        </div>

        <div class="logout-icon">
            <a href="../logout.php">
                <img src="../bilder/logout_icon.png" height="50px" width="50px" alt="Ausloggen">
            </a>
        </div>

        <div class="admin-box">
            <div class="admin-userTable">
            <h2 class="schrift2">Benutzer-Login-Übersicht</h2><br>
                <table>
                    <thead>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Letzter Login</th>
                        <th>Loginstatus</th>
                    </thead>
                    <tbody>
                        <?php 
                            try{
                                $sql = "SELECT * FROM $tableUsers";
                                $result = $conn -> query($sql);
                        
                                if ($result -> rowCount() > 0) {
                                
                                    while ($row = $result -> fetch()) {
                                        ?>
                                            <tr>
                                                <th class="tableIdCell"><?php echo $row["id"] ?></th>
                                                <td class="tableNameCell"><?php echo $row["full_name"] ?></td>
                                                <td class="tableEmailCell"><?php echo $row["email"] ?></td>
                                                <td class="tableLastLoginCell"><?php echo "Letzter Login" ?></td>
                                                <td class="tableLoggedInCell"><?php echo "nicht eingeloggt" ?></td>
                                            </tr>
                                        <?php
                                    }
                                    unset($result);
                                } else {
                                    echo "Es wurden keine gültigen Werte gefunden.";
                                }
                            } 
                            catch (PDOException $e) {
                                die("Connection failed : ". $e->getMessage());
                            }
                            unset ($conn);
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <?php
            
        ?>      

</body>

<?php 
    } else {
        header("Location: ../index.php");
    }
} else {
   header("Location: ../login.php");
}
 ?>