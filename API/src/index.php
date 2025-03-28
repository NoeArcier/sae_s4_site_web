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
						if (isset($url[1])) {
							$login=$url[1];
						} else {
							$login="";
						}
						if (isset($url[2])) {
							$password=$url[2];
						} else {
							$password="";
						}
						verifLoginPassword($login,$password);
						break;
					case 'signalements' :
						if (!empty($url[1])) {
							authentification();
							getSignalements($url[1]);
						} else {
							$infos['Statut'] = "KO";
							$infos['message'] = "Identifiant manquant";
							sendJSON($infos, 404);
						}
						break;
						
					case 'reservations' :
						if (!empty($url[1])) {
							authentification();
							getReservations($url[1]);
						} else {
							$infos['Statut'] = "KO";
							$infos['message'] = "Identifiant manquant";
							sendJSON($infos, 404);
						}
						break;
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
				switch($url[0]) {
					case "modif_signalement" :
						if (!empty($url[1])) {
							$donnees = json_decode(file_get_contents("php://input"),true);
							authentification();
							modifierSignalement($donnees, $url[1]);
						} else {
							$infos['Statut'] = "KO";
							$infos['message'] = "Identifiant manquant";
							sendJSON($infos, 404);
						}
						break;
					default : 
						$infos['Statut']="KO";
						$infos['message']="'".$url[0]."' inexistant";
						sendJSON($infos, 404) ;
				}
			} else {
				$infos['Statut'] = "KO";
				$infos['message'] = "Données manquantes";
				sendJSON($infos, 404);
			}
			break;

		case "POST" :
			if (!empty($_GET['demande'])) {
				$url = explode("/", filter_var($_GET['demande'],FILTER_SANITIZE_URL));
				switch($url[0]) {
					case 'ajout_signalement' : 
						$donnees = json_decode(file_get_contents("php://input"),true);
						authentification();
						ajoutSignalement($donnees);
						break ;
					default : 
						$infos['Statut']="KO";
						$infos['message']="'".$url[0]."' inexistant";
						sendJSON($infos, 404) ;
				}	
			} else {
				$infos['Statut']="KO";
				$infos['message']="URL non valide";
				sendJSON($infos, 404) ;
			}
			break;

		case "DELETE" :	
			if (!empty($_GET['demande'])) {
				$url = explode("/", filter_var($_GET['demande'],FILTER_SANITIZE_URL));
				switch($url[0]) {
					case 'suppr_signalement' : 
						if (!empty($url[1])) {
							authentification();
							supprimeSignalement($url[1]);
						} else {
							$infos['Statut']="KO";
							$infos['message']="Vous n'avez pas renseigné le numéro du signalement.";
							sendJSON($infos, 400) ;
						}
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
			break;

		default :
			$infos['Statut']="KO";
			$infos['message']="URL non valide";
			sendJSON($infos, 404) ;
	}
	// GET
	//http://localhost/sae_s4_site_web/API/src/signalements/A999999
	//http://localhost/sae_s4_site_web/API/src/reservations/A999999
	//http://localhost/sae_s4_site_web/API/src/login/admin/admin

	// PUT
	//http://localhost/sae_s4_site_web/API/src/modif_signalement/1

	//POST
	//http://localhost/sae_s4_site_web/API/src/ajout_signalement

	//DELETE
	//http://localhost/sae_s4_site_web/API/src/suppr_signalement/1

	//Corps en JSON pour PUT
	// {
	// 	"TITRE" : "Ampoule cassée",
	// 	"RESUME" : "L'ampoule s'est cassée pendant la réunion",
	// 	"IMPACT" : "1",
	// 	"RECONTACT" : "0"
	// }

	//Corps en JSON pour POST
	// {
	// 	"TITRE" : "Oui",
	// 	"RESUME" : "Peut être",
	// 	"IMPACT" : "3",
	// 	"RECONTACT" : "1",
	//  "RESERVATION" : "R000001"
	// }
?>