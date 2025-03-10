<?php
use PHPUnit\Framework\TestCase;

require "liaisonBD.php";
require "votre_fichier_de_methodes.php"; // Remplacez par le vrai nom du fichier

class ReservationTest extends TestCase {
    private $pdo;

    protected function setUp(): void {
        $this->pdo = connecteBD();
        // Nettoyage des tables avant chaque test
        $this->pdo->exec("DELETE FROM reservation");
        $this->pdo->exec("DELETE FROM salle");
        $this->pdo->exec("DELETE FROM employe");
        $this->pdo->exec("DELETE FROM activite");
        $this->pdo->exec("DELETE FROM reservation_reunion");
        $this->pdo->exec("DELETE FROM reservation_formation");
        $this->pdo->exec("DELETE FROM reservation_entretien");
        $this->pdo->exec("DELETE FROM reservation_pret_louer");
        $this->pdo->exec("DELETE FROM reservation_autre");
    }

    public function testAffichageReservation() {
        // Insertion de données de test
        $this->pdo->exec("INSERT INTO salle (id_salle, nom) VALUES (1, 'Salle A')");
        $this->pdo->exec("INSERT INTO employe (id_employe, nom, prenom) VALUES (1, 'Dupont', 'Jean')");
        $this->pdo->exec("INSERT INTO activite (id_activite, nom_activite) VALUES (1, 'Réunion')");
        $this->pdo->exec("INSERT INTO reservation (id_reservation, id_salle, id_employe, id_activite, date_reservation, heure_debut, heure_fin) 
                          VALUES ('R000001', 1, 1, 1, '2023-10-15', '09:00', '10:00')");

        $resultat = affichageReservation();
        $this->assertIsArray($resultat);
        $this->assertNotEmpty($resultat);
        $this->assertEquals('Salle A', $resultat[0]['nom_salle']);
    }

    public function testAffichageMesReservations() {
        // Insertion de données de test
        $this->pdo->exec("INSERT INTO salle (id_salle, nom) VALUES (1, 'Salle A')");
        $this->pdo->exec("INSERT INTO employe (id_employe, nom, prenom) VALUES (1, 'Dupont', 'Jean')");
        $this->pdo->exec("INSERT INTO activite (id_activite, nom_activite) VALUES (1, 'Réunion')");
        $this->pdo->exec("INSERT INTO reservation (id_reservation, id_salle, id_employe, id_activite, date_reservation, heure_debut, heure_fin) 
                          VALUES ('R000001', 1, 1, 1, '2023-10-15', '09:00', '10:00')");

        $resultat = affichageMesReservations(1);
        $this->assertIsArray($resultat);
        $this->assertNotEmpty($resultat);
        $this->assertEquals('Salle A', $resultat[0]['nom_salle']);
    }

    public function testAffichageTypeReservation() {
        // Insertion de données de test
        $this->pdo->exec("INSERT INTO salle (id_salle, nom) VALUES (1, 'Salle A')");
        $this->pdo->exec("INSERT INTO employe (id_employe, nom, prenom) VALUES (1, 'Dupont', 'Jean')");
        $this->pdo->exec("INSERT INTO activite (id_activite, nom_activite) VALUES (1, 'Réunion')");
        $this->pdo->exec("INSERT INTO reservation (id_reservation, id_salle, id_employe, id_activite, date_reservation, heure_debut, heure_fin) 
                          VALUES ('R000001', 1, 1, 1, '2023-10-15', '09:00', '10:00')");
        $this->pdo->exec("INSERT INTO reservation_reunion (id_reservation, objet) VALUES ('R000001', 'Réunion de projet')");

        $resultat = affichageTypeReservation('R000001');
        $this->assertIsArray($resultat);
        $this->assertNotEmpty($resultat);
        $this->assertEquals('Réunion de projet', $resultat['objet']);
    }

    public function testInsertionReservation() {
        // Insertion de données de test
        $this->pdo->exec("INSERT INTO salle (id_salle, nom) VALUES (1, 'Salle A')");
        $this->pdo->exec("INSERT INTO employe (id_employe, nom, prenom) VALUES (1, 'Dupont', 'Jean')");
        $this->pdo->exec("INSERT INTO activite (id_activite, nom_activite) VALUES (1, 'Réunion')");
        $this->pdo->exec("INSERT INTO login (id_login, id_employe) VALUES (1, 1)");

        $resultat = insertionReservation('Salle A', 'Réunion', '2023-10-15', '09:00', '10:00', 'Réunion de projet', 'Dupont', 'Jean', '0123456789', '', 1);
        $this->assertEquals("Réservation insérée avec succès", $resultat);

        // Vérification que la réservation a bien été insérée
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM reservation WHERE id_reservation = 'R000001'");
        $this->assertEquals(1, $stmt->fetchColumn());
    }

    public function testSupprimerResa() {
        // Insertion de données de test
        $this->pdo->exec("INSERT INTO salle (id_salle, nom) VALUES (1, 'Salle A')");
        $this->pdo->exec("INSERT INTO employe (id_employe, nom, prenom) VALUES (1, 'Dupont', 'Jean')");
        $this->pdo->exec("INSERT INTO activite (id_activite, nom_activite) VALUES (1, 'Réunion')");
        $this->pdo->exec("INSERT INTO reservation (id_reservation, id_salle, id_employe, id_activite, date_reservation, heure_debut, heure_fin) 
                          VALUES ('R000001', 1, 1, 1, '2023-10-15', '09:00', '10:00')");

        supprimerResa('R000001');
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM reservation WHERE id_reservation = 'R000001'");
        $this->assertEquals(0, $stmt->fetchColumn());
    }

    public function testModifReservation() {
        // Insertion de données de test
        $this->pdo->exec("INSERT INTO salle (id_salle, nom) VALUES (1, 'Salle A'), (2, 'Salle B')");
        $this->pdo->exec("INSERT INTO employe (id_employe, nom, prenom) VALUES (1, 'Dupont', 'Jean')");
        $this->pdo->exec("INSERT INTO activite (id_activite, nom_activite) VALUES (1, 'Réunion'), (2, 'Formation')");
        $this->pdo->exec("INSERT INTO reservation (id_reservation, id_salle, id_employe, id_activite, date_reservation, heure_debut, heure_fin) 
                          VALUES ('R000001', 1, 1, 1, '2023-10-15', '09:00', '10:00')");
        $this->pdo->exec("INSERT INTO reservation_reunion (id_reservation, objet) VALUES ('R000001', 'Réunion de projet')");

        $resultat = modifReservation('R000001', 'Salle B', 'Formation', '2023-10-16', '10:00', '11:00', 'Formation PHP', 'Martin', 'Paul', '0987654321', '', 'Réunion');
        $this->assertEquals("Réservation modifiée avec succès", $resultat);

        // Vérification que la réservation a bien été modifiée
        $stmt = $this->pdo->query("SELECT id_salle, id_activite FROM reservation WHERE id_reservation = 'R000001'");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->assertEquals(2, $result['id_salle']);
        $this->assertEquals(2, $result['id_activite']);
    }
}