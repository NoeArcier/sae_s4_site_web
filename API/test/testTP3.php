
<?php
	require_once("../API/json.php");
	require_once("../API/connexion.php");

	session_start();

	if(isset($_POST['deconnexion']) && $_POST['deconnexion'] == '1'){
		deconnexion();
	}

	if (!isset($_SESSION['session']) || session_id() !== $_SESSION['session'] || !isset($_SESSION['apikey'])) {
		session_destroy();
		header("Location: pageConnexion.php");
		exit();
	}

	if (isset($_GET['valider'])) {
		$codeBarre = $_GET['valider'];
		$prix = $_GET["PRIX"];
		$stock = $_GET["STOCK"];
	
		if (!is_numeric($prix) || !is_numeric($stock)) {
			echo "Code barre : $codeBarre";
			echo "<br/>";
			echo "Prix : $prix";
			echo "<br/>";
			echo "Stock : $stock";
			echo "<br/>";
			echo "Erreur lors de la mise à jour.";
		} else {
			$urlAPI = "http://localhost/complementweb/tp3/API/CB_modifPrixStock/$codeBarre";
			$donnees = json_encode(["PRIX" => $prix, "STOCK" => $stock]);
			$status = 0;
			$reponse = appelAPI($urlAPI, $_SESSION['apikey'], $status, "PUT", $donnees);
	
			if ($status == 201) {
				echo "Code barre : $codeBarre";
				echo "<br/>";
				echo "Prix : $prix";
				echo "<br/>";
				echo "Stock : $stock";
				echo "<br/>";
				echo "Modification réalisée.";
			} else {
				echo "Code barre : $codeBarre";
				echo "<br/>";
				echo "Prix : $prix";
				echo "<br/>";
				echo "Stock : $stock";
				echo "<br/>";
				echo "Erreur lors de la mise à jour.";
			}
		}
	}
	$urlAPI = "http://localhost/complementweb/tp3/API/articlesStockPrix";
	$lesStockPrix = appelAPI($urlAPI, $_SESSION['apikey'], $status);	
?>


<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8" />
		<title>TP3 API STOCK</title>

		<!-- Bootstrap CSS -->
		<link href="bootstrap/css/bootstrap.css" rel="stylesheet">
		
	</head>
	<body>
		<form method="post" action="">
			<?php echo "<span class='login'>Bonjour ".$_SESSION['login']." !</span><br>"; ?>
			<input type="hidden" name="deconnexion" id="deconnexion" value="1">
			<button type="submit" class="btn btn-danger mb-2 mt-2">Se déconnecter</button>
		</form>	
		<div class="container">
			<div class="row">
				<div class="col-xs-12">
					<h1>Modification des stocks et prix des articles</h1>
					<?php
						echo "<table class='table table-striped table-bordered'>";
						
						echo "<tr><th>Categorie</th><th>Code Article</th><th>Désignation</th><th>Taille</th><th>Couleur</th><th>Code Barre</th><th>Prix</th><th>Stock</th><th>Validation</th></tr>";
						foreach ($lesStockPrix as $stockPrix) {
							$codeBarre = $stockPrix['CODE_BARRE'];
							echo "<tr>";
							echo "<form method='GET' action=''>";
							echo "<td>" . $stockPrix['CATEGORIE'] . "</td>";
							echo "<td>" . $stockPrix['CODE_ARTICLE'] . "</td>";
							echo "<td>" . $stockPrix['DESIGNATION'] . "</td>";
							echo "<td>" . $stockPrix['TAILLE'] . "</td>";
							echo "<td>" . $stockPrix['COULEUR'] . "</td>";
							echo "<td>" . $codeBarre . "</td>";
							echo "<td><input type='text' name='PRIX' value='" . $stockPrix['PRIX'] . "'></td>";
							echo "<td><input type='text' name='STOCK' value='" . $stockPrix['STOCK'] . "'></td>";
							echo "<td>
									<input type='hidden' name='valider' value='$codeBarre'>
									<button type='submit' class='btn btn-primary'>Envoyer</button>
								</td>";
							echo "</form>";
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