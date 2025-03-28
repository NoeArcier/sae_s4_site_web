<?php
	function appelAPI($apiUrl, $apiKey, &$http_status, $typeRequete="GET", $donnees=null) {
		
		$curl = curl_init();									// Initialisation

		curl_setopt($curl, CURLOPT_URL, $apiUrl);				// Url de l'API à appeler
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);			// Retour dans une chaine au lieu de l'afficher
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); 		// Désactive test certificat
		curl_setopt($curl, CURLOPT_FAILONERROR, true);
		
		// Parametre pour le type de requete
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $typeRequete); 
		
		// Si des données doivent être envoyées
		if (!empty($donnees)) {
			curl_setopt($curl, CURLOPT_POSTFIELDS, $donnees);
			curl_setopt($curl, CURLOPT_POST, true);
		}
		
		$httpheader []= "Content-Type:application/json";
		
		if (!empty($apiKey)) {
			// Ajout de la clé API dans l'entete si elle existe (pour tous les appels sauf login)
			$httpheader = ['APIKEYDEMONAPPLI: '.$apiKey];
		}
		curl_setopt($curl, CURLOPT_HTTPHEADER, $httpheader);
		
		$result = curl_exec($curl);								// Exécution
		$http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);	// Récupération statut 
		
		curl_close($curl);										// Cloture curl

		if ($http_status=="200" or $http_status=="201" ) {		// OK, l'appel s'est bien passé
			return json_decode($result,true); 					// Retourne la collection 
		} else {
			$result=[]; 										// retourne une collection Vide
			return $result;
		}
	}

	function login($login, $pwd){

		$pdo = getPDO();

		$requete = "SELECT COUNT(*) FROM login WHERE login = :login AND mdp = sha1(:pwd)";
		
		$stmt = $pdo->prepare($requete);
		$stmt->bindParam(':login', $login);
		$stmt->bindParam(':pwd', $pwd);
		
		$stmt->execute();
		
		return $stmt->fetch()['COUNT(*)'];
	}

    function authentification() {
		
		if (isset($_SERVER["HTTP_APIKEYDEMONAPPLI"])) {
			$cleAPI=$_SERVER["HTTP_APIKEYDEMONAPPLI"];

			if ($cleAPI!="LKJfdgfgSDHSKFKsdfSHFJSD5465FfsdSQFH") { 
				$infos['Statut']="KO";
				$infos['message']="APIKEY invalide.";
				sendJSON($infos, 403) ;
				die();
			}
		}else {
			$infos['Statut']="KO";
			$infos['message']="Authentification necessaire par APIKEY.";
			sendJSON($infos, 401) ;
			die();
		}
	}

	function verifLoginPassword($login, $password) {
		$pdo = getPDO();
		
		// Vérifier si l'utilisateur existe et récupérer son API key
		$requete = "SELECT id_employe FROM login WHERE login = :login AND mdp = sha1(:pwd)";
		$stmt = $pdo->prepare($requete);
		$stmt->bindParam(':login', $login);
		$stmt->bindParam(':pwd', $password);
		$stmt->execute();
		
		$id = $stmt->fetchColumn();
	
		if ($id) { //Si il existe
			$infos['APIKEYDEMONAPPLI'] = "LKJfdgfgSDHSKFKsdfSHFJSD5465FfsdSQFH";
			$infos['IDEMPLOYE'] = $id;
			sendJSON($infos, 200);
		} else { //Si il existe pas
			$infos['Statut'] = "KO";
			$infos['message'] = "Logins incorrects.";
			sendJSON($infos, 401);
			die();
		}
	}
?>