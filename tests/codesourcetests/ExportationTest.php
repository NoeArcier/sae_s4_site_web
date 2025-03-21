<?php

use PHPUnit\Framework\TestCase;

require "tests/codesource/exportation.php";

class ExportationTest extends TestCase {

    private $pdoMock;

    protected function setUp(): void {
        // Création d'un mock pour l'objet PDO
        $this->pdoMock = $this->createMock(PDO::class);
    }

    public function testRecupererDonneesAvecReservation() {
        
        $table = 'reservation';
        
        $stmtMock = $this->createMock(PDOStatement::class);
        $stmtMock->method('fetchAll')->willReturn([
            [
                'id_reservation' => 1,
                'id_salle' => 5,
                'date_reservation' => '2024-03-12',
                'heure_debut' => '14:00:00',
                'heure_fin' => '16:00:00',
                'id_activite' => 2
            ]
        ]);

        $this->pdoMock->method('query')->willReturn($stmtMock);

        $stmtActiviteMock = $this->createMock(PDOStatement::class);
        $stmtActiviteMock->method('fetch')->willReturn(['nom_activite' => 'Réunion']);
        
        $this->pdoMock->method('prepare')->willReturn($stmtActiviteMock);
        
        $resultat = recupererDonnees($this->pdoMock, $table);

        $this->assertNotEmpty($resultat);
        $this->assertEquals('00000005', $resultat[0]['id_salle']);
        $this->assertEquals('12/03/2024', $resultat[0]['date_reservation']);
        $this->assertEquals('14h00', $resultat[0]['heure_debut']);
        $this->assertEquals('16h00', $resultat[0]['heure_fin']);
    }
    
    public function testRecupererDonneesAvecSalle()
    {
        $table = 'salle';

        $stmtMock = $this->createMock(PDOStatement::class);
        $stmtMock->method('fetchAll')->willReturn([
            [
                'id_salle' => 3,
                'nom_salle' => 'Salle A',
                'videoproj' => 1,
                'ecran_xxl' => 0,
                'imprimante' => 1
            ]
        ]);

        $this->pdoMock->method('query')->willReturn($stmtMock);

        $resultat = recupererDonnees($this->pdoMock, $table);

        $this->assertNotEmpty($resultat);
        $this->assertEquals('00000003', $resultat[0]['id_salle']);
        $this->assertEquals('oui', $resultat[0]['videoproj']);
        $this->assertEquals('non', $resultat[0]['ecran_xxl']);
        $this->assertEquals('oui', $resultat[0]['imprimante']);
    }

    public function testGenererCSVString() {
        
        $colonnes = ['ID', 'Nom', 'Age'];
        $donnees = [
            ['1', 'Alice', '30'],
            ['2', 'Bob', '25']
        ];

        $csvSortie = genererCSVString($colonnes, $donnees);
        $valeurCherche = "ID;Nom;Age\n1;Alice;30\n2;Bob;25\n";

        $this->assertEquals($valeurCherche, $csvSortie);
    }
}
?>
