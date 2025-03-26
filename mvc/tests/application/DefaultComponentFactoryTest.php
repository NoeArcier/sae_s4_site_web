<?php

namespace application;

use controllers\HomeController;
use controllers\CreationController;
use PHPUnit\Framework\TestCase;
use services\SalleService;
use services\Verification;
use yasmf\NoControllerAvailableForNameException;
use yasmf\NoServiceAvailableForNameException;

class DefaultComponentFactoryTest extends TestCase {

    private DefaultComponentFactory $componentFactory;

    public function setUp(): void {
        parent::setUp();
        $this->componentFactory = new DefaultComponentFactory();
    }

    public function testBuildControllerByNameHome() {
        $controller = $this->componentFactory->buildControllerByName("Home");
        self::assertInstanceOf(HomeController::class, $controller);
    }
    
    public function testBuildControllerByNameCreation() {
        $controller = $this->componentFactory->buildControllerByName("Creation");
        self::assertInstanceOf(CreationController::class ,$controller);
    }

    public function testBuildControllerByNameOther() {
        $this->expectException(NoControllerAvailableForNameException::class);
        $controller = $this->componentFactory->buildControllerByName("NoController");
    }

    public function testBuildServiceByNameSalle() {   
        $service = $this->componentFactory->buildServiceByName("Salle");
        // then the service is UsersService instance
        self::assertInstanceOf(SalleService::class,$service);
    }

    public function testBuildServiceByNameOther() {
        $this->expectException(NoServiceAvailableForNameException::class);
        $this->componentFactory->buildServiceByName("NoService");
    }
}
