<?php 
  session_start();

  include '../db_config.php';

  if (isset($_SESSION['user_id']) && isset($_SESSION['user_email'])) { 
?>

<!DOCTYPE html>

<html lang="de">

    <head>

        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Reporting</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&family=Signika:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <link rel="stylesheet" href="../stylesheet.css">

    </head>

    <body background="../bilder/background.png">
    
    <div class="error1-reporting">
      <?php

         //Automatische Festlegung des letzten Jahres als Datum der ersten Darstellung des Diagramms
        $year = date("Y") - 1;

        //erstmaliges Laden der Reporting-Werte aus der Datenbank für das Neuladen der Seite
        try{
          $sql = "SELECT * FROM $tableRep WHERE $tableRepTime = '$year'";
          $result = $conn -> query($sql);

          if ($result -> rowCount() > 0) {
            $frequency = array();

             //Befüllen der Arrays für die Darstellung des Diagramms mit den Werten aus der Datenbank
            while ($row = $result -> fetch()) {
              $frequency[0] = $row["> 49,0"];
              $frequency[1] = $row["49,0"];
              $frequency[2] = $row["48,9"];
              $frequency[3] = $row["48,8"];
              $frequency[4] = $row["48,7"];
              $frequency[5] = $row["48,6"];
              $frequency[6] = $row["48,5"];
              $frequency[7] = $row["48,4"];
              $frequency[8] = $row["48,3"];
              $frequency[9] = $row["48,2"];
              $frequency[10] = $row["48,1"];
              $frequency[11] = $row["<= 48,0"];
            }
            unset($result);
          } else {
            echo "Es wurden keine gültigen Werte gefunden.";
            $frequency[] = 0;
          }
        } 
        catch (PDOException $e) {
          die("Connection failed : ". $e->getMessage());
        }
        unset ($conn); 
        
      ?>
     </div>


    <!-- Überschrift & Menüpunkte -->
    <h1 class="schrift1">UFLA-Reporting Übersicht<br>Umspannwerke der Stufe x</h1>

    <div class="home-icon">
      <a href="../index.php">
          <img src="../bilder/home_icon.png" height="50px" width="50px" alt="Startmenü">
      </a>
    </div>

    <div class="back-icon">
        <a href="javascript:history.back()">
            <img src="../bilder/back_icon.png" height="50px" width="50px" alt="Zurück">
        </a>
    </div>

    <div class="logout-icon">
        <a href="../logout.php">
            <img src="../bilder/logout_icon.png" height="50px" width="50px" alt="Ausloggen">
        </a>
    </div>


    <!-- Erstellung von Dropdown Menü für Auswahl des Jahres -->
    <!-- Eingabeoptionen werden im Javaskript festgelegt -->
    <!-- Führt bei Eingabe die Funktion "getSelectValue()" aus -->
    <div class="reporting-dropdown">
      <span class="reporting-dropdown-text">Jahr:</span>
      <select id='year-dropdown' onchange="getSelectValue();">
      </select>
    </div>
    

    <div class="barChart-reporting">
    
    <!-- Erstellung des ChartJS Diagramms und der beiden Kalender für Start/Enddatum -->
    <div class="chartBox-reporting">
        <canvas id="chartReporting"></canvas>
    </div>


    <!-- Laden für die Erstellung des Diagramms und Übertragung der eingegeben Werte relevanter Skripte -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js"></script>
    
    <script>
    
        //Setup für ChartJS Diagramm
        //Befüllung mit ersten Daten anhand von festgelegtem Start/Enddatum beim Neuladen der Seite
        var frequency = <?php echo json_encode($frequency); ?>;
        var linkURL = <?php echo json_encode($linkRepUW_Detail); ?>;
        var dataLabels = ['> 49,0', '49,0', '48,9', '48,8', '48,7', '48,6', '48,5', '48,4', '48,3', '48,2', '48,1', '<= 48,0'];
        
        const data = {
                labels: dataLabels,
                datasets: [{

                    data: [
                        {xAxis: dataLabels[0], yAxis: {fstep: frequency[0], link: linkURL, step: dataLabels[0]} },
                        {xAxis: dataLabels[1], yAxis: {fstep: frequency[1], link: linkURL, step: dataLabels[1]} },
                        {xAxis: dataLabels[2], yAxis: {fstep: frequency[2], link: linkURL, step: dataLabels[2]} },
                        {xAxis: dataLabels[3], yAxis: {fstep: frequency[3], link: linkURL, step: dataLabels[3]} },
                        {xAxis: dataLabels[4], yAxis: {fstep: frequency[4], link: linkURL, step: dataLabels[4]} },
                        {xAxis: dataLabels[5], yAxis: {fstep: frequency[5], link: linkURL, step: dataLabels[5]} },
                        {xAxis: dataLabels[6], yAxis: {fstep: frequency[6], link: linkURL, step: dataLabels[6]} },
                        {xAxis: dataLabels[7], yAxis: {fstep: frequency[7], link: linkURL, step: dataLabels[7]} },
                        {xAxis: dataLabels[8], yAxis: {fstep: frequency[8], link: linkURL, step: dataLabels[8]} },
                        {xAxis: dataLabels[9], yAxis: {fstep: frequency[9], link: linkURL, step: dataLabels[9]} },
                        {xAxis: dataLabels[10], yAxis: {fstep: frequency[10], link: linkURL, step: dataLabels[10]} },
                        {xAxis: dataLabels[11], yAxis: {fstep: frequency[11], link: linkURL, step: dataLabels[11]} }
                    ],

                    backgroundColor: [
                        'rgba(255, 26, 104, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)',
                        'rgba(0, 0, 0, 0.2)',
                        'rgba(100, 0, 30, 0.2)',
                        'rgba(100, 255, 30, 0.2)',
                        'rgba(33, 136, 143, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 26, 104, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)',
                        'rgba(0, 0, 0, 1)',
                        'rgba(100, 0, 30, 1)',
                        'rgba(100, 255, 30, 1)',
                        'rgba(33, 136, 143, 1)'
                    ],
                    borderWidth: 1
                }],
            };

            //Tooltip Konfigurationen
            //Tooltip = Hoverbild, wenn man mit der Maus über die Säulen des Diagramms hovert
            const titleBefore = (tooltipItems) => {
                return "Frequenzstufe";
            }
            const titleAfter = (tooltipItems) => {
                return "Hz";
            }
            const lableBefore = (tooltipItems) => {
                return "----------------";
            }
            const labelAfter = (tooltipItems) => {
                return "MW";
            }

            //Konfigurationen für ChartJS-Diagramm
            const config = {
                type: 'bar',
                data,
                options: {
                    parsing: {
                        xAxisKey: 'xAxis',
                        yAxisKey: 'yAxis.fstep'
                    },
                    plugins: {
                        title: {
                            display: true,
                            text: ['Jahresmittel der Abwurfleistungen in den', 'Umspannwerken der Stufe x'],
                            font: {
                                size: 24
                            }
                        },
                        legend: {
                            display: false
                        },
                        tooltip: {
                            yAlign: 'bottom',
                            titleAlign: 'center',
                            bodyAlign: 'center',
                            displayColors: false,
                            callbacks: {
                                beforeTitle: titleBefore,
                                afterTitle: titleAfter,
                                beforeLabel: lableBefore,
                                afterLabel: labelAfter
                                }
                        }
                    },
                    scales: {
                        y: {
                            title: {
                                display: true,
                                text: 'Summe aktivierter Abwurfleistungen [MW]',
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
                                text: 'Umspannwerke',
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
    
        const ctx = document.getElementById('chartReporting');
        
        //Render Block für ChartJS Diagramm
        const chartReporting = new Chart(
        ctx,
        config
        );

        //Erzeugung Dropdown Menü für Jahresauswahl
        //Beginnend immer mit dem Jahr 2017
        //erweitert sich selbstständig nach Jahreswechsel

        let yearDropdown = document.getElementById('year-dropdown');

        let currentYear = new Date().getFullYear();
        let earliestYear = 2017;
        
        while (currentYear >= earliestYear) {
            let yearOption = document.createElement('option');
            yearOption.text = currentYear;
            yearOption.value = currentYear;
            yearDropdown.add(yearOption);
            currentYear -= 1;
        }

        //Funktion wird Aufgerufen, wenn in dem Dropdown-Menü eine Eingabe getätigt wird
        //Entnimmt dem Dropdown_Menü den eingegebenen Wert (Jahr) und übergibt sie an "getDataRep.php"
        //Führt im Anschluss automatisch die Funktionen "getData()" und "updateChart()" aus
        function getSelectValue() {
            var yearSelected = document.getElementById('year-dropdown').value;

            $.ajax({
                async: true,
                url: "../getDataRep.php",
                data: {
                yearSelected,
                },
                datatype: "json",
                type: "POST",
                success: function(data) {
                    getData(data);
                    updateChart();
                }
            });
        }

        //Funktion nimmt die Werte entgegen, welche von "getDataRep.php" bereit gestellt werden
        //Werte sind neue Reporting-Kennzahlen, welche aus der Datenbank entnommen würden
        //globale Arrays zur Darstellung des Diagramms werden mit neuen Werten überschrieben
        function getData(data) {
            window.frequency = $.parseJSON(data);
        }

        //Funktion legt die überschriebenen Arrays zur Darstellung des Diagramms als neue Datensätze für die Darstellung des Diagramms fest
        //Aktualisiert das Diagramm --> Diagramm wird mir neuen Werte generiert
        function updateChart() {
            var newData = [
                {xAxis: dataLabels[0], yAxis: {fstep: frequency[0], link: linkURL, step: dataLabels[0]} },
                {xAxis: dataLabels[1], yAxis: {fstep: frequency[1], link: linkURL, step: dataLabels[1]} },
                {xAxis: dataLabels[2], yAxis: {fstep: frequency[2], link: linkURL, step: dataLabels[2]} },
                {xAxis: dataLabels[3], yAxis: {fstep: frequency[3], link: linkURL, step: dataLabels[3]} },
                {xAxis: dataLabels[4], yAxis: {fstep: frequency[4], link: linkURL, step: dataLabels[4]} },
                {xAxis: dataLabels[5], yAxis: {fstep: frequency[5], link: linkURL, step: dataLabels[5]} },
                {xAxis: dataLabels[6], yAxis: {fstep: frequency[6], link: linkURL, step: dataLabels[6]} },
                {xAxis: dataLabels[7], yAxis: {fstep: frequency[7], link: linkURL, step: dataLabels[7]} },
                {xAxis: dataLabels[8], yAxis: {fstep: frequency[8], link: linkURL, step: dataLabels[8]} },
                {xAxis: dataLabels[9], yAxis: {fstep: frequency[9], link: linkURL, step: dataLabels[9]} },
                {xAxis: dataLabels[10], yAxis: {fstep: frequency[10], link: linkURL, step: dataLabels[10]} },
                {xAxis: dataLabels[11], yAxis: {fstep: frequency[11], link: linkURL, step: dataLabels[11]} }
            ];

            chartReporting.data.datasets[0].data = newData;
            chartReporting.update();
        }


        function clickHandler(click){
            const points = chartReporting.getElementsAtEventForMode(click, 'nearest' , {intersect: true}, true);
            if(points.length){
                const firstPoint = points [0];
                const value = chartReporting.data.datasets [firstPoint.datasetIndex].data[firstPoint.index];
                location.href = value.yAxis.link;
            }
        }

        ctx.onclick = clickHandler
    

    </script>
  </div>
  

</body>

<?php 
}else {
   header("Location: login.php");
}
 ?>