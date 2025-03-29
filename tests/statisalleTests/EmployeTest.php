<?php

use PHPUnit\Framework\TestCase;

require("codesource_patch/employe.php");

class EmployeTest extends TestCase {
    private $pdoMock;
    private $stmtMock;

    protected function setUp(): void {
        $this->pdoMock = $this->createMock(PDO::class);
        $this->stmtMock = $this->createMock(PDOStatement::class);
    }

    public function testRenvoyerEmployes() {
        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->willReturn($this->stmtMock);

        $this->stmtMock->expects($this->once())
            ->method('execute');

        $this->stmtMock->expects($this->once())
            ->method('fetchAll')
            ->willReturn([
                ['nom' => 'Doe', 'prenom' => 'John', 'id_compte' => 'johndoe', 'telephone' => '123456789']
            ]);

        $result = renvoyerEmployes($this->pdoMock);
        $this->assertCount(1, $result);
        $this->assertEquals('Doe', $result[0]['nom']);
    }

    public function testCompterEmployes() {
        $this->pdoMock->expects($this->once())
            ->method('query')
            ->willReturn($this->stmtMock);

        $this->stmtMock->expects($this->once())
            ->method('fetch')
            ->willReturn(['total' => 3]);

        $count = compterEmployes($this->pdoMock);
        $this->assertEquals(3, $count);
    }

    public function testSupprimerEmploye() {
        $this->pdoMock->expects($this->once())
            ->method('beginTransaction');

        $this->pdoMock->expects($this->exactly(2))
            ->method('prepare')
            ->willReturn($this->stmtMock);

        $this->stmtMock->expects($this->exactly(2))
            ->method('execute');

        $this->pdoMock->expects($this->once())
            ->method('commit');

        supprimerEmploye($this->pdoMock, 'E000001');
    }

    public function testAjouterEmploye() {
        $this->pdoMock->expects($this->once())
            ->method('beginTransaction');

        // Simuler la récupération du dernier ID employé
        $this->pdoMock->expects($this->once())
            ->method('query')
            ->willReturn($this->stmtMock);

        $this->stmtMock->expects($this->exactly(2))
            ->method('fetchColumn')
            ->willReturn('E000002'); // Simule le dernier ID trouvé

        // Simuler les préparations des requêtes
        $this->pdoMock->expects($this->exactly(3))
            ->method('prepare')
            ->willReturn($this->stmtMock);

        $this->stmtMock->expects($this->exactly(3))
            ->method('execute');

        $this->pdoMock->expects($this->once())
            ->method('commit');

        ajouterEmploye($this->pdoMock, 'Doe', 'John', 'johndoe', '123456789', 'password', 1);
    }
}
?>
