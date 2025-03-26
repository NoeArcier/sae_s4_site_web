<?php

namespace application;

use controllers\HomeController;
use controllers\CreationController;
use services\SalleService;
use yasmf\ComponentFactory;
use yasmf\NoControllerAvailableForNameException;
use yasmf\NoServiceAvailableForNameException;

/**
 *  The controller factory
 */
class DefaultComponentFactory implements ComponentFactory
{
    private ?SalleService $salleService = null;

    /**
     * @param string $controller_name the name of the controller to instanciate
     * @return mixed the controller
     * @throws NoControllerAvailableForNameException when controller is not found
     */
    public function buildControllerByName(string $controller_name): mixed {
        return match ($controller_name) {
            "Home" => $this->buildHomeController(),
            "Creation" => $this->buildCreationController(),
            default => throw new NoControllerAvailableForNameException($controller_name)
        };
    }

    /**
     * @param string $service_name the name of the service
     * @return mixed the created service
     * @throws NoServiceAvailableForNameException when service is not found
     */
    public function buildServiceByName(string $service_name): mixed
    {
        return match($service_name) {
            "Salle" => $this->buildSalleService(),
            default => throw new NoServiceAvailableForNameException($service_name)
        };
    }

    /**
     * @return SalleService
     */
    private function buildSalleService(): SalleService
    {
        if ($this->salleService == null) {
            $this->salleService = new SalleService();
        }
        return $this->salleService;
    }

    /**
     * @return HomeController
     */
    private function buildHomeController(): HomeController
    {
        return new HomeController($this->buildSalleService());
    }

    /**
     * @return CreationController
     */
    private function buildCreationController(): CreationController
    {
        return new CreationController($this->buildSalleService());
    }
}

?>