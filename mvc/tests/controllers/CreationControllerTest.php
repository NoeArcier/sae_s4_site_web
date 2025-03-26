<?php

namespace controllers;

use PDO;
use PDOStatement;
use PHPUnit\Framework\TestCase;
use services\SalleService;
use services\Verification;
use yasmf\View;

class CreationControllerTest extends TestCase {

    private CreationController $creationController;
    private SalleService $salleService;
    private PDO $pdo;
    private PDOStatement $pdoStatement;

    public function setUp(): void {
        
        parent::setUp();
        
        $this->salleService = new SalleService();
        $this->pdo = $this->createMock(PDO::class);
        $this->pdoStatement = $this->createMock(PDOStatement::class);
        
        $this->creationController = new CreationController($this->salleService);
    }

    /**
     * @covers
     */
     public function testCreationViewIsReturned() {

        $this->pdo->method('prepare')->willReturn($this->pdoStatement);

        $view = $this->creationController->creation($this->pdo);

        self::assertEquals("views/creationSalle", $view->getRelativePath());
        $this->assertInstanceOf(View::class, $view);
    }

    
}
