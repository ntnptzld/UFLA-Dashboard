<?php 
session_start();
include 'db_config.php';

if (isset($_POST['email']) && isset($_POST['password'])) {
	
	//Entgegennahme der auf "login.php" eingegebenen Daten
	$email = $_POST['email'];
	$password = $_POST['password'];
	
	//Überprüfung, ob leere Daten übergeben wurden
	if (empty($email)) {
		header("Location: login.php?error=Email benötigt");
	}else if (empty($password)){
		header("Location: login.php?error=Password benötigt&email=$email");
	}else {
		$stmt = $conn->prepare("SELECT * FROM users WHERE email=?");
		$stmt->execute([$email]);

		if ($stmt->rowCount() === 1) {
			$user = $stmt->fetch();

			$user_id = $user['id'];
			$user_email = $user['email'];
			$user_password = $user['password'];
			$user_full_name = $user['full_name'];

			//Überprüfung auf Korrektheit der eingegeben Werte mit Werten aus der Datenbank
			if ($email === $user_email) {
				if (md5($password, $user_password)) {
					$_SESSION['user_id'] = $user_id;
					$_SESSION['user_email'] = $user_email;
					$_SESSION['user_full_name'] = $user_full_name;
					header("Location: index.php");

				}else {
					header("Location: login.php?error=Falsche Anmeldedaten&email=$email");
				}
			}else {
				header("Location: login.php?error=Falsche Anmeldedaten&email=$email");
			}
		}else {
			header("Location: login.php?error=Falsche Anmeldedaten&email=$email");
		}
	}
}

?>