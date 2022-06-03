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
          body {
              font-family: 'Open Sans', sans-serif;
              font-family: 'Signika', sans-serif;
              background-size: cover;
          }

          .schrift1 {
            color: white;
            display: flex;
            justify-content: center;
            text-align: center;
            font-size: 60px;
            user-select: none;
          }

          .lineChart-monitoring {
              display: flex;
              justify-content: flex-start;
              margin-top: 100px;
              margin-left: 100px;
              margin-right: 100px; 
          }

          .chartBox-monitoring {
              display: flex;
              justify-content: space-between;
              width: 100%;
              padding: 10px;
              border-radius: 20px;
              border: solid 3px white;
              background: white;
          }

          .tablePlace {
            margin-top: 50px;
            margin-left: 10px;
            width: 40%;
          }

          .chartPlace {            
            margin-top: 25px;
            margin-left: 50px;
            width: 75%;
          }      

          .inputPlace {
            margin-top: 50px;
          }

          .table-fixed thead,
          .table-fixed thead>th {
              background: #0250ac;
              color: #fff;
          }

          .table-fixed>thead>tr>th {
              border: 0 !important;
          }

          .table-fixed>tbody>tr>td:last-child {
              border-right: 0;
          }

          .table-fixed tbody td,
          .table-fixed thead>tr>th {
              font-size: 14px;
              padding-left: 20px !important;
          }

          thead th, td {
              width: 20%;
          }

          .table-fixed tbody {
              display: block;
              height: 800px;
              overflow-y: auto;
          }

          .table-fixed tr {
              display: block;
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
      <div class="tablePlace"></div>
      <div class="chartPlace">
        <canvas id="myChart-monitoring"></canvas><br>
        <div class="inputPlace">
          <input onchange="filterData()" type="date" min="2020-01-01" max="2021-01-01" id="startdate" value="2020-01-01"> 
          <input onchange="filterData()" type="date" min="2020-01-01" max="2021-01-01" id="enddate" value="2020-02-01">  
        </div>
      </div>    
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
        //Setup für ChartJS Diagramm
        //Befüllung mit ersten Daten anhand von festgelegtem Start/Enddatum beim Neuladen der Seite
        var labels = ["Datum [yyyy:mm:dd] Uhrzeit [hh:mm]", "Stufe 0: > 49,0 Hz", "Stufe 1: 49,0 Hz" , "Summer aller 10 Stufen: 49,0 Hz bis 48,1 Hz", "Stufe 12: ≤ 48,0 Hz"];
       
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
                radius: 4,
            },{
                label: 'Stufe 1: 49,0 Hz',
                data: step,
                backgroundColor: 'rgba(255, 26, 104, 0.2)',
                borderColor: 'rgba(255, 26, 104, 1)',
                tension: 0.4,
                radius: 4,
            },{
                label: 'Summer aller 10 Stufen: 49,0 Hz bis 48,1 Hz',
                data: stepSum,
                backgroundColor: 'rgba(26, 255, 104, 0.2)',
                borderColor: 'rgba(26, 255, 104, 1)',
                tension: 0.4,
                radius: 4,
            },{
                label: 'Stufe 12: ≤ 48,0 Hz',
                data: lastStep,
                backgroundColor: 'rgba(255, 159, 64, 0.2)',
                borderColor: 'rgba(255, 159, 64, 1)',
                tension: 0.4,
                radius: 4,
            }]
        };

        //Tooltip Konfigurationen
        //Tooltip = Hoverbild, wenn man mit der Maus über die Punkte des Diagramms hovert
        const titleBefore = (tooltipItems) => {
          return "Zeitpunkt";
        };
        const lableBefore = (tooltipItems) => {
          return "----------------";
        };
        const labelAfter = (tooltipItems) => {
          return "MW";
        };

        const ctx = document.getElementById('myChart-monitoring');

        const lineDraw = {
          id: 'lineDraw',
          beforeDatasetsDraw(chart, args, pluginOptions) {
            const {ctx, chartArea: {top, bottom, left, right} }= chart;
            
            //lines(123, 123, 1);

            function lines(x, y, dataSet) {
              ctx.beginPath();
              ctx.StrokeStyle = 'rgba(102, 102, 102, 0.8';
              ctx.lineWidth = 1;
              ctx.setLineDash([5, 5]);
              ctx.moveTo(left, chart.getDatasetMeta(dataSet).data[y].y);
              ctx.lineTo(chart.getDatasetMeta(dataSet).data[x].x, chart.getDatasetMeta(dataSet).data[y].y);
              ctx.lineTo(chart.getDatasetMeta(dataSet).data[x].x, bottom);
              ctx.stroke();
              ctx.closePath();
              ctx.restore();

            };

            ctx.setLineDash([]);
          }
        };

       
        console.log(lineDraw);

        //Konfigurationen für ChartJS-Diagramm
        const config = {
            type: 'line',
            data,
            options: {
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
              },
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
                    mode: 'x'
                  },
                  limits: {
                    x: {minRange: 20}
                  },
                  zoom: {
                    wheel: {
                      enabled: true,
                      speed: 0.05,
                    },
                    mode: 'x'
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
              }           
            },
            plugins: [lineDraw]
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
                    deleteTable();
                    getData(data);
                    updateChart();
                }
            });
        };

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
        };

        //Funktion legt die überschriebenen Arrays zur Darstellung des Diagramms als neue Datensätze für die Darstellung des Diagramms fest
        //Aktualisiert das Diagramm --> Diagramm wird mir neuen Werte generiert
        function updateChart() {

          chartMonitoring.data.labels = dates;
          chartMonitoring.data.datasets[0].data = firstStep;
          chartMonitoring.data.datasets[1].data = step;
          chartMonitoring.data.datasets[2].data = stepSum;
          chartMonitoring.data.datasets[3].data = lastStep;
          chartMonitoring.update();
        
          createTable();
        };


        //Erstellung der Tabelle
        function createTable() {

          const chartBox = document.querySelector('.tablePlace');
          const tableDiv = document.createElement('DIV');
          tableDiv.setAttribute ('id', 'tableDiv');
          tableDiv.setAttribute ('class', 'col-xs-7 table-bordered table-striped table-condensed table-fixed');

          const table = document.createElement('TABLE');
          table.classList.add('chartjs-table');

          const thead = table.createTHead();
          table.classList.add('chartjs-thead');
          thead.insertRow(0);
          
          thead.rows[0].insertCell(0).innerText = labels[0];
          thead.rows[0].insertCell(1).innerText = labels[1];
          thead.rows[0].insertCell(2).innerText = labels[2];
          thead.rows[0].insertCell(3).innerText = labels[3];
          thead.rows[0].insertCell(4).innerText = labels[4];

          thead.rows[0].setAttribute('id', 'tableRow0');

          //add table body
          const tbody = table.createTBody();
          tbody.classList.add('chartjs-tbody');                  

          //console.log(data.datasets);

          for (let i = 0; i < dates.length; i++) {
                tbody.insertRow(i);
                tbody.rows[i].insertCell(-1).innerText = dates[i];
          };

          for (let i = 0; i < firstStep.length; i++) {
            if (firstStep[i] == null) { 
              tbody.rows[i].insertCell(-1).innerText = firstStep[i];
            } else {
              tbody.rows[i].insertCell(-1).innerText = firstStep[i] + " MW";
            }
          };

          for (let i = 0; i < step.length; i++) {
            if (step[i] == null) { 
              tbody.rows[i].insertCell(-1).innerText = step[i];
            } else {
              tbody.rows[i].insertCell(-1).innerText = step[i] + " MW";
            }
          };

          for (let i = 0; i < stepSum.length; i++) {
            if (stepSum[i] == null) { 
              tbody.rows[i].insertCell(-1).innerText = stepSum[i];
            } else {
              tbody.rows[i].insertCell(-1).innerText = stepSum[i] + " MW";
            }
          };

          for (let i = 0; i < lastStep.length; i++) {
            if (lastStep[i] == null) { 
              tbody.rows[i].insertCell(-1).innerText = lastStep[i];
            } else {
              tbody.rows[i].insertCell(-1).innerText = lastStep[i] + " MW";
            }
          };

          for (let i = 0; i < dates.length; i++) {
            var rowID = "tableRow" + (i+1);
            tbody.rows[i].setAttribute('id', rowID);
          }
          
          chartBox.appendChild(tableDiv);
          tableDiv.appendChild(table);
        };

        createTable();

        function deleteTable() {
   
          document.getElementById("tableDiv").remove();
        };

        /*
        function mousemoveHandler(mousemove) {
          
          const points =  chartMonitoring.getElementsAtEventForMode(mousemove, 'nearest', {intersect: true}, true);
          
          const trbody = document.querySelectorAll('tbody tr');
         
          trbody.forEach((tr) => {
            tr.style.backgroundColor = 'transparent';
          });

          if (points[0]) {
            const dataSet = points[0].datasetIndex;         

            if (dataSet == 0) {
              const index = points[0].index;
              const rowID = "tableRow" + (index + 1);
              const color = chartMonitoring.data.datasets[0].backgroundColor;

              const element = document.getElementById(rowID);
              element.scrollIntoView(true);

              trbody[index].style.backgroundColor = color;
            };

            if (dataSet == 1) {
              const index = points[0].index;
              const rowID = "tableRow" + (index + 1);
              const color = chartMonitoring.data.datasets[1].backgroundColor;
            
              const element = document.getElementById(rowID);
              element.scrollIntoView(true);

              trbody[index].style.backgroundColor = color;
            };

            if (dataSet == 2) {
              const index = points[0].index;
              const rowID = "tableRow" + (index + 1);
              const color = chartMonitoring.data.datasets[2].backgroundColor;

              const element = document.getElementById(rowID);
              element.scrollIntoView(true);

              trbody[index].style.backgroundColor = color;
            };

            if (dataSet == 3) {
              const index = points[0].index;
              const rowID = "tableRow" + (index + 1);
              const color = chartMonitoring.data.datasets[3].backgroundColor;

              const element = document.getElementById(rowID);
              element.scrollIntoView(true);

              trbody[index].style.backgroundColor = color;
            };
          
          };
        };

       // chartMonitoring.canvas.onmousemove = mousemoveHandler;
       */
      
        function clickHandler(click) {
            const points = chartMonitoring.getElementsAtEventForMode(click, 'nearest' , {intersect: true}, true);
            
            const trbody = document.querySelectorAll('tbody tr');
         
          trbody.forEach((tr) => {
            tr.style.backgroundColor = 'transparent';
          });

          if (points[0]) {
            const dataSet = points[0].datasetIndex;         

            if (dataSet == 0) {
              const index = points[0].index;
              const rowID = "tableRow" + (index + 1);
              const color = chartMonitoring.data.datasets[0].backgroundColor;

              const element = document.getElementById(rowID);
              element.scrollIntoView(true);

              trbody[index].style.backgroundColor = color;
            };

            if (dataSet == 1) {
              const index = points[0].index;
              const rowID = "tableRow" + (index + 1);
              const color = chartMonitoring.data.datasets[1].backgroundColor;
            
              const element = document.getElementById(rowID);
              element.scrollIntoView(true);

              trbody[index].style.backgroundColor = color;
            };

            if (dataSet == 2) {
              const index = points[0].index;
              const rowID = "tableRow" + (index + 1);
              const color = chartMonitoring.data.datasets[2].backgroundColor;

              const element = document.getElementById(rowID);
              element.scrollIntoView(true);

              trbody[index].style.backgroundColor = color;
            };

            if (dataSet == 3) {
              const index = points[0].index;
              const rowID = "tableRow" + (index + 1);
              const color = chartMonitoring.data.datasets[3].backgroundColor;

              const element = document.getElementById(rowID);
              element.scrollIntoView(true);

              trbody[index].style.backgroundColor = color;
            };
          
          };
        };  


        
        chartMonitoring.canvas.onclick = clickHandler;


    </script>

</div>

</body>

</html>


<?php 
} else {
   header("Location: login.php");
}
 ?>