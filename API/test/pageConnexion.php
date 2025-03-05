<?php
	require_once("../src/connexion.php");
	require_once("../src/donnees.php");
    
	session_start();

	if(isset($_POST['login']) && isset($_POST['pwd']) && $_POST['login'] != "" && $_POST['pwd'] != "") {

		$login = $_POST['login'];
		$pwd = $_POST['pwd'];

		try {
			$urlAPI = "http://localhost/sae_s4_site_web/API/src/login/".$login."/".$pwd;
			$apikey = appelAPI($urlAPI, "", $status);
			$_SESSION['apikey'] = $apikey["APIKEYDEMONAPPLI"];

			$resultat = login($login, $pwd);

			if($resultat != 0) {
				$_SESSION['login'] = $login;
				$_SESSION['session'] = session_id();
				header('Location: testAPI.php');
				exit();
			} else {
				echo "Identifiant ou mot de passe incorrect !"; 
			}
		} catch (Exception $e) {
			echo "Identifiant ou mot de passe incorrect !";
		}
	}
?>

<!DOCTYPE HTML>
<html>
<head>
    <title>Connexion</title>
    <meta name="Description" content="" />
    <meta name="Keywords" content="api, connexion" />
    <link href="bootstrap/css/bootstrap.css" rel="stylesheet">
</head>
    <body>
        <div class = "container">
            <div class = "row">
				<div class = "col-md-12 bordure">
					<div class = "row">
					</div>
				</div>
				<div class = "col-md-12 bordure">
					<form method="POST" action="pageConnexion.php">
						<div class = "row">
							<div class = "col-md-6">
								<br/>Identifiant :<br/><br/>
								<input type="text" id="login" name="login" placeholder="Tapez votre numéro de compte" class="form-control">
							</div>
							<div class = "col-md-6">
								<br/>Mot de passe :<br/><br/>
								<input type="password" id="pwd" name="pwd" placeholder="Tapez votre mot de passe" class="form-control">
							</div>
							<div class = "col-md-12">
                                <br>
								<button type="submit" class="btn btn-success mb-2 mt-2">Me connecter</button>
							</div>
						</div>
					</form>
				</div>
            </div>
        </div>
    </body>
</html>