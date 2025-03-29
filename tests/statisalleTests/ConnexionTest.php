<?php

use PHPUnit\Framework\TestCase;

require("codesource_patch/connexion.php");

class ConnexionTest extends TestCase {
    
    private $pdoMock;
    private $stmtMock;

    protected function setUp(): void {
        $this->pdoMock = $this->createMock(PDO::class);
        $this->stmtMock = $this->createMock(PDOStatement::class);
    }

    public function testVerificationUtilisateurExistant() {
        $this->pdoMock->method('prepare')->willReturn($this->stmtMock);
        $this->stmtMock->method('fetch')->willReturn([
            'id_login' => 1,
            'login' => 'testUser',
            'mdp' => 'hashedPassword',
            'id_employe' => 123
        ]);
        
        $result = verif_utilisateur($this->pdoMock, 'testUser');
        
        $this->assertIsArray($result);
        $this->assertEquals(1, $result['id_login']);
        $this->assertEquals('testUser', $result['login']);
    }

    public function testVerificationMotDePasseCorrect() {
        $this->pdoMock->method('prepare')->willReturn($this->stmtMock);
        $this->stmtMock->method('fetch')->willReturn([
            'id_login' => 1,
            'login' => 'testUser',
            'mdp' => 'hashedPassword'
        ]);
        
        $result = verif_mdp($this->pdoMock, 'hashedPassword');
        
        $this->assertIsArray($result);
        $this->assertEquals('hashedPassword', $result['mdp']);
    }

    public function testTypeUtilisateur() {
        $this->pdoMock->method('prepare')->willReturn($this->stmtMock);
        $this->stmtMock->method('fetch')->willReturn([
            'id_type' => 2,
            'nom_type' => 'admin'
        ]);
        
        $result = type_utilisateur($this->pdoMock, 'testUser', 'hashedPassword');
        
        $this->assertIsArray($result);
        $this->assertEquals(2, $result['id_type']);
        $this->assertEquals('admin', $result['nom_type']);
    }

    public function testVerificationSession() {
        $_SESSION = ['id' => 1];
        
        $this->expectNotToPerformAssertions();
        
        verif_session(); // Ne doit pas rediriger car la session est active
    }
}