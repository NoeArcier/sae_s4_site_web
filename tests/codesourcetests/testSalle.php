<?php
use PHPUnit\Framework\TestCase;

require "liaisonBD.php";
require "votre_fichier.php"; // Remplacez par le vrai nom du fichier

class SalleTest extends TestCase {
    private $pdo;

    protected function setUp(): void {
        $this->pdo = connecteBD();
        $this->pdo->exec("DELETE FROM salle"); // Nettoyage de la table avant chaque test
    }

    public function testVerifNomSalle() {
        $this->pdo->exec("INSERT INTO salle (id_salle, nom) VALUES (1, 'Salle A')");
        $this->assertTrue(verifNomSalle('Salle A', 2)); // Nom déjà existant
        $this->assertFalse(verifNomSalle('Salle B', 3)); // Nom unique
    }

    public function testCreationSalle() {
        creationSalle('Salle Test', 50, 1, 0, 10, 'Ordinateurs', 'Windows', 1);
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM salle WHERE nom = 'Salle Test'");
        $this->assertEquals(1, $stmt->fetchColumn());
    }

    public function testRecupAttributSalle() {
        $this->pdo->exec("INSERT INTO salle (id_salle, nom, capacite) VALUES (1, 'Salle A', 30)");
        $result = recupAttributSalle(1);
        $this->assertEquals('Salle A', $result['nom']);
        $this->assertEquals(30, $result['capacite']);
    }

    public function testMettreAJourSalle() {
        $this->pdo->exec("INSERT INTO salle (id_salle, nom, capacite) VALUES (1, 'Ancienne Salle', 20)");
        $message = mettreAJourSalle(1, 'Nouvelle Salle', 50, 1, 1, 5, 'Type', 'Logiciel', 1);
        $this->assertStringContainsString('Salle mise à jour', $message);
        
        $stmt = $this->pdo->query("SELECT nom, capacite FROM salle WHERE id_salle = 1");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->assertEquals('Nouvelle Salle', $result['nom']);
        $this->assertEquals(50, $result['capacite']);
    }

    public function testSupprimerSalle() {
        $this->pdo->exec("INSERT INTO salle (id_salle, nom) VALUES (1, 'Salle à Supprimer')");
        supprimerSalle(1);
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM salle WHERE id_salle = 1");
        $this->assertEquals(0, $stmt->fetchColumn());
    }
}
