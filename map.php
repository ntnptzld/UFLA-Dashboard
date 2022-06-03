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

    <style>
        
    </style>

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
        <h1 class="schrift1">Übersichtskarte aller Umspannwerke</h1>

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
            <canvas id="myChart-map"></canvas><br>
    </div>

    <!-- Laden für die Erstellung des Diagramms und Übertragung der eingegeben Werte relevanter Skripte -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/hammer.js/2.0.8/hammer.min.js" 
    integrity="sha512-UXumZrZNiOwnTcZSHLOfcTs0aos2MzBWHXOHOuB0J/R44QB0dwY5JgfbvljXcklVf65Gc4El6RjZ+lnwd2az2g==" 
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-zoom/1.2.1/chartjs-plugin-zoom.min.js" 
    integrity="sha512-klQv6lz2YR+MecyFYMFRuU2eAl8IPRo6zHnsc9n142TJuJHS8CG0ix4Oq9na9ceeg1u5EkBfZsFcV3U7J51iew==" 
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    
    <script>
        const labels = [
            'January',
            'February',
            'March',
            'April',
            'May',
            'June',
        ];
        const data = {
            datasets: [{
                label: 'Stufe 1',
                data: [{
                x: 20,
                y: 30,
                r: 13
                }, {
                x: 40,
                y: 10,
                r: 28
                }],
                backgroundColor: 'rgb(255, 99, 132)'
            },{
                label: 'Stufe 2',
                data: [{
                x: 15,
                y: 34,
                r: 15
                }, {
                x: 30,
                y: 19,
                r: 14
                }],
                backgroundColor: 'rgb(132, 99, 255)'
            },{
                label: 'Stufe 3',
                data: [{
                x: 25,
                y: 37,
                r: 9
                }, {
                x: 24,
                y: 13,
                r: 4
                }],
                backgroundColor: 'rgb(99, 255, 132)'
            }]
        };

        const config = {
            type: 'bubble',
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
                  zoom: {
                  pan: {
                    enabled: true,
                    mode: 'xy'
                  },
                  limits: {
                    x: {minRange: 20}
                  },
                  zoom: {
                    wheel: {
                      enabled: true,
                      speed: 0.02,
                    },
                    mode: 'xy'
                  }                    
                }
              },
              scales: {
                y: {
                  beginAtZero: true,
                  title: {
                    display: false
                  },            
                  ticks: {
                    display: false
                  },
                  grid: {
                      display: false,
                      drawOnChartArea: false,
                      drawBorder: false
                  }

                },
                x: {
                  title: {
                    display: false
                    },            
                  ticks: {
                    display: false
                  },
                  grid: {
                      display: false,
                      drawOnChartArea: false,
                      drawBorder: false
                  }
                }
              }
            }
        };

        const myChart_Map = new Chart(
            document.getElementById('myChart-map'),
            config
        );
</script>
</div>

</body>

</html>


<?php 
}else {
   header("Location: login.php");
}
 ?>