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
        <title>Nutzerverwaltung</title>

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
        
            .tableIconCellEdit {
                width: 10%;
                text-align: center;
            }

            .tableIconCellDel {
                width: 10%;
                text-align: center;
            }

            .tableIconCellAdd {
                width: 10%;
                text-align: center;
            }
            
            .popupUPC .popupUPC-overlay {
                position: fixed;
                top: 0px;
                left: 0px;
                right: 0px;
                bottom: 0px;
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
                box-shadow: 8px, 8px, 16px rgba(255, 255, 255, 0.1);
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

            .confirmBtns {
                display: flex;
                justify-content: space-around;
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
            <div class="admin-log">
            <h2 class="schrift2">Log-Fenster</h2><br>
                <?php
                    if(isset($_GET["del"])) {
                        if(!empty($_GET["del"])) {
                            $sql = $conn->prepare("SELECT * FROM $tableUsers WHERE id = :id");
                            $sql->execute(array(":id" =>$_GET["del"]));
                            $row = $sql->fetch();
                            if ($row["email"] == "admin@mitnetz-strom.de") {
                                ?>
                                    <p>Der Haupt-Administrator darf hier nicht gelöscht werden.</p>
                                <?php
                            } else if (($_SESSION['user_id']) == $_GET["del"]) {
                                ?>
                                    <p>Dieser Account kann nicht gelöscht werden, da Sie mit diesem Account eingeloggt sind.</p>
                                <?php
                            } else {

                                $sql = $conn->prepare("DELETE FROM $tableUsers WHERE id = :id");
                                $sql->execute(array(":id" => $_GET["del"]));
                                ?>
                                    <p>Der Benutzer wurde gelöscht.</p>
                                <?php
                            }
                        }
                    }
                ?>
            </div>
            <div class="admin-userTable">
            <h2 class="schrift2">Benutzer-Übersicht</h2><br>
                <table id="userControlTable">
                    <thead>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Nutzergruppe</th>
                        <th>Bearbeiten</th>
                        <th>Löschen</th>
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
                                                <td class="tableUserCell"><?php echo $row["usergroupe"] ?></td>
                                                <td class="tableIconCellEdit"><a href="user-edit.php?id=<?php echo $row["id"] ?>"><i class="fa-solid fa-user-pen" style="cursor: pointer;"></i></a></td>
                                                <td class="tableIconCellDel"><div onclick="cellClicked()"><?php $userNameDel[] = $row["full_name"]; $idDel[] = $row["id"] ?><i class="fa-solid fa-user-minus" style="cursor: pointer;"></i></div></td>
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
                    <tfoot>
                         <tr>
                             <td class="tableIdCell"></td><td class="tableNameCell"></td>
                             <th class="tableEmailCell">Nutzer hinzufügen</th>
                             <td class="tableUserCell"></td>
                             <td class="tableIconCellAdd"><a href="user-add.php"><i class="fa-solid fa-user-plus"></i></a></td>
                         </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        
        <?php
            
        ?>

        <div class="popupUPC" id="popup-userPwChange">
            <div class="popupUPC-overlay" onclick="togglePopup()"></div>
            <div class="popupUPC-content">
                <div class="popupUPC-closeBtn" onclick="togglePopup()">&times;</div>
                <h3>Sind Sie sicher, dass Sie den Nutzer<br><span id="inputUserName"></span><br> entfernen möchten?</h3><br>
                <div class="confirmBtns">
                    <div onclick="togglePopup()"><a id="linkInputId" href="user-control.php?del="><i class="fa-solid fa-check" style="cursor: pointer;"></i></a></div>
                    <div onclick="togglePopup()"><i class="fa-solid fa-xmark" style="cursor: pointer;"></i></div>
                </div>
            </div>
        </div>

        <script type="text/javascript" src="../javascript/font.js"></script>

        <script type="text/javascript">
            
            var userNameDel = <?php echo json_encode($userNameDel); ?>;
            var idDel = <?php echo json_encode($idDel); ?>;

            function getTableRow() {
                const tbody = document.querySelector('#userControlTable tbody');
                tbody.addEventListener('click', function(e) {
                    const cell = e.target.closest('td');
                    if (!cell) {return;}
                    const row = cell.parentElement;
                    var rowCount = (row.rowIndex - 1);

                    document.getElementById('inputUserName').innerHTML = userNameDel[rowCount];
                    document.getElementById('linkInputId').href = `user-control.php?del=${idDel[rowCount]}`;
                });
            };

            function togglePopup() {
                document.getElementById("popup-userPwChange").classList.toggle("active");
            };

            function cellClicked() {
                getTableRow();
                togglePopup();
            };
        </script>

        

</body>

<?php 
    } else {
        header("Location: ../index.php");
    }
} else {
   header("Location: ../login.php");
}
 ?>