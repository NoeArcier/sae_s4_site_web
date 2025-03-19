<?php
namespace controllers;

use PDO;
use services\SalleService;
use yasmf\HttpHelper;
use yasmf\View;

/**
 * Default controller
 */
class HomeController {

    private SalleService $salleService;

    /**
     * Create a new default controller
     */
    public function __construct(SalleService $salleService)
    {
        $this->salleService = $salleService;
    }

    /**
     * Default action
     *
     * @param PDO $pdo  the PDO object to connect to the database
     * @return View the default view displaying all users
     */
    public function index(PDO $pdo): View {
        // $status_id = (int)HttpHelper::getParam('status_id') ?: 2 ;
        // $start_letter = htmlspecialchars(HttpHelper::getParam('start_letter').'%') ?: '%';
        // $search_stmt = $this->salleService->findUsersByUsernameAndStatus($pdo, $start_letter, $status_id) ;
        // $view = new View("views/all_users");
        // $view->setVar('search_stmt',$search_stmt);
        // return $view;
    }

}


