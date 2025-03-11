<?php

use PHPUnit\Framework\TestCase;

require("fonction/employe.php");

class EmployeTest extends TestCase {
    private $pdo;

    protected function setUp(): void {
        $this->pdo = new PDO('sqlite::memory:');
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->createTables();
    }

    private function createTables() {
        $this->pdo->exec("CREATE TABLE employe (
            id_employe TEXT PRIMARY KEY, 
            nom TEXT, 
            prenom TEXT, 
            telephone TEXT
        );");

        $this->pdo->exec("CREATE TABLE login (
            login TEXT PRIMARY KEY, 
            mdp TEXT, 
            id_type INTEGER, 
            id_employe TEXT,
            FOREIGN KEY(id_employe) REFERENCES employe(id_employe)
        );");
    }

    public function testRenvoyerEmployes() {
        global $pdo;
        $pdo = $this->pdo;
        $pdo->exec("INSERT INTO employe VALUES ('E000001', 'Doe', 'John', '123456789');");
        $pdo->exec("INSERT INTO login VALUES ('johndoe', 'password', 1, 'E000001');");

        $result = renvoyerEmployes();
        $this->assertCount(1, $result);
    }

    public function testCompterEmployes() {
        global $pdo;
        $pdo = $this->pdo;
        $pdo->exec("INSERT INTO employe VALUES ('E000001', 'Doe', 'John', '123456789');");

        $count = compterEmployes();
        $this->assertEquals(1, $count);
    }

    public function testVerifMdp() {
        $this->assertTrue(verifMdp("Password@123"));
        $this->assertFalse(verifMdp("short"));
        $this->assertFalse(verifMdp("NoSpecialChar1"));
    }

    public function testVerifLogin() {
        global $pdo;
        $pdo = $this->pdo;
        $pdo->exec("INSERT INTO login VALUES ('johndoe', 'password', 1, 'E000001');");
        
        $this->assertTrue(verifLogin('johndoe'));
        $this->assertFalse(verifLogin('janedoe'));
    }

    public function testSupprimerEmploye() {
        global $pdo;
        $pdo = $this->pdo;
        $pdo->exec("INSERT INTO employe VALUES ('E000001', 'Doe', 'John', '123456789');");
        $pdo->exec("INSERT INTO login VALUES ('johndoe', 'password', 1, 'E000001');");
        
        supprimerEmploye('E000001');
        $stmt = $pdo->query("SELECT COUNT(*) FROM employe;");
        $this->assertEquals(0, $stmt->fetchColumn());
    }
}
