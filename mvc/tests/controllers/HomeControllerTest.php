<?php

namespace controllers;

use PDO;
use PDOStatement;
use PHPUnit\Framework\TestCase;
use services\SalleService;

class HomeControllerTest extends TestCase {

    private HomeController $homeController;
    private SalleService $salleService;
    private PDO $pdo;
    private PDOStatement $pdoStatement;

    public function setUp(): void {
        
        parent::setUp();
        $this->salleService = $this->createMock(SalleService::class);
        $this->pdo = $this->createMock(PDO::class);
        $this->pdoStatement = $this->createMock(PDOStatement::class);
        
        $this->homeController = new HomeController($this->salleService);
    }
    
    /**
     * @covers
     */
    public function composantsExistants(): void {
        self::assertNotNull($this->salleService);
        self::assertNotNull($this->homeController);
    }

    /**
     * @covers
     */
    public function testIndex(): void {

        // On simule le retour de listeSalles()
        $donneesMock = [
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
            ]
        ];
        
        $this->salleService->method('listeSalles')->willReturn($donneesMock);
        
        $view = $this->homeController->index($this->pdo);
        self::assertEquals("views/affichageSalle", $view->getRelativePath());
        
        // Variables de la vue
        self::assertSame($donneesMock, $view->getVar("listeSalle"));
        self::assertSame(["Salle Azur"], $view->getVar("tabNoms"));
        self::assertSame([10], $view->getVar("tabCapacite"));
        self::assertSame(["Colleco Vision"], $view->getVar("tabOrdinateur"));
        self::assertSame(["notepad++"], $view->getVar("tabLogiciels"));
    }
}
