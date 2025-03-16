<?php

use PHPUnit\Framework\TestCase;

require "fonction/liaisonBD.php";

class ExportationTest extends TestCase {
    private $pdo;

    protected function setUp(): void {
        $this->pdo = new PDO('sqlite::memory:');
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo->exec("CREATE TABLE reservation (
            id_reservation INTEGER PRIMARY KEY,
            id_salle INTEGER,
            date_reservation TEXT,
            heure_debut TEXT,
            heure_fin TEXT,
            id_activite INTEGER
        )");

        $this->pdo->exec("CREATE TABLE activite (
            id_activite INTEGER PRIMARY KEY,
            nom_activite TEXT
        )");
    }

    public function testRecupererDonneesReservation() {
        $this->pdo->exec("INSERT INTO activite (id_activite, nom_activite) VALUES (1, 'Réunion')");
        $this->pdo->exec("INSERT INTO reservation (id_reservation, id_salle, date_reservation, heure_debut, heure_fin, id_activite)
                           VALUES (1, 123, '2025-03-09', '14:00:00', '16:00:00', 1)");
        
        $donnees = recupererDonnees('reservation');
        
        $this->assertNotEmpty($donnees);
        $this->assertEquals('00000123', $donnees[0]['id_salle']);
        $this->assertEquals('09/03/2025', $donnees[0]['date_reservation']);
        $this->assertEquals('14h00', $donnees[0]['heure_debut']);
        $this->assertEquals('16h00', $donnees[0]['heure_fin']);
    }

    public function testGenererCSVString() {
        $colonnes = ['ID', 'Nom'];
        $donnees = [
            ['1', 'Test'],
            ['2', 'Exemple']
        ];
        
        $csv = genererCSVString($colonnes, $donnees);
        
        $this->assertStringContainsString("ID;Nom", $csv);
        $this->assertStringContainsString("1;Test", $csv);
        $this->assertStringContainsString("2;Exemple", $csv);
    }
}
?>
