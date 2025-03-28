<?php
namespace controllers;

use PDO;
use services\Verification;
use services\SalleService;
use yasmf\HttpHelper;
use yasmf\View;

/**
 * Default controller
 */
class CreationController {

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
    public function creation(PDO $pdo): View {

        $startTime = microtime(true); // temps de chargement de la page

        // STUB remplace connexion, 
        session_start();
        $_SESSION['typeUtilisateur'] = 1;
        $_SESSION['login'] = 'STUB';

        // chargement vue
        $view = new View("views/creationSalle");

        $this->ajout($pdo, $view);

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

    private function ajout($pdo, $view) {
        
        // Vérification des variables issues du formulaire
        $nomSalle =         isset($_POST['nomSalle'])        ? htmlspecialchars($_POST['nomSalle']) : '';
        $capacite =         isset($_POST['capacite'])        ? htmlspecialchars($_POST['capacite']) : '';
        $videoProjecteur =  isset($_POST['videoProjecteur']) ? htmlspecialchars($_POST['videoProjecteur']) : '';
        $ecranXXL =         isset($_POST['ecranXXL'])        ? htmlspecialchars($_POST['ecranXXL']) : '';
        $nbrOrdi =          isset($_POST['nbrOrdi'])         ? htmlspecialchars($_POST['nbrOrdi']) : '';
        $typeMateriel =     isset($_POST['typeMateriel'])    ? htmlspecialchars($_POST['typeMateriel']) : '';
        $logiciel =         isset($_POST['logiciel'])        ? htmlspecialchars($_POST['logiciel']) : '';
        $imprimante =       isset($_POST['imprimante'])      ? htmlspecialchars($_POST['imprimante']) : '';

        $erreurs = Verification::verifierInfosSalles($nomSalle, (int)$capacite, $videoProjecteur, $ecranXXL);

        $messageSucces = $messageErreur = "";

        // Vérification que l'id est bien unique dans la base de données
        if (Verification::verifNomSalle($pdo, $nomSalle)) {
            $messageErreur = "Erreur : Ce nom de salle existe déjà. Veuillez en choisir un autre.";
            $nomSalle = '';
        }

        if (!isset($erreurs['nomSalle']) && !isset($erreurs['capacite'])
            && !isset($erreurs['videoProjecteur'])
            && !isset($erreurs['ecranXXL']) && $messageErreur == ""){

            try {
                $this->salleService->ajoutSalle($pdo, $nomSalle, (int) $capacite,
                                                $videoProjecteur == "oui" ? 1 : 0,
                                                $ecranXXL == "oui" ? 1 : 0,
                                                $nbrOrdi, $typeMateriel,
                                                $logiciel, $imprimante);
                                                
                $messageSucces = "Salle ajoutée avec succès !";
            } catch (PDOException $e) {
                $messageErreur = "Une erreur est survenue lors de la création de la salle.";
            }
        }

        $view->setVar('nomSalle', $nomSalle);
        $view->setVar('capacite', $capacite);
        $view->setVar('videoProjecteur', $videoProjecteur);
        $view->setVar('ecranXXL', $ecranXXL);
        $view->setVar('nbrOrdi', $nbrOrdi);
        $view->setVar('typeMateriel', $typeMateriel);
        $view->setVar('logiciel', $logiciel);
        $view->setVar('imprimante', $imprimante);
        $view->setVar('erreurs', $erreurs);
        $view->setVar('messageSucces', $messageSucces);
        $view->setVar('messageErreur', $messageErreur);

    }

}

?>