<?php

declare (strict_types = 1);

namespace MyApp\Routing;

use MyApp\Controller\CurrencyController;
use MyApp\Controller\DefaultController;
use MyApp\Controller\PanierController;
use MyApp\Controller\ProductController;
use MyApp\Controller\AvisController;
use MyApp\Service\DependencyContainer;

class Router
{
    private $dependencyContainer;
    private $pageMappings;
    private $defaultPage;
    private $errorPage;

    public function __construct(DependencyContainer $dependencyContainer)
    {
        $this->dependencyContainer = $dependencyContainer;
        // Tableau contenant l'ensemble des pages (controller) de votre site
        // La clé est le mot qui sera récupéré dans la variable page de l'url
        // La valeur est un tableau composé de 2 colonnes
        // Colonne 1 : classe du contrôleur
        // Colonne 2 : nom de la méthode à appeler

        $this->pageMappings = [
            'home' => [DefaultController::class, 'home', []],
            '404' => [DefaultController::class, 'error404', []],
            '403' => [DefaultController::class, 'error403', []],
            'contact' => [DefaultController::class, 'contact', []],
            'mentionsLegales' => [DefaultController::class, 'mentionsLegales', []],
            '500' => [DefaultController::class, 'error500', []],
            'types' => [DefaultController::class, 'types', []],
            'produits' => [ProductController::class, 'produits', []],
            'products' => [ProductController::class, 'listProducts', []],
            'users' => [DefaultController::class, 'users', []],
            'updateType' => [DefaultController::class, 'updateType', ['admin']],
            'updateProducts' => [ProductController::class, 'updateProducts', ['admin']],
            'addType' => [DefaultController::class, 'addType', ['admin']],
            'addProducts' => [ProductController::class, 'addProducts', ['admin']],
            'deleteType' => [DefaultController::class, 'deleteType', ['admin']],
            'deleteProducts' => [DefaultController::class, 'deleteProducts', ['admin']],
            'currency' => [CurrencyController::class, 'currency', []],
            'updateCurrency' => [CurrencyController::class, 'updateCurrency', ['admin']],
            'addCurrency' => [CurrencyController::class, 'addCurrency', ['admin']],
            'deleteCurrency' => [DefaultController::class, 'deleteCurrency', ['admin']],
            'economicZone' => [DefaultController::class, 'economicZone', []],
            'updateEconomicZone' => [DefaultController::class, 'updateEconomicZone', ['admin']],
            'addEconomicZone' => [DefaultController::class, 'addEconomicZone', ['admin']],
            'deleteEconomicZone' => [DefaultController::class, 'deleteEconomicZone', ['admin']],
            'listCurrency' => [CurrencyController::class, 'listCurrency', []],
            'listProducts' => [ProductController::class, 'listProducts', []],
            'showProduct' => [ProductController::class, 'showProduct', []],
            'inscription' => [DefaultController::class, 'inscription', []],
            'connexion' => [DefaultController::class, 'connexion', []],
            'deconnexion' => [DefaultController::class, 'deconnexion', []],
            'profil' => [DefaultController::class, 'profil', []],
            'ajoutPanier' => [PanierController::class, 'ajoutPanier', []],
            'afficherPanier' => [PanierController::class, 'afficherPanier', []],
            'avisProduct' => [AvisController::class, 'avisProduct', []],
            'showAvis' => [AvisController::class, 'showAvis', []],
            'addAvis' => [AvisController::class, 'addAvis', []],
            'listAvis' => [AvisController::class, 'listAvis', []],
            'deleteAvis' => [AvisController::class, 'deleteAvis', ['admin']],
        ];
        $this->defaultPage = 'home';
        $this->errorPage = '404';
    }

    public function route($twig)
    {
        $requestedPage = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_STRING);

        // Si l'url ne contient pas la variable page, redirection vers la page d'accueil
        if (!$requestedPage) {
            $requestedPage = $this->defaultPage;
        } else {
            // Si la valeur de la page ne correspond pas à une clé du tableau associatif, redirection vers une page d'erreur
            if (!array_key_exists($requestedPage, $this->pageMappings)) {
                $requestedPage = $this->errorPage;
            }
        }

        // Récupère la ligne qui correspond à la clé comprise dans page
        $controllerInfo = $this->pageMappings[$requestedPage];
        /* Destructuration du tableau en mettant la première valeur du tableau de la ligne dans $controllerClass et la deuxième
        valeur dans $method */
        [$controllerClass, $method, $requiredRoles] = $controllerInfo;

        if ($this->checkUserPermissions($requiredRoles)) {
// Vérification de l'existence de la classe et de la méthode du contrôleur à appeler
            if (class_exists($controllerClass) && method_exists($controllerClass, $method)) {
// Instancie la classe récupérée
                $controller = new $controllerClass($twig, $this->dependencyContainer);
//la fonction call_user_func appelle une méthode sur un objet
                call_user_func([$controller, $method]);
            } else {
// Si la classe ou la méthode n'existe pas, utilisez le contrôleur d'erreur 500
                $this->handleError($twig, '500');
            }
        } else {
            $this->handleError($twig, '403');
        }
    }

    private function checkUserPermissions(array $requiredRoles): bool
    {
        if (!empty($requiredRoles)) {
            if (isset($_SESSION['roles'])) {
                $i = array_intersect($_SESSION['roles'], $requiredRoles);
                if (empty($i)) {
                    return false;
                } else {
                    return true;
                }
            } else {
                return false;
            }
        } else {
            return true;
        }
    }
    private function handleError($twig, $errorCode)
    {
        $errorInfo = $this->pageMappings[$errorCode];
        [$errorControllerClass, $errorMethod] = $errorInfo;
        $errorController = new $errorControllerClass($twig, $this->dependencyContainer);
        call_user_func([$errorController, $errorMethod]);
    }
}
