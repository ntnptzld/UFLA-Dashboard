<?php 
  session_start();

  if (!isset($_SESSION['user_id']) && !isset($_SESSION['user_email'])) { 
?>

<!DOCTYPE html>
<html lang="de">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Login</title>

	<link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&family=Signika:wght@300;400;500;600;700&display=swap" rel="stylesheet">   
    
    <link rel="stylesheet" href="stylesheet.css">
	<link rel="icon" href="bilder/UFLA_Logo.png">

	<style>
        div.container {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);

            display: flex;
            flex-direction: row;
            align-items: center;

            background-color: white;
			border-radius: 20px;
            padding: 30px;
            box-shadow: 50px 50px 50px -50px darkslategray;
        }

        div.container div.myform {
            width: 270px;
            margin-right: 30px;
        }

        div.container div.myform input {
            border:  none;
            outline: none;
            border-radius: 0;
            width: 100%;
            border-bottom: 2px solid #1c1c1e;
            margin-bottom: 25px;
            padding: 7px 0;
            font-size: 14px;
        }

        button {
            color: white;
            background-color: #1c1c1e;
            border: none;
            outline: none;
            border-radius: 2px;
            font-size: 14px;
            padding: 5px 12px;
            font-weight: 500;
        }

		textarea:focus,
		input:focus {
			outline: none;
		}

    </style>
</head>


<body background="bilder/background.png">

	<!-- Überschrift -->
    <h1 class="schrift1">UFLA-Dashboard Mitnetz Strom</h1>
	

	<div class="container">
        <div class="myform">
            <form action="auth.php" method="post">
		  		<h2 class="schrift2">Login</h2>

				<?php if (isset($_GET['error'])) { ?>
				<div class="login-error" role="alert">
				<?=htmlspecialchars($_GET['error'])?>
				</div>
				<?php } ?><br>

				<input type="email" 
		           name="email" 
				   placeholder="Email Adresse"
		           value="<?php if(isset($_GET['email']))echo(htmlspecialchars($_GET['email'])) ?>" 
		           class="form-control" 
		           id="InputEmail" aria-describedby="emailHelp">
                
				<input type="password" 
		           class="form-control" 
		           name="password" 
				   placeholder="Passwort"
		           id="InputPassword">

                <button type="submit">Login</button>
            </form>
        </div>
        <div class="image">
            <img src="bilder/office.png" width="600px">
        </div>
    </div>

	<!-- Logos der zusammenarbeitenden Partner (Mitnetz Strom & Hochschule Mittweida) -->
	<div class="login-partner">
		<div class="partner-logos">
			<div class="partner-mitnetz">
				<a href="https://www.mitnetz-strom.de/" target="_blank">
					<img src="bilder/Mitnetz Logo.png" alt="Mitnetz Strom">
				</a>
			</div>
			<div class="partner-hsmw">
				<a href="https://www.hs-mittweida.de/" target="_blank">
					<img src="bilder/HSMW_Logo.png" alt="Hochschule Mittweida">
				</a>
			</div>
		</div>
	</div>
	  
</body>
</html>

<?php 
} else {
	//nach erfolgreichem Login wird auf die Startseite "index.php" weitergeleitet
   	header("Location: index.php");
}
?>