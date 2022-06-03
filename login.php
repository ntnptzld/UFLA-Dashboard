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

</head>


<body background="bilder/background.png">

	<!-- Überschrift -->
    <h1 class="schrift1">UFLA-Dashboard MITNETZ Strom</h1>
	

	<div class="login-box">
		<!-- Login-Formular -->
		<!-- Werte werden an SESSION übergeben und von "auth.php" entgegengenommen-->
	  	<form action="auth.php" method="post">
		  <h2 class="schrift-login">Login</h2>
		  
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
		           id="InputEmail" aria-describedby="emailHelp"><br>
		  
		    <input type="password" 
		           class="form-control" 
		           name="password" 
				   placeholder="Passwort"
		           id="InputPassword"><br>

		  <button type="submit">Login</button>

		</form>
	</div>

	<!-- Logos der zusammenarbeitenden Partner (Mitnetz Strom & Hochschule Mittweida) -->
	<div class="login-partner">
		<p>In Kooperation zwischen:</p>
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