<?php
	// Données
		
	function getPDO(){
		// Retourne un objet connexion à la BD
		$host='localhost';	// Serveur de BD
		$db='statisallebd';		// Nom de la BD
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
	
	function getSignalements($identifiant) {
		try {
			$pdo=getPDO();
			$requete='SELECT id, date, heure, titre, resume, impact, recontact
			FROM signalements 
			JOIN reservation ON signalements.id_reservation = reservation.id_reservation
			WHERE signalements.id_reservation = (SELECT id_reservation
												 FROM reservation
												 WHERE id_employe = :IDENTIFIANT)
			order by date DESC, heure DESC, impact DESC'; 
			
			$stmt = $pdo->prepare($requete);
			$stmt->bindParam(":IDENTIFIANT", $identifiant);										// Préparation de la requête
			$stmt->execute();	
				
			$signalement=$stmt ->fetchALL();
			$stmt->closeCursor();
			$stmt=null;
			$pdo=null;

			sendJSON($signalement, 200) ;
		} catch(PDOException $e){
			$infos['Statut']="KO";
			$infos['message']=$e->getMessage();
			sendJSON($infos, 500) ;
		}
	}

	function getReservations($idEmploye) {
		try {
			$pdo=getPDO();
			$requete='SELECT reservation.id_reservation AS ID, reservation.date_reservation AS DATE, reservation.heure_debut AS HDEBUT, reservation.heure_fin AS HFIN, salle.nom AS SALLE, activite.nom_activite AS ACTIVITE
			FROM reservation
			JOIN salle ON reservation.id_salle = salle.id_salle
			JOIN activite ON reservation.id_activite = activite.id_activite
			WHERE id_employe = :id
			order by date_reservation DESC';
			
			$stmt = $pdo->prepare($requete); // Préparation de la requête
			$stmt->bindParam("id", $idEmploye);
			$stmt->execute();	
				
			$reservations=$stmt->fetchALL();
			$stmt->closeCursor();
			$stmt=null;
			$pdo=null;

			sendJSON($reservations, 200) ;
		} catch(PDOException $e){
			$infos['Statut']="KO";
			$infos['message']=$e->getMessage();
			sendJSON($infos, 500) ;
		}
	}

	function modifierSignalement($donneesJson, $identifiant) {
		if ($donneesJson['TITRE'] != "" && $donneesJson['RESUME'] != ""
			&& $donneesJson['IMPACT'] != "" && $donneesJson['RECONTACT'] != "") {
			// Données remplies, on modifie dans la table stockprix
			try {
				$pdo = getPDO();
	
				// Mise à jour du prix et du stock
				$requete = "UPDATE signalements SET titre = :TITRE, resume = :RESUME, impact = :IMPACT, recontact = :RECONTACT WHERE id = :IDENTIFIANT";
				$stmt = $pdo->prepare($requete);
				$stmt->bindParam(":IDENTIFIANT", $identifiant);
				$stmt->bindParam(":TITRE", $donneesJson['TITRE']);
				$stmt->bindParam(":RESUME", $donneesJson['RESUME']);
				$stmt->bindParam(":IMPACT", $donneesJson['IMPACT']);
				$stmt->bindParam(":RECONTACT", $donneesJson['RECONTACT']);
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
	
	function ajoutSignalement($donneesJson) {
		if($donneesJson['TITRE'] != ""
			&& $donneesJson['RESUME'] != ""
			&& $donneesJson['IMPACT'] != ""
			&& $donneesJson['RECONTACT'] != ""
			&& $donneesJson['RESERVATION'] != ""
		  ){
			  // Données remplies, on insère dans la table
			try {
				$pdo=getPDO();
				$requete='INSERT INTO signalements(date, heure, titre, resume, impact, recontact, id_reservation) VALUES (CURRENT_DATE(), CURRENT_TIME(), :TITRE, :RESUME, :IMPACT, :RECONTACT, :RESERVATION)';
				$stmt = $pdo->prepare($requete);						// Préparation de la requête
				$stmt->bindParam(":TITRE", $donneesJson['TITRE']);				
				$stmt->bindParam(":RESUME", $donneesJson['RESUME']);
				$stmt->bindParam(":IMPACT", $donneesJson['IMPACT']);
				$stmt->bindParam(":RECONTACT", $donneesJson['RECONTACT']);
				$stmt->bindParam(":RESERVATION", $donneesJson['RESERVATION']);
				$stmt->execute();	
				
				$IdInsere=$pdo->lastInsertId() ;
					
				$stmt=null;
				$pdo=null;
				
				// Retour des informations au client (statut + id créé)
				$infos['Statut']="OK";
				$infos['ID']=$IdInsere;

				sendJSON($infos, 201) ;
			} catch(PDOException $e){
				// Retour des informations au client 
				$infos['Statut']="KO";
				$infos['message']=$e->getMessage();

				sendJSON($infos, 500) ;
			}
		} else {
			// Données manquantes, Retour des informations au client 
			$infos['Statut']="KO";
			$infos['message']="Données incomplètes";
			sendJSON($infos, 400) ;
		}
	}

	function supprimeSignalement($idSignalement) {
		try {
			$pdo=getPDO();
			$requete='delete from signalements where id = :ID';  

			$stmt = $pdo->prepare($requete);						// Préparation de la requête
			$stmt->bindParam(":ID", $idSignalement);
			
			$stmt->execute();	
			$deleted = $stmt->rowCount();	

			$stmt->closeCursor();
			$stmt=null;
			$pdo=null;
			if ($deleted !=0) {
				$infos['Statut']="OK";
				$infos['message']="Signalement supprimé";
				sendJSON($infos, 200) ;
			} else {
				$infos['Statut']="KO";
				$infos['message']="ID inexistant";
				sendJSON($infos, 400) ;
			}
			
		} catch(PDOException $e){
			// Retour des informations au client 
			$infos['Statut']="KO";
			$infos['message']=$e->getMessage();

			sendJSON($infos, 500) ;
		}
	}
?>