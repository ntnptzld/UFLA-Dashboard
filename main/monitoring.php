<?php 
  session_start();
  include 'db_config.php';

  if (isset($_SESSION['user_id']) && isset($_SESSION['user_email'])) { 
?>

<!DOCTYPE html>

<html lang="de">

  <head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitoring</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&family=Signika:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="stylesheet.css">
    <link rel="icon" href="bilder/UFLA_Logo.png">

  </head>

  <body background="bilder/background.png">

    <?php
      
      //manuelles festlegen von Start/Enddatum beim Neuladen der Seite; Automatisierung ist geplant
      $startdate = "2020-01-01";
      $enddate = "2020-02-01";

      //erstmaliges Laden der Monitoring-Werte aus der Datenbank für das Neuladen der Seite
      try{
        $sql = "SELECT * FROM $tableMon WHERE $tableMonTime BETWEEN DATE '$startdate' AND DATE '$enddate'";
        $result = $conn -> query($sql);

        if ($result -> rowCount() > 0) {
          
          //Befüllen der Arrays für die Darstellung des Diagramms mit den Werten aus der Datenbank
          while ($row = $result -> fetch()) {
            $dates[] = $row["Zeitschritt"];
            $firstStep[] = $row["> 49,0 Hz"];
            $step[] = $row["49,0 Hz"];
            $stepSum[] = $row["49,0 Hz bis 48,1 Hz"];
            $lastStep[] = $row["<= 48,0 Hz"];
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


        <!-- Überschrift & Menüpunkte -->
        <h1 class="schrift1">UFLA-Monitoring Viertelstundewerte</h1>

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


<div class="lineChart-monitoring">

    <!-- Erstellung des ChartJS Diagramms und der beiden Kalender für Start/Enddatum -->
    <!-- Auswahl im Kalender wird mit "max" und "min" zur Zeit auf einzigen in der Datenbank verfügbaren Werte beschränkt-->
    <!-- Muss zur Zeit noch manuell geändert werden; Automatisierung ist geplant -->
    <div class="chartBox-monitoring">
            <canvas id="myChart-monitoring"></canvas><br>
            <input onchange="filterData()" type="date" min="2020-01-01" max="2021-01-01" id="startdate" value="2020-01-01"> 
            <input onchange="filterData()" type="date" min="2020-01-01" max="2021-01-01" id="enddate" value="2020-02-01">
    </div>

    <!-- Laden für die Erstellung des Diagramms und Übertragung der eingegeben Werte relevanter Skripte -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js"></script>
    
    <script>
        //Setup für ChartJS Diagramm
        //Befüllung mit ersten Daten anhand von festgelegtem Start/Enddatum beim Neuladen der Seite
        var dates = <?php echo json_encode($dates); ?>;
        var firstStep = <?php echo json_encode($firstStep); ?>;
        var step = <?php echo json_encode($step); ?>;
        var stepSum = <?php echo json_encode($stepSum); ?>;
        var lastStep = <?php echo json_encode($lastStep); ?>;

        const data = {
            labels: dates,
            datasets: [{
                label: 'Stufe 0: > 49,0 Hz',
                data: firstStep,
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                tension: 0.4,
            },{
                label: 'Stufe 1: 49,0 Hz',
                data: step,
                backgroundColor: 'rgba(255, 26, 104, 0.2)',
                borderColor: 'rgba(255, 26, 104, 1)',
                tension: 0.4,
            },{
              label: 'Summer aller 10 Stufen: 49,0 Hz bis 48,1 Hz',
                data: stepSum,
                backgroundColor: 'rgba(26, 255, 104, 0.2)',
                borderColor: 'rgba(26, 255, 104, 1)',
                tension: 0.4,
            },{
              label: 'Stufe 12: ≤ 48,0 Hz',
                data: lastStep,
                backgroundColor: 'rgba(255, 159, 64, 0.2)',
                borderColor: 'rgba(255, 159, 64, 1)',
                tension: 0.4,
            }]
        };

        //Tooltip Konfigurationen
        //Tooltip = Hoverbild, wenn man mit der Maus über die Punkte des Diagramms hovert
        const titleBefore = (tooltipItems) => {
          return "Zeitpunkt";
        }
        const lableBefore = (tooltipItems) => {
          return "----------------";
        }
        const labelAfter = (tooltipItems) => {
          return "MW";
        }

        //Konfigurationen für ChartJS-Diagramm
        const config = {
            type: 'line',
            data,
            options: {
              plugins: {
                title: {
                  display: true,
                  text: ['Zeitreihen der aktivierten Abwurfleistungen pro Unterfrequenz-Auslösestufe', 'in viertelstündlicher zeitlicher Auflösung'],
                  font: {
                    size: 24
                  }
                },
                tooltip: {
                  yAlign: 'bottom',
                  titleAlign: 'center',
                  bodyAlign: 'center',
                  displayColors: false,
                  callbacks: {
                    beforeTitle: titleBefore,
                    beforeLabel: lableBefore,
                    afterLabel: labelAfter
                    }
                  }
              },
              scales: {
                y: {
                  beginAtZero: true,
                  title: {
                    display: true,
                    text: 'Aktivierte Abwurfleistungen [MW]',
                    font: {
                      size: 20
                    }
                  },            
                  ticks: {
                    font: {
                      size: 16
                    }
                  }
                },
                x: {
                  title: {
                    display: true,
                    text: 'Datum [dd:mm:yyyy] Uhrzeit [hh:mm]',
                    font: {
                      size: 20
                    }
                  },            
                  ticks: {
                    font: {
                      size: 16
                    }
                  }
                }
              }
            }
        };

        //Render Block für ChartJS Diagramm
        const chartMonitoring = new Chart(
            document.getElementById('myChart-monitoring'),
            config
        );

        //Funktion wird Aufgerufen, wenn in einem Kalender eine Eingabe getätigt wird
        //Entnimmt den Kalendern ihre derzeit ausgewählte Value (Start/Enddatum) und übergibt sie an "getDataMon.php"
        //Führt im Anschluss automatisch die Funktionen "getData()" und "updateChart()" aus
        function filterData() {
          var startdateSelected = document.getElementById('startdate').value;
          var enddateSelected = document.getElementById('enddate').value;

          $.ajax({
                async: true,
                url: "getDataMon.php",
                data: {
                startdateSelected,
                enddateSelected,
                },
                datatype: "json",
                type: "POST",
                success: function(data) {
                    getData(data);
                    updateChart();
                }
            });
        }

        //Funktion nimmt die Werte entgegen, welche von "getDataMon.php" bereit gestellt werden
        //Werte sind neue Monitoring-Kennzahlen, welche aus der Datenbank entnommen würden
        //globale Arrays zur Darstellung des Diagramms werden mit neuen Werten überschrieben
        function getData(data) {
          
          //Entnahme der einzelnen Array-Bestandteile anhand ihres Namens aus dem Übergabearray "array"
          var array = $.parseJSON(data);
          window.dates = array.dates;
          window.firstStep = array.firstStep;
          window.step = array.step;
          window.stepSum = array.stepSum;
          window.lastStep = array.lastStep;
        }

        //Funktion legt die überschriebenen Arrays zur Darstellung des Diagramms als neue Datensätze für die Darstellung des Diagramms fest
        //Aktualisiert das Diagramm --> Diagramm wird mir neuen Werte generiert
        function updateChart() {

          chartMonitoring.data.labels = dates;
          chartMonitoring.data.datasets[0].data = firstStep;
          chartMonitoring.data.datasets[1].data = step;
          chartMonitoring.data.datasets[2].data = stepSum;
          chartMonitoring.data.datasets[3].data = lastStep;
          chartMonitoring.update();
        }

    </script>

</div>

</body>

</html>


<?php 
}else {
   header("Location: login.php");
}
 ?>