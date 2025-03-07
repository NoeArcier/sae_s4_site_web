
<?php
	require_once("../src/json.php");
	require_once("../src/connexion.php");

	session_start();

	if (!isset($_SESSION['session']) || session_id() !== $_SESSION['session'] || !isset($_SESSION['apikey'])) {
		session_destroy();
		header("Location: pageConnexion.php");
		exit();
	}

	if (isset($_GET['valider'])) {
		$id = $_GET['valider'];
		$titre = $_GET["titre"];
		$resume = $_GET["resume"];
		$impact = $_GET["impact"];
		$recontact = $_GET["recontact"];	
	
		if ($titre == "" || $resume == ""
		    || !is_numeric($impact) || !is_numeric($recontact)) {
			echo "Identifiant : $id";
			echo "<br/>";
			echo "Titre : $titre";
			echo "<br/>";
			echo "Résume : $resume";
			echo "<br/>";
			echo "Impact : $impact";
			echo "<br/>";
			echo "Recontact : $recontact";
			echo "<br/>";
			echo "Erreur lors de la mise à jour.";
		} else {
			$urlAPI = "http://localhost/sae_s4_site_web/API/src/modif_signalement/$id";
			$donnees = json_encode(["TITRE" => $titre, "RESUME" => $resume, "IMPACT" => $impact, "RECONTACT" => $recontact]);
			$status = 0;
			appelAPI($urlAPI, $_SESSION['apikey'], $status, "PUT", $donnees);
		}
	}

	if (isset($_GET['supprimer'])) {
		$id = $_GET['supprimer'];	

		$urlAPI = "http://localhost/sae_s4_site_web/API/src/suppr_signalement/$id";
		appelAPI($urlAPI, $_SESSION['apikey'], $status, "DELETE");
	}

	if (isset($_GET['ajouter'])) {
		$titre = $_GET['titre'];
		$resume = $_GET['resume'];
		$impact = $_GET['impact'];
		$recontact = $_GET['recontact'];
		$id_reservation = "R000011";

		$urlAPI = "http://localhost/sae_s4_site_web/API/src/ajout_signalement";
		$donnees = json_encode(["TITRE" => $titre, "RESUME" => $resume, "IMPACT" => $impact, "RECONTACT" => $recontact, "RESERVATION" => $id_reservation]);
		appelAPI($urlAPI, $_SESSION['apikey'], $status, "POST", $donnees);
	}

	$urlAPI = "http://localhost/sae_s4_site_web/API/src/signalements/A999999";
	$lesSignalements = appelAPI($urlAPI, $_SESSION['apikey'], $status);

	$urlAPI = "http://localhost/sae_s4_site_web/API/src/reservations/A999999";
	$lesReservations = appelAPI($urlAPI, $_SESSION['apikey'], $status);	
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
						
						echo "<tr><th>Id</th><th>Date</th><th>Heure</th><th>Titre</th><th>Resumé</th><th>Impact</th><th>Recontact</th><th>Validation</th><th>Suppression</th></tr>";
						foreach ($lesSignalements as $signalements) {
							echo "<tr>";
							echo "<form method='GET' action=''>";
							echo "<td>" . $signalements['id'] . "</td>";
							echo "<td>" . $signalements['date'] . "</td>";
							echo "<td>" . $signalements['heure'] . "</td>";
							echo "<td><input type='text' name='titre' value='" . $signalements['titre'] . "'></td>";
							echo "<td><input type='text' name='resume' value='" . $signalements['resume'] . "'></td>";
							echo "<td><input type='text' name='impact' value='" . $signalements['impact'] . "'></td>";
							echo "<td><input type='text' name='recontact' value='" . $signalements['recontact'] . "'></td>";
							echo "<td>
									<input type='hidden' name='valider' value=" .$signalements["id"] .">
									<button type='submit' class='btn btn-primary'>Valider</button>
								</td>";
							echo "<td>
									<input type='hidden' name='supprimer' value=". $signalements['id'] .">
									<button type='submit' class='btn btn-danger'>Supprimer</button>
							</td>";
							echo "</form>";
							echo "</tr>";
						}
						echo "</table>";
					?>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-12">
					<h1>Réservations</h1>
					<?php
						echo "<table class='table table-striped table-bordered'>";
						
						echo "<tr><th>Id</th><th>Date</th><th>Heure Début</th><th>Heure Fin</th><th>Salle</th><th>Activité</th></tr>";
						foreach ($lesReservations as $reservation) {
							echo "<tr>";
							echo "<td>" . $reservation['ID'] . "</td>";
							echo "<td>" . $reservation['DATE'] . "</td>";
							echo "<td>" . $reservation['HDEBUT'] . "</td>";
							echo "<td>" . $reservation['HFIN'] . "</td>";
							echo "<td>" . $reservation['SALLE'] . "</td>";
							echo "<td>" . $reservation['ACTIVITE'] . "</td>";
							echo "</tr>";
						}
						echo "</table>";
					?>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-12">
					<h1>Ajouter un signalement</h1>
					<form action="" method="GET">
						Titre : 
						<input type="text" name="titre"><br><br>
						Résumé : 
						<input type="text" name="resume"><br><br>
						Impact : 
						<input type="number" name="impact"><br><br>
						Recontact : 
						<input type="number" name="recontact"><br><br>
						<input type='hidden' name='ajouter' value="0">
						<button type="submit" class="btn btn-success">Ajouter</button>
					</form>
				</div>
			</div>
		</div>
	</body>
</html>