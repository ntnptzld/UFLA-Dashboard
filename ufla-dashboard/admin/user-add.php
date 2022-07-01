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
        <title>Nutzer hinzufügen</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&family=Signika:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <link rel="stylesheet" href="../stylesheet.css">
        <link rel="icon" href="../bilder/UFLA_Logo.png">

       
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

            .admin-userEdit {
                padding-left: 5%;
                padding-bottom: 3%;
                text-align: center;
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
        </style>

    </head>

    <body background="../bilder/background.png">

        <!-- Überschrift & Menüpunkte -->
        <h1 class="schrift1">Benutzer bearbeiten</h1>


        <div class="home-icon">
        <a href="../index.php">
            <img src="../bilder/home_icon.png" height="50px" width="50px" alt="Startmenü">
        </a>
        </div>

        <div class="back-icon">
            <a href="user-control.php">
                <img src="../bilder/back_icon.png" height="50px" width="50px" alt="Zurück">
            </a>
        </div>

        <div class="logout-icon">
            <a href="../logout.php">
                <img src="../bilder/logout_icon.png" height="50px" width="50px" alt="Ausloggen">
            </a>
        </div>

        <div class="admin-box">
        <div class="admin-log">
            <h2 class="schrift2">Log-Fenster</h2><br>
                <?php
                    if(isset($_POST["newUserData"])) {
                        if ($_POST["newPassword"] != $_POST["newPasswordWdh"]) {
                            ?>
                                <p>Die Passwörter sind nicht gleich.</p>
                            <?php
                        } else {
                        ?> 
                            <p>Neuer Benutzer wurden gespeichert.</p>
                        <?php
                        }
                    }
                ?>
            </div>
            <div class="admin-userEdit">
            <h2 class="schrift2">Benutzerdaten ändern</h2><br>
                <?php
                    if(isset($_POST["newUserData"])) {
                        
                        if ($_POST["newPassword"] == $_POST["newPasswordWdh"]) {
                            $sql = $conn->prepare("INSERT INTO $tableUsers (full_name, email, password, usergroupe) VALUES (?, ?, ?, ?)");
                            $sql->execute(array($_POST["newUsername"], $_POST["newEmail"], md5($_POST["newPassword"]), $_POST["newUserGroupe"]));
                        }
                    }
                ?>

                <form action="user-add.php" method="post">
                    <input type="text" name="newUsername" value="<?php if (!empty($_POST["newUsername"])) echo $_POST["newUsername"] ?>" placeholder="Benutzername" require><br><br>
                    <input type="email" name="newEmail" value="<?php  if (!empty($_POST["newEmail"])) echo $_POST["newEmail"] ?>" placeholder="Email" require><br><br>
                    <input type="password" name="newPassword" value="<?php if (!empty($_POST["newPassword"])) if ($_POST["newPassword"] == $_POST["newPasswordWdh"]) echo $_POST["newPassword"] ?>" placeholder="Passwort" require><br><br>
                    <input type="password" name="newPasswordWdh" value="<?php if (!empty($_POST["newPasswordWdh"])) if ($_POST["newPassword"] == $_POST["newPasswordWdh"]) echo $_POST["newPasswordWdh"] ?>" placeholder="Passwort wiederholen" require><br><br>
                    <select name="newUserGroupe">
                        <option value="user">Benutzer</option>
                        <option value="admin">Administrator</option>
                    </select><br><br>
                    <button name="newUserData" type="submit">Benutzer speichern</button>
                </form>
            </div>
            
        </div>
</body>

<?php 
    } else {
        header("Location: ../index.php");
    }
} else {
   header("Location: ../login.php");
}
 ?>