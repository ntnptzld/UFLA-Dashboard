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
        <title>Administration</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&family=Signika:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <link rel="stylesheet" href="../stylesheet.css">

       
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
            <a href="nutzer-verwaltung.php">
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
                    if(isset($_GET["id"])) {
                        if(!empty($_GET["id"])) {
                        } else {
                            ?>
                                <p>Es wurde kein Nutzer ausgewählt.</p>
                            <?php
                        }
                    } 
                    else {
                        ?>
                            <p>Es wurde kein Nutzer ausgewählt.</p>
                        <?php
                    }
                    if(isset($_POST["newUserData"])) {
                        
                        if ($_POST["newPassword"] != $_POST["newPassword1"]) {
                            ?>
                                <p>Die Passwörter sind nicht gleich.</p>
                            <?php
                        } else {
                            ?> 
                                <p>Neue Benutzerdaten wurden gespeichert.</p>
                            <?php
                        }
                    }
                ?>
            </div>
            <div class="admin-userEdit">
            <h2 class="schrift2">Benutzerdaten ändern</h2><br>
                <?php
                    if(isset($_GET["id"])) {
                        if(!empty($_GET["id"])) {
                            if(isset($_POST["newUserData"])) {
                                if ($_POST["newPassword"] == $_POST["newPassword1"]) {
                                    $sql = $conn->prepare("UPDATE $tableUsers SET full_name = :user, email = :email, password = :password WHERE id = :id");
                                    $sql->execute(array(":user" => $_POST["newUsername"], ":email" => $_POST["newEmail"], ":password" => md5($_POST["newPassword"]), ":id" => $_GET["id"]));
                                }
                            }
                            $sql = $conn->prepare("SELECT * FROM $tableUsers WHERE id = :id");
                            $sql->execute(array(":id" => $_GET["id"]));
                            $row = $sql->fetch();
                            ?>
                                <form action="nutzer-bearbeiten.php?id=<?php echo $_GET["id"]?>" method="post">
                                    <input type="text" name="newUsername" value="<?php echo $row["full_name"] ?>" placeholder="Benutzername" require><br><br>
                                    <input type="email" name="newEmail" value="<?php echo $row["email"] ?>" placeholder="Email" require><br><br>
                                    <input type="password" name="newPassword" value="<?php echo md5($row["password"]) ?>" placeholder="Passwort" require><br><br>
                                    <input type="password" name="newPassword1" value="<?php echo md5($row["password"]) ?>" placeholder="Passwort wiederholen" require><br><br>
                                    <button name="newUserData" type="submit">Speichern</button>
                                </form>
                            <?php
                        }
                    }
                ?>
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