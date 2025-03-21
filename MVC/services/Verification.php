<?php

namespace services;

class Verification
{
    /**
     * Vérifie si un nom de salle existe déjà dans la base de données.
     *
     * @param \PDO $pdo Connexion à la base de données
     * @param string $nomSalle Nom de la salle à vérifier
     * @return bool Retourne true si le nom existe, false sinon
     */
    public static function verifNomSalle(\PDO $pdo, string $nomSalle): bool
    {
        $sql = "SELECT 1 FROM salle WHERE nom = :nomSalle LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':nomSalle', $nomSalle, \PDO::PARAM_STR);
        $stmt->execute();

        return (bool) $stmt->fetchColumn();
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
    public static function verifierInfosSalles(string $nomSalle, int $capacite, bool $videoProjecteur, bool $ecranXXL): bool
    {
        $nomSalle = trim($nomSalle); // Supprime les espaces inutiles
        
        if (empty($nomSalle) || strlen($nomSalle) < 3 || is_numeric($nomSalle)) {
            return false; // Le nom de la salle doit contenir au moins 3 caractères et ne pas être uniquement numérique
        }
        
        if ($capacite <= 0) {
            return false; // La capacité doit être strictement positive
        }

        return true; // Toutes les vérifications sont validées
    }
}
