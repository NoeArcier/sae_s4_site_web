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
     * 
     */
    public function listeSalles($pdo) {

    }

    /**
     * 
     */
    public function infosSalle($pdo, $idSalle) {

    }

    /**
     * 
     */
    public function ajoutSalle($pdo, $nom, $capacite,
                                      $videoProjecteur, $ecranXXL, $nbOrdi,
                                      $typeMateriel, $logiciel, $imprimante) {
        
    }

    /**
     * 
     */
    public function modificationSalle($pdo, $idSalle, $nom, $capacite,
                                      $videoProjecteur, $ecranXXL, $nbOrdi,
                                      $typeMateriel, $logiciel, $imprimante) {
        
    }

    /**
     * 
     */
    public function supprimerSalle($pdo, $idSalle) {
        
    }

}