<?php
	// Données
		
	function getPDO(){
		// Retourne un objet connexion à la BD
		$host='localhost';	// Serveur de BD
		$db='mezabi3';		// Nom de la BD
		$user='root';		// User 
		$pass='root';		// Mot de passe
		$charset='utf8mb4';	// charset utilisé
		
		// Constitution variable DSN
		$dsn="mysql:host=$host;dbname=$db;charset=$charset";
		
		// Réglage des options
		$options=[																				 
			PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,
			PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC,
			PDO::ATTR_EMULATE_PREPARES=>false];
		
		try{	// Bloc try bd injoignable ou si erreur SQL
			$pdo=new PDO($dsn,$user,$pass,$options);
			return $pdo ;			
		} catch(PDOException $e){
			//Il y a eu une erreur de connexion
			$infos['Statut']="KO";
			$infos['message']="Problème connexion base de données";
			sendJSON($infos, 500) ;
			die();
		}
	}
	
	function getStockPrix() {
		// Retourne la liste des stockPrix
		try {
			$pdo=getPDO();
			$requete='SELECT ar.CATEGORIE, ca.DESIGNATION AS CATEGORIE, ar.CODE_ARTICLE, ar.DESIGNATION, ta.CODE_TAILLE, ta.DESIGNATION as TAILLE, co.CODE_COULEUR, co.DESIGNATION as COULEUR, sp.CODE_BARRE, sp.PRIX, sp.STOCK  
			FROM stockprix sp left join articles ar on sp.ARTICLE=ar.ID_ARTICLE 
			LEFT JOIN a_couleurs co ON sp.COULEUR = co.CODE_COULEUR 
			LEFT JOIN a_tailles ta ON sp.TAILLE = ta.CODE_TAILLE
			LEFT JOIN a_categories ca ON ar.CATEGORIE = ca.CODE_CATEGORIE
			order by ar.CATEGORIE, ar.CODE_ARTICLE, ta.CODE_TAILLE, co.DESIGNATION' ; 
			
			$stmt = $pdo->prepare($requete);										// Préparation de la requête
			$stmt->execute();	
				
			$stockprix=$stmt ->fetchALL();
			$stmt->closeCursor();
			$stmt=null;
			$pdo=null;

			sendJSON($stockprix, 200) ;
		} catch(PDOException $e){
			$infos['Statut']="KO";
			$infos['message']=$e->getMessage();
			sendJSON($infos, 500) ;
		}
	}

	function modifierPrixStock($donneesJson, $code_barre) {
		if ($donneesJson['PRIX'] != "" && $donneesJson['STOCK'] != "") {
			// Données remplies, on modifie dans la table stockprix
			try {
				$pdo = getPDO();
	
				// Vérification si le code-barre existe
				// Faire une transaction
				// $verif = $pdo->prepare("SELECT COUNT(*) FROM stockprix WHERE CODE_BARRE = :CODE_BARRE");
				// $verif->bindParam(":CODE_BARRE", $code_barre);
				// $verif->execute();
				// $existe = $verif->fetchColumn();
	
				// if ($existe == 0) {
				// 	$infos['Statut'] = "KO";
				// 	$infos['Message'] = "Code-barre non trouvé";
				// 	sendJSON($infos, 404);
				// }
	
				// Mise à jour du prix et du stock
				$maRequete = "UPDATE stockprix SET PRIX = :PRIX, STOCK = :STOCK WHERE CODE_BARRE = :CODE_BARRE";
				$stmt = $pdo->prepare($maRequete);
				$stmt->bindParam(":CODE_BARRE", $code_barre);
				$stmt->bindParam(":PRIX", $donneesJson['PRIX']);
				$stmt->bindParam(":STOCK", $donneesJson['STOCK']);
				$stmt->execute();
	
				$nb = $stmt->rowCount(); // Nombre de lignes modifiées
				$stmt = null;
				$pdo = null;
	
				if ($nb == 0) {
					// Erreur lors de la mise à jour
					$infos['Statut'] = "KO";
					$infos['Message'] = "Aucune modification effectuée";
					sendJSON($infos, 404);
				} else {
					// Modification réalisée
					$infos['Statut'] = "OK";
					$infos['Message'] = "Modification effectuée";
					sendJSON($infos, 201);
				}
	
			} catch (PDOException $e) {
				// Retour des informations au client en cas d'erreur BD
				$infos['Statut'] = "KO";
				$infos['Message'] = $e->getMessage();
				sendJSON($infos, 503);
			}
		} else {
			// Données manquantes
			$infos['Statut'] = "KO";
			$infos['Message'] = "Données incomplètes";
			sendJSON($infos, 400);
		}
	}	
?>