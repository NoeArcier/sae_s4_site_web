<?php 
	require_once("json.php");
	require_once("donnees.php");
	require_once("connexion.php");

	$request_method = $_SERVER["REQUEST_METHOD"];
	switch($request_method) {
		case "GET" :
			if (!empty($_GET['demande'])) {

				$url = explode("/", filter_var($_GET['demande'],FILTER_SANITIZE_URL));
				
				switch($url[0]) {
					case 'login' :
						if (isset($url[1])) {$login=$url[1];} else {$login="";}
						if (isset($url[2])) {$password=$url[2];} else {$password="";}
						verifLoginPassword($login,$password);
						break;
					break;
					case 'articlesStockPrix' :
						authentification(); 
						getStockPrix();
						break ;
					default : 
						$infos['Statut']="KO";
						$infos['message']=$url[0]." inexistant";
						sendJSON($infos, 404) ;
				}
			} else {
				$infos['Statut']="KO";
				$infos['message']="URL non valide";
				sendJSON($infos, 404) ;
			}
			break ;

		case "PUT":
			if (!empty($_GET['demande'])) {
				$url = explode("/", filter_var($_GET['demande'], FILTER_SANITIZE_URL));
				if ($url[0] === 'CB_modifPrixStock' && !empty($url[1])) {
					authentification();
					$donnees = json_decode(file_get_contents("php://input"),true);
					modifierPrixStock($donnees, $url[1]);
				} else {
					$infos['Statut'] = "KO";
					$infos['message'] = "URL non valide ou code-barre manquant";
					sendJSON($infos, 404);
				}
			} else {
				$infos['Statut'] = "KO";
				$infos['message'] = "Données manquantes";
				sendJSON($infos, 404);
			}
			break;

		default :
			$infos['Statut']="KO";
			$infos['message']="URL non valide";
			sendJSON($infos, 404) ;
	}
?>