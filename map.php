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
        canvas {
          background-image: url(bilder/Karte.png);
          background-size: 100% 100%;
          border-radius: 20px;
        }

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
      
      <script>

        var linkURL = <?php echo json_encode($linkRepUW_Detail); ?>;
        var dataLabels = ['> 49,0 Hz', '49,0 Hz', '48,9 Hz', '48,8 Hz', '48,7 Hz', '48,6 Hz', '48,5 Hz', '48,4 Hz', '48,3 Hz', '48,2 Hz', '48,1 Hz', '≤ 48,0 Hz'];
        
        const data = {

labels: ['labels'],
datasets: [{
            label: 'Stufe 1 = 49,0 Hz',
            data: [
                {x: 2.48,       y: 2.33,     r: 5, name: 'Gos - Gospergrün'},
                {x: 2.88,       y: 2.75,     r: 5, name: 'Lö - Lößnitz'},
                {x: 3.1,        y: 2.2,      r: 5, name: 'Crd - Crottendorf'},
                {x: 2.94,       y: 4.6,      r: 5, name: 'Bu - Burgstädt'},
                {x: 3.6,        y: 3.8,      r: 5, name: 'Cla - Clausnitz'},
                {x: 3.1,        y: 5.15,     r: 5, name: 'MitS - Mittweida/Süd'},
                {x: 3.15,       y: 5.45,     r: 5, name: 'Krt - Kriebethal'},
                {x: 4.67,       y: 8.7,      r: 5, name: 'Kra - Krauschwitz'},
                {x: 4.45,       y: 7.75,     r: 5, name: 'Lo - Lohsa'},
                {x: 3.61,       y: 10.45,    r: 5, name: 'Uck - Uckro'},
                {x: 3.6,        y: 9.15,     r: 5, name: 'DoW - Doberlug/West'},
                {x: 3.26,       y: 5.455,    r: 5, name: 'Et - Etzdorf'},
                {x: 2.87,       y: 6.4,      r: 5, name: 'Se - Sermuth'},
                {x: 2.375,      y: 5.57,     r: 5, name: 'Trö - Tröglitz'},
                {x: 2.375,      y: 7.75,     r: 5, name: 'Sk - Schkeuditz'},
                {x: 1.93,       y: 7.85,     r: 5, name: 'Wlb - Wansleben'},
                {x: 2.51,       y: 8.93,     r: 5, name: 'BiO - Bitterfeld/Ost'},
                {x: 1.9,        y: 9.8,      r: 5, name: 'Ad - Aderstedt'},
                {x: 1.55,       y: 9.8,      r: 5, name: 'Fr - Frose'},

                //defenieren die Größe des Diagramms
                {x: 0,y: 0, r: 0 },
                {x: 0,y: 12, r: 0 },
                {x: 6,y: 0, r: 0 }
        ],
        
            backgroundColor: [
                'rgba(0, 15, 110, 1)',
                
        ],
            borderColor: [
                'rgba(0, 15, 60, 1)',
                
        ],
},
{
        label: 'Stufe 2 = 48,9 Hz',
            data: [
                  {x: 2.5,      y: 3.5,      r: 5, name: 'WerS - Werdau/Süd'},
                  {x: 3.8,      y: 7.65,     r: 5, name: 'Or - Ortrand'},
                  {x: 4.11,     y: 9,        r: 5, name: 'Grä - Großräschen'},
                  {x: 3.5,      y: 8.45,     r: 5, name: 'Lie - Liebenwerda'},
                  {x: 3.89,     y: 11.10,    r: 5, name: 'Lü - Lübben'},
                  {x: 4.37,     y: 10.85,    r: 5, name: 'Pei - Peitz'},
                  {x: 1.54,     y: 7.55,     r: 5, name: 'Ar - Artern'},
                  {x: 2.95,     y: 2,        r: 5,  name: 'Ritt - Rittersgrün'},
               

               //defenieren die Größe des Diagramms
               {x: 0,y: 0, r: 0 },
                {x: 0,y: 12, r: 0 },
                {x: 6,y: 0, r: 0 }
            ],
            backgroundColor: [
                'rgba(0, 10, 170, 0.3)',
                
        ],
            borderColor: [
            'rgba(0, 10, 170, 0.3)',
                
        ],
        borderWidth: 1,
        clip: {left: false, top: false, right: false, bottom: false}
},
{
        label: 'Stufe 3 = 48,8 Hz',
            data: [
                {x: 2.47,       y: 2.88,     r: 5, name: 'Rei - Reichenbach'},
                {x: 2.92,       y: 2.88,     r: 5, name: 'Zw - Zwönitz'},
                {x: 2.87,       y: 4.4,      r: 5, name: 'Lim - Limbach'},
                {x: 3.07,       y: 2.49,     r: 5, name: 'Sche - Scheibenberg'},
                {x: 3.13,       y: 2.04,     r: 5, name: 'Neu - Neudorf'},
                {x: 3.14,       y: 2.88,     r: 5, name: 'Fab - Falkenbach'},
                {x: 3.46,       y: 4.73,     r: 5, name: 'Fro - Freiberg/Ost'},
                {x: 2.78,       y: 2.75,     r: 5, name: 'SchN - Schneeberg/Nord'},
                {x: 3.34,       y: 8.78,     r: 5, name: 'Fbg - Falkenberg'},
                {x: 3,          y: 8,        r: 5, name: 'Lab - Langenreichenbach'},
                {x: 2.62,       y: 7.3,      r: 5, name: 'Eng - Engeldorf'},

                //defenieren die Größe des Diagramms
                {x: 0,y: 0, r: 0 },
                {x: 0,y: 12, r: 0 },
                {x: 6,y: 0, r: 0 }
            ],
            backgroundColor: [
                'rgba(0, 170, 170, 0.3)',
                
        ],
            borderColor: [
            'rgba(0, 170, 170, 0.3)',
                
        ],
        borderWidth: 1,
        clip: {left: false, top: false, right: false, bottom: false}
},
{
        label: 'Stufe 4 = 48,7 Hz',
            data: [
                {x: 2.33,      y: 2.06,       r: 5, name:  'PnB - Plauen B'},
                {x: 2.55,      y: 1.87,       r: 5, name:  'Fst - Falkenstein'},   
                {x: 2.6,       y: 2,          r: 5, name:  'AuV - Auerbach/Vogtland'},
                {x: 3.15,      y: 3.4,        r: 5, name:  'ZS - Zschopau/Süd'},
                {x: 4.11,      y: 7.65,       r: 5, name:  'Bd- Bernsdorf'  },              
                {x: 4.16,      y: 8.26,       r: 5, name:  'La - Lauta' },
                {x: 3.4,       y: 9.45,       r: 5, name:  'Hbg - Herzberg'},
                {x: 3.12,      y: 10,         r: 5, name:  'Je - Jessen'},
                {x: 4.6,       y: 9.9,        r: 5, name:  'Fo - Forst'},
                {x: 4.55,      y: 9.4,        r: 5, name:  'Döb - Döbern'},
                {x: 4.4,       y: 8.955,      r: 5, name:  'Spr - Spremberg'},
                {x: 2.27,      y: 8.1,        r: 5, name: 'Reu - Reußen'},
                {x: 1.4,       y: 7,          r: 5, name: 'Hel - Heldrungen'},
                {x: 2.22,      y: 9.75,       r: 5, name: 'KötN - Köthen'},
                {x: 2.235,     y: 10.2,       r: 5, name: 'Su - Susigke'},
                {x: 1.67,      y: 8.65,       r: 5, name: 'KlmS - Klostermanfeld/Süd'},
                {x: 1.319,     y: 9.85,       r: 5, name: 'Th - Thale'},
                {x: 1.322,     y: 8.15,       r: 5, name: 'Rla - Roßla'},

                //defenieren die Größe des Diagramms
                {x: 0,y: 0, r: 0 },
                {x: 0,y: 12, r: 0 },
                {x: 6,y: 0, r: 0 }
            ],
            backgroundColor: [
                'rgba(0, 170, 10, 0.3)',
                
        ],
            borderColor: [
            'rgba(0, 170, 10, 0.3)',
                
        ],
        borderWidth: 1,
        clip: {left: false, top: false, right: false, bottom: false}
},
{
        label: 'Stufe 5 = 48,6 Hz',
            data: [
                {x: 2.31,      y: 2.15,      r: 5,  name: 'PnA - Plauen A'},
                {x: 2.44,      y: 2.4,       r: 5,  name: 'Her - Herlasgrün'},
                {x: 2.52,      y: 0.87,      r: 5,  name: 'FrH - Freiberg Hütte (SST)'},   
                {x: 2.7,       y: 3,         r: 5,  name: 'Si - Silberstraße' },
                {x: 2.85,      y: 3.66,      r: 5,  name: 'Ger - Gersdorf'},
                {x: 2.6,       y: 4.285,     r: 5,  name: 'MeeO - Merrane/Ost'},
                {x: 2.91,      y: 4.285,     r: 5,  name: 'Rö - Röhrsdorf'},
                {x: 3.26,      y: 4.485,     r: 5,  name: 'Fra - Frankenberg'},
                {x: 3.375,     y: 4.682,     r: 5,  name: 'FrW - Freiberg/West'},
                {x: 3.5,       y: 4.682,     r: 5,  name: 'FrH - Freiberg Hütte (SST)'},
                {x: 3.33,      y: 3.4,       r: 5,  name: 'Po - Pockau'},
                {x: 3.29,      y: 3.23,      r: 5,  name: 'Mab - Marienberg'},
                {x: 2.63,      y: 7.55,      r: 5,  name: 'Ta - Taucha'},
                {x: 2.25,      y: 7.05,      r: 5,  name: 'Le - Lennewitz'},
                

                //defenieren die Größe des Diagramms
                {x: 0,y: 0, r: 0 },
                {x: 0,y: 12, r: 0 },
                {x: 6,y: 0, r: 0 }
            ],
            backgroundColor: [
                'rgba(150, 200, 50, 0.6)',
                
        ],
            borderColor: [
            'rgba(150, 200, 50, 0.6)',
                
        ],
        borderWidth: 1,
        clip: {left: false, top: false, right: false, bottom: false}
},
{
        label: 'Stufe 6 = 48,5 Hz',
            data: [
                {x: 3.31,       y: 4.34,       r: 5, name: 'Oed - Oederan'},
                {x: 4.32,       y: 9.9,        r: 5, name:  'Cot - Cottbus'},
                {x: 3.95,       y: 9.9,        r: 5, name:  'Ca - Calau'},
                {x: 2.53,       y: 7.73,       r: 5, name: 'See - Seehausen'},
                {x: 1.5,        y: 7.82,       r: 5, name: 'Ob - Oberröblingen'},

            ],
            backgroundColor: [
                'rgba(255, 255, 0, 0.6)',
                
        ],
            borderColor: [
            'rgba(255, 255, 0, 0.6)',
                
        ],
        borderWidth: 1,
        clip: {left: false, top: false, right: false, bottom: false}
},
{
        label: 'Stufe 7 = 48,4 Hz',
            data: [
            {x: 2.92,     y: 3.45,    r: 5, name: 'Ndw - Niederwürschnitz'},
            {x: 3,        y: 3.2,     r: 5, name: 'AuE - Auerbach/Erzgebirge'},
            {x: 2.62,     y: 4,       r: 5, name: 'Schl - Schlunzig'},
            {x: 2.9,      y: 2.42,    r: 5, name: 'Schb - Schwarzenberg'},
            {x: 3.15,     y: 2.32,    r: 5, name: 'Bä - Bärenstein'},                      
            {x: 3.8,      y: 9.25,    r: 5, name: 'Fi - Finsterwalde'},
            {x: 4.16,     y: 10.26,   r: 5, name: 'Vet - Vetschau' },
            {x: 2.86,     y: 7.5,     r: 5, name: 'Wu - Wurzen'},
            {x: 2.53,     y: 5.55,    r: 5, name: 'Meu - Meuselwitz'},
            {x: 2.22,     y: 7.75,    r: 5, name: 'Dö - Döllnitz'},
            {x: 1.9,      y: 6.55,    r: 5, name: 'Md - Müncheroda'},
            {x: 1.77,     y: 6.15,    r: 5, name: 'Wi - Wischroda'},
            {x: 1.77,     y: 7,       r: 5, name: 'Rdf - Reinsdorf'},
            {x: 2.6,      y: 9.6,     r: 5, name: 'Rd - Radis'},
            {x: 2.54,     y: 10,      r: 5, name: 'Orb - Oranienbaum'},
            {x: 2.47,     y: 9.3,     r: 5, name: 'WnO - Wolfen/Ost'},
            {x: 1.7,      y: 9.15,    r: 5, name: 'HtN - Hettstedt/Nord'},
            {x: 1.35,     y: 9.14,    r: 5, name: 'Hz - Harzergerode'},
            {x: 1.4,      y: 9.65,    r: 5, name: 'Ri - Rieder'},
            
            

            ],
            backgroundColor: [
                'rgba(255, 180, 0, 0.7)',
                
        ],
            borderColor: [
            'rgba(255, 180, 0, 0.7)',
                
        ],
        borderWidth: 1,
        clip: {left: false, top: false, right: false, bottom: false}
},
{
        label: 'Stufe 8 = 48,3 Hz',
            data: [
               // {x: 4,      y: 5,       r: 12},
               

            ],
            backgroundColor: [
                'rgba(255, 60, 80, 0.7)',
                
        ],
            borderColor: [
            'rgba(255, 60, 80, 0.7)',
                
        ],
        borderWidth: 1,
        clip: {left: false, top: false, right: false, bottom: false}
},
{
        label: 'Stufe 9 = 48,2 Hz',
            data: [
                {x: 2.36,     y: 1.6,     r: 5, name:  'Dro - Drosdorf'},
                {x: 2.62,     y: 1.4,     r: 5, name:  'Kli - Klingenthal'},
                {x: 2.70,     y: 2.1,     r: 5, name:  'Sö - Schönheide'},
                {x: 2.85,     y: 2.36,    r: 5, name:  'AueS - Aue/Süd'},
                {x: 2.87,     y: 4.0,     r: 5, name:  'Obl - Oberlungwitz'},
                {x: 2.53,     y: 4.18,    r: 5, name:  'Cris - Chrimmitschau/Süd'},
                {x: 3.27,     y: 4.3,     r: 5, name:  'FlO - Flöha/Ost'},
                {x: 3.45,     y: 4.0,     r: 5, name:  'BrE - Brand-Erbisdorf'},
                {x: 3.8,      y: 8.45,    r: 5, name:  'Lhm - Lauchhammer/Mitte'},
                {x: 4.03,     y: 8.65,    r: 5, name:  'Sen - Senftenberg'},
                {x: 3.1,      y: 8.8,     r: 5, name:  'To - Torgau'},
                {x: 2.55,     y: 6.85,    r: 5, name:  'Mbg - Markkleeberg'},
                {x: 2.48,     y: 6.1,     r: 5, name:  'Gro - Groitzsch'},
                {x: 2.3,      y: 5.57,    r: 5, name:  'Zei - Zeitz'},
                {x: 2.2,      y: 5.59,    r: 5, name:  'Slk - Schelkau'},
                {x: 2.43,     y: 8.72,    r: 5, name:  'Ro - Roitzsch'},
                {x: 3.10,     y: 3.53,    r: 5,  name: 'Di - Dittersberg'},

            ],
            backgroundColor: [
                'rgba(255, 0, 0, 0.6)',
                
        ],
            borderColor: [
            'rgba(255, 0, 0, 0.6)',
                
        ],
        borderWidth: 1,
        clip: {left: false, top: false, right: false, bottom: false}
},
{
        label: 'Stufe 10 = 48,1 Hz',
            data: [
               // {x: 4.1,        y: 5.1,     r: 12},
               

            ],
            backgroundColor: [
                'rgba(160, 130, 80, 1)',
                
        ],
            borderColor: [
            'rgba(160, 130, 80, 1)',
                
        ],
        borderWidth: 1,
        clip: {left: false, top: false, right: false, bottom: false}
},
{
        label: 'nicht Aktive UW',
            data: [
                {x: 2.27,      y: 2.2,       r: 5,  name: 'PnPl - Plauen/Plamag'},
                {x: 2.23,      y: 1.75,      r: 5,  name: 'Wei - Weischlitz'},
                {x: 2.77,      y: 2.2,       r: 5,  name: 'SH - Schönheiderhammer'},
                {x: 2.82,      y: 2.1,       r: 5,  name: 'Ei - Eibenstock'},
                {x: 2.94,      y: 2.3,       r: 5,  name: 'Ea - Erla'},
                {x: 2.95,      y: 2.15,      r: 5,  name: 'EWEa - Eisenwerk Erla'},
                {x: 2.52,      y: 3.1,       r: 5,  name: 'Nm - Neumark'},
                {x: 2.53,      y: 3.25,      r: 5,  name: 'SLF - Spindel- und Lagertechnik Fraureuth'},             
                {x: 2.57,      y: 3.4,       r: 5,  name: 'ZwiW - Zwickau/West'},
                {x: 2.6,       y: 3.65,      r: 5,  name: 'SZwi - Zwickau/Sachsenring'},
                {x: 2.63,      y: 3.5,       r: 5,  name: 'ZwiE - Zwickau/Eckersbach'},
                {x: 2.61,      y: 3.38,      r: 5,  name: 'ZwiZ - Zwickau/Zentrum'},
                {x: 2.53,      y: 3.85,      r: 5,  name: 'Bud - Buderus'},
                {x: 2.67,      y: 4.15,      r: 5,  name: 'Gla - Glauchau'},
                {x: 2.76,      y: 4.1,       r: 5,  name: 'Pa - Palla'},
                {x: 2.75,      y: 3.99,      r: 5,  name: 'Knf - Knauf'},
                {x: 2.965,     y: 4.53,      r: 5,  name: 'SG - Stahlguss'},               
                {x: 3.01,      y: 4.32,      r: 5,  name: 'ChT - Chemnitz/Trompetter'},
                {x: 3.02,      y: 4.46,      r: 5,  name: 'ChM - Chemnitz/Mitte'},
                {x: 3.05,      y: 4.48,      r: 5,  name: 'ChGl - Chemnitz/Glösa'},
                {x: 3.08,      y: 4.4,       r: 5,  name: 'ChU - Chemnitz/Hauptbahnhof 07'},
                {x: 3.075,     y: 4.29,      r: 5,  name: 'ChZ - Chemnitz/Zentrum'},
                {x: 3.09,      y: 4.2,       r: 5,  name: 'ChZw - Chemnitz/Zeisigwald'},
                {x: 3.073,     y: 4.1,       r: 5,  name: 'ChH - Chemnitz/Hochschule'},
                {x: 3.045,     y: 4.03,      r: 5,  name: 'ChSp - Chemnitz/Stadtpark'},
                {x: 3.01,      y: 4.1,       r: 5,  name: 'ChW - Chemnitz/West'},
                {x: 2.96,      y: 4.2,       r: 5,  name: 'ChK - Chemnitz/Kappel'},
                {x: 3.12,      y: 4.28,      r: 5,  name: 'Nws - Niederwiesa'},
                {x: 2.89,      y: 3.33,      r: 5,  name: 'Sto - Stollberg'},
                {x: 2.88,      y: 2.65,      r: 5,  name: 'AueO - Aue/Ost'},
                {x: 3.12,      y: 2.65,      r: 5,  name: 'An - Annaberg'},
                {x: 3.08,      y: 3.3,       r: 5,  name: 'Ven - Venusberg'},
                {x: 3.12,      y: 3.3,       r: 5,  name: 'Sven - Venusberg Steinbruch'},
                {x: 3.297,     y: 3.17,      r: 5,  name: 'DFW - Marienberg Federnwerk'},
                {x: 3.45,      y: 3.18,      r: 5,  name: 'Olb - Olbernhau'},
                {x: 3.43,      y: 4.6,       r: 5,  name: 'WaFr - Freiberg Wacker'},
                {x: 3.43,      y: 5,         r: 5,  name: 'FrN - Freiberg/Nord'},
                {x: 2.87,      y: 5,         r: 5,  name: 'Od - Oberelsdorf'},
                {x: 2.59,      y: 5.15,      r: 5,  name: 'Alb - Altenburg'},
                {x: 2.55,      y: 4.52,      r: 5,  name: 'Goe - Gößnitz'},
                {x: 2.44,      y: 4.4,       r: 5,  name: 'Bw - Beerwalde'},
                {x: 2.54,      y: 5.45,      r: 5,  name: 'Pöh - Pöhla'},
                {x: 2.58,      y: 5.8,       r: 5,  name: 'Wun - Werkstoffunion'},
                {x: 2.66,      y: 6.05,      r: 5,  name: 'Bo - Borna'},
                {x: 2.69,      y: 6.2,       r: 5,  name: 'Eu - Eula'},
                {x: 2.83,      y: 5.76,      r: 5,  name: 'Gei - Geithain'},
                {x: 3.23,      y: 6.03,      r: 5,  name: 'Db - Döbeln'},
                {x: 3.05,      y: 6.15,      r: 5,  name: 'Ln - Leisnig'},
                {x: 2.82,      y: 6.6,       r: 5,  name: 'Gm - Grimma'},
                {x: 2.5,       y: 6.2,       r: 5,  name: 'Sci - Schleenhain'},
                {x: 2.5,       y: 6.6,       r: 5,  name: 'BSL - BSL'},
                {x: 2.462,     y: 6.6,       r: 5,  name: 'Pul - Pulgar'},
                {x: 2.525,     y: 6.6,       r: 5,  name: 'Lip - Lippendorf'},
                {x: 2.48,      y: 6.75,      r: 5,  name: 'Zwe - Zwenkau'},
                {x: 2.43,      y: 6.8,       r: 5,  name: 'Kn - Knautnaundorf'},
                {x: 2.4,       y: 6.94,      r: 5,  name: 'Ls - Lausen'},
                {x: 2.37,      y: 7.04,      r: 5,  name: 'Ku - Kulkwitz'},
                {x: 3.18,      y: 7.10,      r: 5,  name: 'Os - Oschatz'},
                {x: 2.82,      y: 7.3,       r: 5,  name: 'Be - Bennewitz'},
                {x: 3.43,      y: 7.10,      r: 5,  name: 'Str - Strenzfeld'},              
                {x: 3.53,      y: 7.2,       r: 5,  name: 'RiN - Riesa/Nord'},              
                {x: 3.55,      y: 7.7,       r: 5,  name: 'Grä - Großräschen'},
                {x: 2.4,       y: 7.3,       r: 5,  name: 'Meg - Metallguß'},
                {x: 2.42,      y: 7.35,      r: 5,  name: 'Boe - Böhlitz-Ehrenberg'},
                {x: 2.437,     y: 7.6,       r: 5,  name: 'Lüt - Lützschena'},
                {x: 2.57,      y: 7.77,      r: 5,  name: 'Pl - Plaußig'},
                {x: 2.53,      y: 8,         r: 5,  name: 'Rck - Rackwitz'},
                {x: 2.53,      y: 8.4,       r: 5,  name: 'Del - Delitzsch'},
                {x: 2.70,      y: 8,         r: 5,  name: 'Kos - Kospa'},
                {x: 2.76,      y: 8.14,      r: 5,  name: 'EiO - Eilenburg/Ost '},
                {x: 3.23,      y: 8.14,      r: 5,  name: 'Bel - Belgern '},
                {x: 2.465,     y: 7.4,       r: 5,  name: 'LzA - Leipzig A'},
                {x: 2.535,     y: 7.3,       r: 5,  name: 'LzB - Leipzig B'},
                {x: 2.565,     y: 7.585,     r: 5,  name: 'LzC - Leipzig C'},
                {x: 2.48,      y: 7.3,       r: 5,  name: 'LzD - Leipzig D'},
                {x: 2.515,     y: 7.423,     r : 5,  name: 'LzF - Leipzig F'},
                {x: 2.63,      y: 7.55,      r: 5,  name: 'LzG - Leipzig G'},
                {x: 2.575,     y: 7.48,      r: 5,  name: 'LzI - Leipzig I'},
                {x: 2.527,     y: 7.418,     r: 5,  name: 'LzK - Leipzig K'},
                {x: 2.48,      y: 7.5,       r: 5,  name: 'LzN - Leipzig N'},
                {x: 2.577,     y: 7.42,      r: 5,  name: 'LzO - Leipzig O'},
                {x: 2.53,      y: 7.5,       r: 5,  name: 'LzS - Leipzig S'},
                {x: 2.55,      y: 7.05,      r: 5,  name: 'LzT - Leipzig T'},
                {x: 2.458,     y: 7.2,       r: 5,  name: 'LzW - Leipzig W'},
                {x: 2.48,      y: 7.1,       r: 5,  name: 'LzZ - Leipzig Z'},
                {x: 2.72,      y: 8.8,       r: 5,  name: 'Due - Bad Düben '},
                {x: 3.6,       y: 8.25,      r: 5,  name: 'El - Elsterwerda '},
                {x: 3.9,       y: 8.3,       r: 5,  name: 'Sch - Schwarzheide '},
                {x: 4.28,      y: 8.1,       r: 5,  name: 'Hoy - Hoyerswerda '},
                {x: 4.43,      y: 7.65,      r: 5,  name: 'LoW - Lossa/West'},    //???????????????????????????????????????????????
                {x: 4.66,      y: 8.1,       r: 5,  name: 'Rwo - Reichwalde/Ost'},    
                {x: 4.35,      y: 8.2,       r: 5,  name: 'Hei - Schaltstation Heide'},    
                {x: 4.34,      y: 8.35,      r: 5,  name: 'Elh -  Elsterheide'}, 
                {x: 4.33,      y: 8.6,       r: 5,  name: 'Sab -  Sabrodt'}, 
                {x: 4.31,      y: 8.9,       r: 5,  name: 'Spt -  Spreetal'},    
                {x: 4.33,      y: 8.85,      r: 5,  name: 'Sp - Schwarze Pumpe'},
                {x: 4.5,       y: 8.955,     r: 5, name:  'Grs - Graustein'},
                {x: 4.03,      y: 8.9,       r: 5, name:  'So - Sonne'},
                {x: 3.65,      y: 9.3,       r: 5, name: 'DoK - Doberlug-Kirchhain'},
                {x: 3.05,      y: 9.3,       r: 5, name: 'Pre - Prettin'},
                {x: 3.11,      y: 10.1,      r: 5, name: 'JeN - Jessen/Nord'},
                {x: 4.3,       y: 10.02,     r: 5, name:  'CotSa - Cottbus/Sandow'},    
                {x: 4.32,      y: 10.16,     r: 5, name:  'CotK - Cottbus/Kraftwerk'},
                {x: 4.25,      y: 9.9,       r: 5, name:  'CotW - Cottbus/West'},
                {x: 3.98,      y: 10.6,      r: 5, name:  'Lüb - Lübbenau'},
                {x: 3.9,       y: 10.77,     r: 5, name:  'Rag - Ragow'},   
                {x: 4.45,      y: 10.8,      r: 5, name: 'Jän - Jänschwalde'},    
                {x: 4.48,      y: 10.7,      r: 5, name: 'Rad - Radewiese'},
                {x: 4.42,      y: 10.55,     r: 5, name: 'Jän - Jänschwalde'},
                {x: 4.39,      y: 11.1,      r: 5, name: 'Prl - Preilack '}, 
                {x: 4.63,      y: 11.3,      r: 5, name: 'Gu - Guben '},
                {x: 4.62,      y: 8.45,      r: 5,  name: 'Ww -  Weißwasser'},
                {x: 2.88,      y: 9.3,       r: 5, name: 'Schm - Schmiedeberg'},  
                {x: 2.88,      y: 10.77,     r: 5, name: 'Za - Zahna'}, 
                {x: 2.78,      y: 10.45,     r: 5, name: 'WbgN - Wittenberg/Nord'},   
                {x: 2.72,      y: 10.42,     r: 5, name: 'WbgN - Wittenberg/West'},   
                {x: 2.7,       y: 10.64,     r: 5, name: 'PieN - Piesteritz/Nord'},    
                {x: 2.65,      y: 10.64,     r: 5, name: 'PieB - Piesteritz BHKW'}, 
                {x: 2.6,       y: 10.55,     r: 5, name: 'CoN - Coswig/Nord'}, 
                {x: 2.57,      y: 9.45,      r: 5, name: 'ESZ - Elektroschmelze Zschornewitz'},  
                {x: 2.375,     y: 10.64,     r: 5, name: 'Rlu - Roßlau'}, 
                {x: 2.375,     y: 10,       r: 5, name: 'DeA - Dessau/Alten'},  
                {x: 2.43,      y: 9.65,      r: 5, name: 'Ma - Marke'},  
                {x: 2.4,       y: 9.4,       r: 5, name: 'WnT - Wolfen/Thalheim '},  
                {x: 2.38,      y: 9.3,       r: 5, name: 'WnA - Wolfen/Areal A '},  
                {x: 2.41,      y: 9.2,       r: 5, name: 'Wn - Wolfen'},  
                {x: 2.39,      y: 9.1,       r: 5, name: 'WnF - Wolfen/Filmfabrik '}, 
                {x: 2.45,      y: 8.93,      r: 5, name: 'BiM - Bitterfeld/Mitte'},  
                {x: 2.235,     y: 9.18,      r: 5, name: 'Cös - Cösitz '},  
                {x: 2.235,     y: 8.4,       r: 5, name: 'HlR - Halle/Reideburg '}, 
                {x: 2.14,      y: 8.25,      r: 5, name: 'HlN - Halle/Nord '},  
                {x: 2.11,      y: 8.13,      r: 5, name: 'HlZ - Halle/Zentrum '},
                {x: 2.08,      y: 8.1,       r: 5, name: 'HlW - Halle/West '},
                {x: 2.13,      y: 8.0,       r: 5, name: 'HlWö - Halle/Wörmlitz'},
                {x: 2.18,      y: 8.08,      r: 5, name: 'HlO - Halle/Ost '},
                {x: 2.17,      y: 8.0,       r: 5, name: 'HlD - Halle/Dieselstraße '},
                {x: 2.16,      y: 7.92,      r: 5, name: 'HlS - Halle/Süd '},
                {x: 2.09,      y: 7.73,      r: 5, name: 'Lau - Lauchstädt '},
                {x: 2.0,       y: 7.77,      r: 5, name: 'And - Angersdorf '},
                {x: 1.96,      y: 7.04,      r: 5, name: 'Mch - Mücheln  '},
                {x: 2.0,       y: 6.12,      r: 5, name: 'Nb - Naumburg   '},
                {x: 2.16,      y: 7.05,      r: 5, name: 'MeB - Merseburg/Beuna'},       
                {x: 2.2,       y: 7.07,      r: 5, name: 'Mi - Mitte '},
                {x: 2.2,       y: 6.96,      r: 5, name: 'Leu - Leuna  '},
                {x: 2.09,      y: 6.92,      r: 5, name: 'GrkN - Großkayna/Neu '},
                {x: 2.12,      y: 6.6,       r: 5, name: 'Ws - Weißenfels'},
                {x: 2.1,       y: 6.55,      r: 5, name: 'WsN - Weißenfels/Nord'},   
                {x: 2.22,      y: 6.35,      r: 5, name: 'Zor - Zorbau '},
                {x: 2.4,       y: 5.78,      r: 5, name: 'Gö - Göbitz '},
                {x: 2.335,     y: 5.53,      r: 5, name: 'ZeiG - Zeitz/Guss'},
                {x: 1.9,       y: 8.03,      r: 5, name: 'AmB - Amsdorf/Braunkohle '},
                {x: 1.83,      y: 6.9,       r: 5, name: 'Ka - Karsdorf'},
                {x: 1.9,       y: 9.88,      r: 5, name: 'BrW - Bernburg/West'},
                {x: 1.96,      y: 9.8,       r: 5, name: 'BrS - Bernburg/Süd '},
                {x: 1.96,      y: 9.7,       r: 5, name: 'BrU - Bernburg/Untergrundspeicher  '},
                {x: 1.98,      y: 9.6,       r: 5, name: 'Bz - Bebitz '},
                {x: 1.96,      y: 9.2,       r: 5, name: 'Kön - Könnern '},
                {x: 1.93,      y: 9.95,      r: 5, name: 'BrK - Bernburg/Kali'},
                {x: 1.91,      y: 10,        r: 5, name: 'BrP - Bernburg/Peißen'},   
                {x: 1.94,      y: 10,        r: 5, name: 'BrN - Bernburg/Nord  '},
                {x: 1.95,      y: 10.17,     r: 5, name: 'BrN - Strenzfeld   '},
                {x: 1.7,       y: 9.8,       r: 5, name: 'As - Aschersleben '},
                {x: 1.68,      y: 9.87,      r: 5, name: 'AsN - Aschersleben/Nord '},
                {x: 1.72,      y: 9.09,      r: 5, name: 'Ht - Hettstedt'},
                {x: 1.74,      y: 8.85,      r: 5, name: 'Klm - Klostermansfeld '},
                {x: 1.55,      y: 10.1,      r: 5, name: 'Na - Nachterstedt '},
                {x: 1.4,       y: 10,        r: 5, name: 'QbO - Quedlinburg/Ost '},
                {x: 1.28,      y: 9.7,       r: 5, name: 'EHW - Eisen- und Hüttenwerk Thale '}, 
                {x: 4.58,      y: 11.9,      r: 5, name:  'EhsP - Eisenhüttenstadt/Pohlitz '},                            
                
            ],
            backgroundColor: [
                'rgba(130, 130, 130, 0.8)',
                
        ],
            borderColor: [
            'rgba(130, 130, 130, 0.8)',
                
        ],
        borderWidth: 1,
        clip: {left: false, top: false, right: false, bottom: false}
}

]
};
/*
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
        };      */

        const config = {
            type: 'bubble',
            data,
            options: {
              plugins: {
                tooltip: {
                  callbacks: {
                    label: (context) => {
                      return context.raw.name;
                    }
                  }
                },
                title: {
                  display: false,
                  text: ['Übersichtskarte über die Verteilung von Umspannwerken auf die Frequenzstufen'],
                  font: {
                    size: 24
                  }, 
                  padding: {
                    top: 10,
                    bottom: 30
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
                  max: 12,
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
                  min: 1,
                  max: 5,
                  grid: {
                      display: false,
                      drawOnChartArea: false,
                      drawBorder: false
                  }
                }
              }
            }//, plugins: [drawImage]
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