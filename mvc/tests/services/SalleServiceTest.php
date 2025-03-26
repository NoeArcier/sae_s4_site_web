<?php

namespace services;

use PDO;
use PDOStatement;
use PHPUnit\Framework\TestCase;
use services\SalleService;

class SalleServiceTest extends TestCase {

    private SalleService $salleService;
    
    private PDO $pdo;
    private PDOStatement $pdoStatement;
    
    private $sallesMock = [
        [
            "id_salle" => "1", 
            "nom" => "Salle Azur", 
            "capacite" => 10, 
            "videoproj" => 1, 
            "ecran_xxl" => 1,
            "ordinateur" => "Colleco Vision", 
            "imprimante" => 0, 
            "logiciels" => "notepad++", 
            "type" => "PC portable"
        ], 
            
        [
            "id_salle" => "2", 
            "nom" => "Salle Rouge", 
            "capacite" => 10, 
            "videoproj" => 1, 
            "ecran_xxl" => 1,
            "ordinateur" => "Colleco Vision", 
            "imprimante" => 0, 
            "logiciels" => "notepad++", 
            "type" => "PC portable"
        ]
    ];
    
    public function setUp(): void {
        
        parent::setUp();
        
        // Devient un test d'intégration
        $this->salleService = new SalleService();
        
        $this->pdo = $this->createMock(PDO::class);
        $this->pdoStatement = $this->createMock(PDOStatement::class);
    }
    
    /**
     * @covers
     */
    public function testListeSallesExistantes() {
        
        $this->pdoStatement->method('fetchAll')->willReturn($this->sallesMock);
        $this->pdo->method('query')->willReturn($this->pdoStatement);
        
        self::assertSame($this->sallesMock, $this->salleService->listeSalles($this->pdo));
    }
    
    /**
     * @covers
     */
    public function testListeSallesNonExistantes() {
        
        $this->pdoStatement->method('fetchAll')->willReturn([]);
        $this->pdo->method('query')->willReturn($this->pdoStatement);
        
        self::assertSame([], $this->salleService->listeSalles($this->pdo));
    }
    
    /**
     * @covers
     */
    public function testInfosSalleTrouvees() {
        
        $salleTrouveeMock = [
            "nom" => "Salle Azur", 
            "capacite" => 10, 
            "videoproj" => 1, 
            "ecran_xxl" => 1,
            "ordinateur" => "Colleco Vision", 
            "imprimante" => 0, 
            "logiciels" => "notepad++", 
            "type" => "PC portable"
        ];
        
        $this->pdoStatement->method('fetch')->willReturn($salleTrouveeMock);
        $this->pdo->method('prepare')->willReturn($this->pdoStatement);
        
        self::assertSame($salleTrouveeMock, $this->salleService->infosSalle($this->pdo, "1"));
    }
    
    /**
     * @covers
     */
    public function testInfosSalleNonExistantes() {

        $this->pdoStatement->method('fetch')->willReturn(null);
        $this->pdo->method('prepare')->willReturn($this->pdoStatement);
        
        self::assertSame(null, $this->salleService->infosSalle($this->pdo, "3"));
    }
    
    /**
     * @covers
     */
    public function testAjoutSalle() {
    
        $nom = "Salle Azur";
        $capacite = 10;
        $videoProjecteur = 1;
        $ecranXXL = 1;
        $nbOrdi = 2;
        $typeMateriel = "PC portable";
        $logiciel = "notepad++";
        $imprimante = 0;

        $this->pdoStatement->expects($this->once())
                            ->method('execute')
                            ->willReturn(true);

        $this->pdo->method('prepare')->willReturn($this->pdoStatement);
        
        $this->salleService->ajoutSalle(
            $this->pdo, $nom, $capacite, $videoProjecteur,
            $ecranXXL, $nbOrdi, $typeMateriel, $logiciel, $imprimante
        );
    }
    
    /**
     * @covers
     */
    public function testModificationSalleEffectuee() {
        
        $id_salle = "1";
        $nom = "Salle Azur";
        $capacite = 10;
        $videoProjecteur = 1;
        $ecranXXL = 1;
        $nbOrdi = 2;
        $typeMateriel = "PC portable";
        $logiciel = "notepad++";
        $imprimante = 0;
        
        // Une seule fois la méthode execute = true
        $this->pdoStatement->expects($this->once())
                            ->method('execute')
                            ->willReturn(true);

        $this->pdoStatement->method('rowCount')->willReturn(1);
        $this->pdo->method('prepare')->willReturn($this->pdoStatement);

        $result = $this->salleService->modificationSalle(
            $this->pdo, $idSalle, $nom, $capacite, 
            $videoProjecteur, $ecranXXL, $nbOrdi, 
            $typeMateriel, $logiciel, $imprimante
        );

        self::assertSame("Salle mise à jour avec succès !", $result);
    }
    
    /**
     * @covers
     */
    public function testModificationSalleNonEffectuee() {
        
        $id_salle = "1";
        $nom = "Salle Azur";
        $capacite = 10;
        $videoProjecteur = 1;
        $ecranXXL = 1;
        $nbOrdi = 2;
        $typeMateriel = "PC portable";
        $logiciel = "notepad++";
        $imprimante = 0;
        
        // Une seule fois la méthode execute = true
        $this->pdoStatement->expects($this->once())
                            ->method('execute')
                            ->willReturn(true);

        $this->pdoStatement->method('rowCount')->willReturn(0);
        $this->pdo->method('prepare')->willReturn($this->pdoStatement);

        $result = $this->salleService->modificationSalle(
            $this->pdo, $idSalle, $nom, $capacite, 
            $videoProjecteur, $ecranXXL, $nbOrdi, 
            $typeMateriel, $logiciel, $imprimante
        );

        self::assertSame("Aucune modification n'a été effectuée. Vérifiez les données.", $result);
    }
    
    // TODO méthode suppression
}