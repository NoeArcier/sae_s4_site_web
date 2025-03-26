<?php

namespace services;

use PDO;

class Verification
{
    /**
     * Vérifie si un nom de salle existe déjà dans la base de données.
     *
     * @param \PDO $pdo Connexion à la base de données
     * @param string $nomSalle Nom de la salle à vérifier
     * @return bool Retourne true si le nom existe, false sinon
     */
    public static function verifNomSalle(PDO $pdo, string $nomSalle): bool
    {
        $sql = "SELECT COUNT(*) AS nb FROM salle WHERE nom = :nomSalle LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':nomSalle', $nomSalle, \PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetchColumn(0) == 1;
    }

    /**
     * Vérifie les informations d'une salle avant l'ajout ou la modification.
     *
     * @param string $nomSalle Nom de la salle
     * @param int $capacite Capacité de la salle (doit être positive)
     * @param bool $videoProjecteur Présence d'un vidéoprojecteur (true/false)
     * @param bool $ecranXXL Présence d'un écran XXL (true/false)
     * @return bool Retourne true si toutes les informations sont valides, false sinon
     */
    public static function verifierInfosSalles(string $nomSalle, int $capacite, bool $videoProjecteur, bool $ecranXXL)
    {
        $nomSalle = trim($nomSalle);
        $erreurs = [];

        // Vérification des champs obligatoires
        if ($nomSalle == "" || strlen($nomSalle) < 3 || is_numeric($nomSalle)) {
            $erreurs['nomSalle'] = "Le nom de la salle est obligatoire, il ne doit pas être numérique et doit avoir au moins 3 caractères.";
        }
        if ($capacite == "" || $capacite <= 0) {
            $erreurs['capacite'] = "La capacité est obligatoire et doit être positive.";
        }
        if ($videoProjecteur == "") {
            $erreurs['videoProjecteur'] = "Le choix pour le vidéo projecteur est obligatoire.";
        }
        if ($ecranXXL == "") {
            $erreurs['ecranXXL'] = "Le choix pour l'ordinateur XXL est obligatoire.";
        }

        return $erreurs;
    }

    /**
     * Vérifie si une salle a des réservations
     * Retourne un tableau contenant les IDs des réservations ou un tableau vide si aucune réservation
     * @param $id_salle
     * @return array
     */
    public static function verifiserSalleAReservations(PDO $pdo, $id_salle) {
        
        try {
            $requete = "SELECT COUNT(*) AS nb FROM reservation WHERE id_salle = :id_salle";
            $stmt = $pdo->prepare($requete);
            $stmt->execute(['id_salle' => $id_salle]);
            return $stmt->fetchAll()['nb'] > 0;
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), $e->getCode());
        }
    }

}
