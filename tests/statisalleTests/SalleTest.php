<?php
use PHPUnit\Framework\TestCase;

require "codesource_patch/salle.php";

class SalleTest extends TestCase
{
    private $pdoMock;

    protected function setUp(): void {
        $this->pdoMock = $this->createMock(PDO::class);
    }

    public function testVerifNomSalle() {
        $stmtMock = $this->createMock(PDOStatement::class);
        $stmtMock->expects($this->once())->method('execute');
        $stmtMock->expects($this->once())->method('fetchColumn')->willReturn(1);

        $this->pdoMock->expects($this->once())->method('prepare')->willReturn($stmtMock);

        $this->assertTrue(verifNomSalle($this->pdoMock, 'Salle A', 2));
    }

    public function testCreationSalle() {
        $stmtMock = $this->createMock(PDOStatement::class);
        $stmtMock->expects($this->once())->method('execute');

        $this->pdoMock->expects($this->once())->method('prepare')->willReturn($stmtMock);

        creationSalle($this->pdoMock, 'Salle B', 30, 1, 0, 10, 'PC', 'Windows', 1);
        $this->assertTrue(true);
    }

    public function testRecupAttributSalle() {
        $stmtMock = $this->createMock(PDOStatement::class);
        $stmtMock->expects($this->once())->method('execute');
        $stmtMock->expects($this->once())->method('fetch')->willReturn([
            'nom' => 'Salle C',
            'capacite' => 50,
            'videoproj' => 1
        ]);

        $this->pdoMock->expects($this->once())->method('prepare')->willReturn($stmtMock);

        $resultat = recupAttributSalle($this->pdoMock, 3);
        $this->assertEquals('Salle C', $resultat['nom']);
        $this->assertEquals(50, $resultat['capacite']);
    }

    public function testMettreAJourSalle() {
        $stmtMock = $this->createMock(PDOStatement::class);
        $stmtMock->expects($this->once())->method('execute');
        $stmtMock->expects($this->once())->method('rowCount')->willReturn(1);

        $this->pdoMock->expects($this->once())->method('prepare')->willReturn($stmtMock);

        $resultat = mettreAJourSalle($this->pdoMock, 3, 'Salle D', 40, 1, 1, 15, 'Mac', 'Linux', 0);
        $this->assertEquals("Salle mise à jour avec succès !", $resultat);
    }

    public function testSupprimerSalle() {
        $stmtMock = $this->createMock(PDOStatement::class);
        $stmtMock->expects($this->once())->method('execute');

        $this->pdoMock->expects($this->once())->method('prepare')->willReturn($stmtMock);

        supprimerSalle($this->pdoMock, 3);
        $this->assertTrue(true);
    }

    public function testVerifierReservations() {
        $stmtMock = $this->createMock(PDOStatement::class);
        $stmtMock->expects($this->once())->method('execute');
        $stmtMock->expects($this->once())->method('fetchAll')->willReturn([101, 102]);

        $this->pdoMock->expects($this->once())->method('prepare')->willReturn($stmtMock);

        $resultat = verifierReservations($this->pdoMock);
        $this->assertEquals([101, 102], $resultat);
    }
}

?>