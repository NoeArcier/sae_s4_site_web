<?php

namespace services;

use PDO;
use PDOStatement;

/**
 * classe service permettant d'accéder à la base de données
 * pour effectuer des opérations sur les salles
 */
class SalleService {

    /**
     * récupère toutes les informations sur les salles
     * @param $pdo, accès BD
     * @return array
     */
    public function listeSalles($pdo) {
        
        try {
            $stmt = $pdo->query("SELECT id_salle, nom, capacite, videoproj, ecran_xxl, ordinateur, type, logiciels, imprimante 
                                                   FROM salle 
                                                   ORDER BY nom ASC");
            
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            // Erreur de BD
            throw new PDOException($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Récupère les informations liées à une salle à partir de son identifiant
     * @param $pdo, accès BD
     * @param $idSalle
     * @return mixed|null
     */
    public function infosSalle($pdo, $idSalle) {
        try {
            // Sélectionner les attributs spécifiques de la salle au lieu de "*"
            $stmt = $pdo->prepare("SELECT nom, capacite, videoproj, ecran_xxl, ordinateur, type, logiciels, imprimante 
                                   FROM salle 
                                   WHERE id_salle = :id_salle");

            $stmt->bindParam(':id_salle', $idSalle, PDO::PARAM_INT);
            $stmt->execute();
            $resultat = $stmt->fetch(PDO::FETCH_ASSOC);

            return $resultat;

        } catch (PDOException $e) {
            // Gérer les erreurs potentielles liées à la base de données
            echo "Erreur lors de la récupération des attributs de la salle : " . $e->getMessage();
            return null;  // Retourner null en cas d'erreur
        }
    }

    /**
     * Insère une nouvelle salle dans la table "salle" avec
     * les informations récupérées sur le formulaire "creationSalle.php"
     * @param $pdo, accès BD
     * @param $nom
     * @param $capacite
     * @param $videoProjecteur
     * @param $ecranXXL
     * @param $nbOrdi
     * @param $typeMateriel
     * @param $logiciel
     * @param $imprimante
     * @return void
     */
    public function ajoutSalle($pdo, $nom, $capacite,
                                      $videoProjecteur, $ecranXXL, $nbOrdi,
                                      $typeMateriel, $logiciel, $imprimante) {

        $stmt = $pdo->prepare("INSERT INTO salle ( nom, capacite, videoproj, ecran_xxl, ordinateur, type, logiciels, imprimante) 
                               VALUES (:nom, :capacite, :videoproj, :ecranXXL, :nbOrdi, :type, :logiciels, :imprimante);");

        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':capacite', $capacite);
        $stmt->bindParam(':videoproj', $videoProjecteur);
        $stmt->bindParam(':ecranXXL', $ecranXXL);
        $stmt->bindParam(':nbOrdi', $nbOrdi);
        $stmt->bindParam(':type', $typeMateriel);
        $stmt->bindParam(':logiciels', $logiciel);
        $stmt->bindParam(':imprimante', $imprimante);
        $stmt->execute();
        
    }

    /**
     * modifie les informations liées à la salle
     * @param $pdo, accès BD
     * @param $idSalle
     * @param $nom
     * @param $capacite
     * @param $videoProjecteur
     * @param $ecranXXL
     * @param $nbOrdi
     * @param $typeMateriel
     * @param $logiciel
     * @param $imprimante
     * @return string
     */
    public function modificationSalle($pdo, $idSalle, $nom, $capacite,
                                      $videoProjecteur, $ecranXXL, $nbOrdi,
                                      $typeMateriel, $logiciel, $imprimante) {
        
        try {
            $videoProjecteur = (intval($videoProjecteur) == 1) ? 1 : 0;
            $ordinateurXXL = (intval($ordinateurXXL) == 1) ? 1 : 0;
            $imprimante = (intval($imprimante) == 1) ? 1 : 0;

            $stmt = $pdo->prepare("UPDATE salle
                                    SET nom = :nom,
                                        capacite = :capacite,
                                        videoproj = :videoproj,
                                        ecran_xxl = :ecranXXL,
                                        ordinateur = :nbrOrdi,
                                        type = :type,
                                        logiciels = :logiciels,
                                        imprimante = :imprimante
                                    WHERE id_salle = :id_salle;");

            $stmt->bindParam(':nom', $nom);
            $stmt->bindParam(':capacite', $capacite, PDO::PARAM_INT);
            $stmt->bindParam(':videoproj', $videoProjecteur, PDO::PARAM_INT);
            $stmt->bindParam(':ecranXXL', $ecranXXL, PDO::PARAM_INT);
            $stmt->bindParam(':nbrOrdi', $nbOrdi, PDO::PARAM_INT);
            $stmt->bindParam(':type', $typeMateriel);
            $stmt->bindParam(':logiciels', $logiciel);
            $stmt->bindParam(':imprimante', $imprimante, PDO::PARAM_INT);
            $stmt->bindParam(':id_salle', $idSalle, PDO::PARAM_INT);
            $stmt->execute();

            // Vérifier si des lignes ont été mises à jour
            if ($stmt->rowCount() > 0) {
                return "Salle mise à jour avec succès !";
            } else {
                return "Aucune modification n'a été effectuée. Vérifiez les données.";
            }

        } catch (PDOException $e) {
            // En cas d'erreur, retourner l'erreur PDO
            return "Erreur lors de la mise à jour : " . $e->getMessage();
        }
        
    }

    /**
     * Supprime la salle associé
     * @param $pdo, accès BD
     * @param $id_salle
     * @return array
     */
    public function supprimerSalle($pdo, $idSalle) {
        try {
            $requete = "DELETE FROM salle WHERE id_salle = :idSalle";
            $stmt = $pdo->prepare($requete);
            $stmt->execute(['idSalle' => $idSalle]);
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), $e->getCode());
        }
    }

}

?>