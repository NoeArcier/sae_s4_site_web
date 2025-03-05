
<?php
	require_once("../src/json.php");
	require_once("../src/connexion.php");

	session_start();

	if (!isset($_SESSION['session']) || session_id() !== $_SESSION['session'] || !isset($_SESSION['apikey'])) {
		session_destroy();
		header("Location: pageConnexion.php");
		exit();
	}

	$urlAPI = "http://localhost/sae_s4_site_web/API/src/signalements";
	$lesSignalements = appelAPI($urlAPI, $_SESSION['apikey'], $status);	
?>


<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8" />
		<title>SAE</title>

		<!-- Bootstrap CSS -->
		<link href="bootstrap/css/bootstrap.css" rel="stylesheet">
		
	</head>
	<body>
		<div class="container">
			<div class="row">
				<div class="col-xs-12">
					<h1>Signalements</h1>
					<?php
						echo "<table class='table table-striped table-bordered'>";
						
						echo "<tr><th>Id</th><th>Date</th><th>Heure</th><th>Titre</th><th>Resumé</th><th>Impact</th><th>Recontact</th></tr>";
						foreach ($lesSignalements as $signalements) {
							echo "<tr>";
							echo "<td>" . $signalements['id'] . "</td>";
							echo "<td>" . $signalements['date'] . "</td>";
							echo "<td>" . $signalements['heure'] . "</td>";
							echo "<td>" . $signalements['titre'] . "</td>";
							echo "<td>" . $signalements['resume'] . "</td>";
							echo "<td>" . $signalements['impact'] . "</td>";
							echo "<td>" . $signalements['recontact'] . "</td>";
							echo "</tr>";
						}
						echo "</table>";
					?>
				</div>
			</div>
		</div>
		<br><br>
	</body>
</html>