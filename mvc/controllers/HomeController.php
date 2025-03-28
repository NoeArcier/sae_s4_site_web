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

        $startTime = microtime(true); // temps de chargement de la page

        // STUB remplace connexion, 
        session_start();
        $_SESSION['typeUtilisateur'] = 1;
        $_SESSION['login'] = 'STUB';

        // chargement vue
        $view = new View("views/affichageSalle");

        // supprime salle si demandé
        $this->supprimer($view, $pdo);

        // récupération infos salles
        $listeSalle = $this->salleService->listeSalles($pdo);
        $tabNoms = array_unique(array_column($listeSalle, 'nom'));
        $tabCapacite = array_unique(array_column($listeSalle, 'capacite'));
        $tabOrdinateur = array_unique(array_column($listeSalle, 'ordinateur'));
        $tabLogiciels = array_unique(array_column($listeSalle, 'logiciels'));
        
        // tri infos salles
        asort($tabNoms);
        asort($tabCapacite);
        asort($tabOrdinateur);
        asort($tabLogiciels);

        // ajout listes vue
        $view->setVar('listeSalle',$listeSalle);
        $view->setVar('tabNoms',$tabNoms);
        $view->setVar('tabCapacite',$tabCapacite);
        $view->setVar('tabOrdinateur',$tabOrdinateur);
        $view->setVar('tabLogiciels',$tabLogiciels);

        // nom page et chemin aide
        $nomPage = basename($_SERVER['PHP_SELF'], '.php');
        // Déterminer si la page actuelle commence par "aide"
        $estPageAide = isset($nomPage) && str_starts_with($nomPage, 'aide');
        $baseChemin = $estPageAide ? '../pages/' : ''; // Chemin de base en fonction du type de page
        $view->setVar('nomPage',$nomPage);
        $view->setVar('baseChemin',$baseChemin);

        $endTime = microtime(true);
        $executionTime = round(($endTime - $startTime) * 1000, 2); // Temps en millisecondes
        $view->setVar('executionTime',$executionTime);
        return $view;
    }

    /**
     * vérifie si une suppression est demandée,
     * supprime la salle demandée si nécessaire
     * @view vue à laquelle on transmet les messages d'erreur et de succès
     */
    private function supprimer($view, $pdo) {
        $messageSucces = $messageErreur ='';
        $supprimer = isset($_POST['supprimer']) ? $_POST['supprimer'] : 'false';
        $id_salle = $_POST['idSalle'] ?? null;

        // si suppression demandée
        if ($supprimer == "true" && $id_salle) {

            try {
                $succes = $this->salleService->supprimerSalle($pdo, $id_salle);

                if ($succes) {
                    $messageSucces = 'Salle supprimée avec succès !';
                } else {
                    $messageErreur = '<span class="fa-solid fa-arrow-right erreur"></span>
                                      <span class="erreur">Impossible de supprimer cette salle. 
                                      Des réservations y sont associées.</span>
                                      <a href="affichageReservation.php" title="Page réservation">Cliquez ici</a>';
                }
                
            } catch (PDOException $e) {
                $messageSucces = '<span class="fa-solid fa-arrow-right erreur"></span>
                              <span class="erreur">Une erreur est survenue : ' . htmlspecialchars($e->getMessage()) . '</span>';
            }
        }

        $view->setVar('messageSucces',$messageSucces);
        $view->setVar('messageErreur',$messageErreur);
    }

}

?>