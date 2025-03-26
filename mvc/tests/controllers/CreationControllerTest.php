<?php

namespace controllers;

use PDO;
use PDOStatement;
use PHPUnit\Framework\TestCase;
use services\SalleService;
use services\Verification;

class CreationControllerTest extends TestCase {

    private CreationController $creationController;
    private SalleService $salleService;
    private PDO $pdo;
    private PDOStatement $pdoStatement;

    public function setUp(): void {
        
        parent::setUp();
        $this->salleService = $this->createMock(SalleService::class);
        $this->pdo = $this->createMock(PDO::class);
        $this->pdoStatement = $this->createMock(PDOStatement::class);
        
        $this->creationController = new CreationController($this->salleService);
    }
    
    public function composantsExistants(): void {
        self::assertNotNull($this->salleService);
        self::assertNotNull($this->creationController);
    }

    public function testCreation(): void {          
        // TODO
    }
}
