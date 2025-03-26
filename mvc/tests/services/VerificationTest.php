<?php

namespace services;

use PDO;
use PDOStatement;
use PHPUnit\Framework\TestCase;
use services\Verification;

class VerificationTest extends TestCase {
    
    public function setUp(): void {
        
        parent::setUp();
        
        $this->pdo = $this->createMock(PDO::class);
        $this->pdoStatement = $this->createMock(PDOStatement::class);
    }
    
    /**
     * @covers
     */
    public function testVerifNomSalleSucces() {
        
        $this->pdoStatement->expects($this->once())
                            ->method('execute')
                            ->willReturn(true);

        $this->pdoStatement->method('fetchColumn')->willReturn(1);
        $this->pdo->method('prepare')->willReturn($this->pdoStatement);
        
        self::assertSame(true, Verification::verifNomSalle($this->pdo, "test"));
    }
    
    /**
     * @covers
     */
    public function testVerifNomSalleEchec() {
        
        $this->pdoStatement->expects($this->once())
                            ->method('execute')
                            ->willReturn(true);

        $this->pdoStatement->method('fetchColumn')->willReturn(0);
        $this->pdo->method('prepare')->willReturn($this->pdoStatement);
        
        self::assertSame(false, Verification::verifNomSalle($this->pdo, "test"));
    }
    
    /**
     * @covers
     */
    public function testVerifierInfosSallesAucunesErreurs() {
        self::assertSame([], Verification::verifierInfosSalles("IncidAndroid", 1, "oui", "non"));
    }
    
    /**
     * @covers
     */
    public function testVerifierInfosSallesAvecErreurs() {
        
        self::assertSame([
            "nomSalle" => "Le nom de la salle est obligatoire, il ne doit pas être numérique et doit avoir au moins 3 caractères.", 
            "capacite" => "La capacité est obligatoire et doit être positive.", 
            "videoProjecteur" => "Le choix pour le vidéo projecteur est obligatoire.", 
            "ecranXXL" => "Le choix pour l'ordinateur XXL est obligatoire."
        ], Verification::verifierInfosSalles("    ", -1, "", ""));
    }
    
    /**
     * @covers
     */
    public function testVerifiserSalleAReservations() {
        
        $id_salle = 1;

        $this->pdoStatement->method('execute')->willReturn(true);
        $this->pdoStatement->method('fetchAll')->willReturn(['nb' => 3]); // Simule 3 réservations
        $this->pdo->method('prepare')->willReturn($this->pdoStatement);

        $resultat = Verification::verifiserSalleAReservations($this->pdo, $id_salle);
        $this->assertTrue($resultat);
    }
    
    /**
     * @covers
     */
    public function testVerifiserSalleAucunesReservations() {
        $id_salle = 1;
        
        $this->pdoStatement->method('execute')->willReturn(true);
        $this->pdoStatement->method('fetchAll')->willReturn(['nb' => 0]); // Simule 0 réservations
        $this->pdo->method('prepare')->willReturn($this->pdoStatement);
        
        $resultat = Verification::verifiserSalleAReservations($this->pdo, $id_salle);
        $this->assertFalse($resultat);
    }
}