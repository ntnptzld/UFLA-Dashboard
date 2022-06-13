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
    <title>Netzkarte</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&family=Signika:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="stylesheet.css">
    <link rel="icon" href="bilder/UFLA_Logo.png">

    <style>
        /*canvas {
          background-image: url(bilder/Karte.png);
          background-size: 100% 100%;
          border-radius: 20px;
        }*/

        .chartBox-map {
          width: 70%;
          padding: 20px;
          margin-top: 40px;
          border-radius: 20px;
          border: solid 3px white;
          background: white;
        }

        .bubbleChart-map {
            display: flex;
            justify-content: center;
            margin-top: -20px;
        }

        #mapImage {
          border-radius: 20px;
        }
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


    <div class="bubbleChart-map">

      <!-- Erstellung des ChartJS Diagramms und der beiden Kalender für Start/Enddatum -->
      <!-- Auswahl im Kalender wird mit "max" und "min" zur Zeit auf einzigen in der Datenbank verfügbaren Werte beschränkt-->
      <!-- Muss zur Zeit noch manuell geändert werden; Automatisierung ist geplant -->
      <div class="chartBox-map">
              <canvas id="myChart-map"></canvas><br>
      </div>

      <!-- Laden für die Erstellung des Diagramms und Übertragung der eingegeben Werte relevanter Skripte -->
      <script type="text/javascript" src="javascript/chart.js"></script>
      <script type="text/javascript" src="javascipt/ajax.js"></script>
      <script type="text/javascript" src="javascript/pan.js"></script>
      <script type="text/javascript" src="javascript/zoom.js"></script>

      
      <script>

        var linkURL = <?php echo json_encode($linkRepUW_Detail); ?>;
        var dataLabels = ['> 49,0 Hz', '49,0 Hz', '48,9 Hz', '48,8 Hz', '48,7 Hz', '48,6 Hz', '48,5 Hz', '48,4 Hz', '48,3 Hz', '48,2 Hz', '48,1 Hz', '≤ 48,0 Hz'];
        
        const data = {
          datasets: [{
            label:dataLabels[0],
            data: [{
            }],
            backgroundColor: 'rgb(99, 255, 132)'
          },{
            label: dataLabels[1],
            data: [{
              x: 20,
              y: 30,
              r: 13
            }, {
              x: 40,
              y: 10,
              r: 28
            }],
            backgroundColor: 'rgba(255, 26, 104, 0.6)',
            borderColor: 'rgba(255, 26, 104, 1)'
          },{
            label: dataLabels[2],
            data: [{
              x: 15, 
              y: 34, 
              r: 15
            }, {
              x: 30,
              y: 19,
              r: 14
            }],
            backgroundColor: 'rgba(54, 162, 235, 0.6)',
            borderCOlor: 'rgba(54, 162, 235, 1)'
          },{
            label: dataLabels[3],
            data: [{
              x: 25,
              y: 37,
              r: 9
            }, {
              x: 24,
              y: 13,
              r: 4
            }],
            backgroundColor: 'rgba(255, 206, 86, 0.6)',
            borderColor: 'rgba(255, 206, 86, 1)'
          },{
            label: dataLabels[4],
            data: [{
              x: 22,
              y: 31,
              r: 5
            }, {
              x: 32,
              y: 12,
              r: 19
            }],
            backgroundColor: 'rgba(75, 192, 192, 0.6)',
            borderColor: 'rgba(75, 192, 192, 1)'
          },{
            label: dataLabels[5],
            data: [{
              x: 38,
              y: 14,
              r: 5
            }, {
              x: 21,
              y: 38,
              r: 7
            }],
            backgroundColor: 'rgba(153, 102, 255, 0.6)',
            borderColor: 'rgba(153, 102, 255, 1)'
          },{
            label: dataLabels[6],
            data: [{
              x: 13,
              y: 26,
              r: 14
            }, {
              x: 28,
              y: 8,
              r: 9
            }],
            backgroundColor: 'rgba(255, 159, 64, 0.6)',
            borderColor: 'rgba(255, 159, 64, 1)'
          },{
            label: dataLabels[7],
            data: [{
              x: 20,
              y: 10,
              r: 13
            }, {
              x: 40,
              y: 16,
              r: 28
            }],
            backgroundColor: 'rgba(0, 0, 0, 0.6)',
            borderColor: 'rgba(0, 0, 0, 1)'
          },{
            label: dataLabels[8],
            data: [{
              x: 23,
              y: 25,
              r: 17
            }, {
              x: 33,
              y: 12,
              r: 12
            }],
            backgroundColor: 'rgba(100, 0, 30, 0.6)',
            borderColor: 'rgba(100, 0, 30, 1)'
          },{
            label:dataLabels[9],
            data: [{
              x: 4,
              y: 25,
              r: 5
            }, {
              x: 23,
              y: 17,
              r: 17
            }],
            backgroundColor: 'rgba(100, 255, 30, 0.6)',
            borderColor: 'rgba(100, 255, 30, 1)'
          },{
            label:dataLabels[10],
            data: [{
              x: 15,
              y: 7,
              r: 6
            }, {
              x: 29,
              y: 12,
              r: 18
            }],
            backgroundColor: 'rgba(33, 136, 143, 0.6)',
            borderColor: 'rgba(33, 136, 143, 1)'
          },{
            label:dataLabels[11],
            data: [{
            }],
            backgroundColor: 'rgb(99, 255, 132)'
          }]
        };

        const image = new Image();
        image.src = 'bilder/Karte.png';
        const ctx = document.getElementById('myChart-map');
        var canvasWidth = ctx.getBoundingClientRect().width;
        var canvasHeight = ctx.getBoundingClientRect().height;

        console.log(canvasWidth + ", " + canvasHeight);
        // image größe: 5102 x 2551

        const drawImage = {
          id: 'customBackgroundImg',
          beforeDraw (chart, args, options) {
            const {ctx, chartArea: {top, bottom, left, right, width, height}, scales: {x, y}} = chart;
            ctx.save();

            ctx.drawImage (image, 29, 69, 1186, 568);
            ctx.restore();
          }
        };

        const config = {
            type: 'bubble',
            data,
            options: {
              plugins: {
                  title: {
                    display: true,
                    text: ['Übersichtskarte über die Verteilung von Umspannwerken auf die Frequenzstufen'],
                    font: {
                      size: 24
                    }, 
                    padding: {
                      top: 10,
                      bottom: 30
                  }
                  },
                  zoom: {
                  pan: {
                    enabled: true,
                    mode: 'xy'
                  },
                  limits: {
                    xy: {minRange: 40}
                  },
                  zoom: {
                    wheel: {
                      enabled: true,
                      speed: 0.05,
                    },
                    mode: 'xy'
                  }                    
                },
                legend: {
                  position: 'right',
                  align: 'left'
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
                  min: 0,
                  max: 40,
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
                  min: 0,
                  max: 45,
                  grid: {
                      display: false,
                      drawOnChartArea: false,
                      drawBorder: false
                  }
                }
              }
            }, 
            plugins: [drawImage]
        };

        const chartMap = new Chart(
            document.getElementById('myChart-map'),
            config
        );

        function clickHandler(click) {
            const points = chartMap.getElementsAtEventForMode(click, 'nearest' , {intersect: true}, true);
            if(points.length){
                const firstPoint = points [0];
                const value = chartMap.data.datasets [firstPoint.datasetIndex].data[firstPoint.index];
                location.href = "reporting/Umspannwerke-Detail.php";
            }
        };  

        chartMap.canvas.onclick = clickHandler;

      </script>
    </div>

  </body>

</html>


<?php 
}else {
   header("Location: login.php");
}
 ?>