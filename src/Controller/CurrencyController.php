<?php
declare (strict_types = 1);
namespace MyApp\Controller;

use MyApp\Entity\Currency;
use MyApp\Model\CurrencyModel;
use MyApp\Model\EconomicZoneModel;
use MyApp\Service\DependencyContainer;
use Twig\Environment;

class CurrencyController
{
    private $twig;
    private CurrencyModel $currencyModel;
    private EconomicZoneModel $economicZoneModel;
    public function __construct(Environment $twig, DependencyContainer $dependencyContainer)
    {
        $this->twig = $twig;
        $this->currencyModel = $dependencyContainer->get('CurrencyModel');
        $this->economicZoneModel = $dependencyContainer->get('EconomicZoneModel');
    }
    public function listCurrency()
    {
        $currency = $this->currencyModel->getAllCurrency();
        echo $this->twig->render('currencyController/listCurrency.html.twig',
            ['currency' => $currency]);
    }

    public function currency()
    {
        $currency = $this->currencyModel->getAllCurrency();
        echo $this->twig->render('currencyController/currency.html.twig', ['currency' => $currency]);
    }

    public function addCurrency()
    {
        $zones = $this->economicZoneModel->getAllEconomicZones();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
            $idZone = filter_input(INPUT_POST, 'idZone',
                FILTER_SANITIZE_NUMBER_INT);
            if (!empty($name) && !empty($idZone)) {
                $zone = $this->economicZoneModel->getOneEconomicZone(intVal($idZone));
                if ($zone == null) {
                    $_SESSION['message'] = 'Erreur sur la zone économique.';
                } else {
                    $currency = new Currency(null, $name, $zone);
                    $success = $this->currencyModel->createCurrency($currency);
                }
            } else {
                $_SESSION['message'] = 'Veuillez saisir toutes les données.';
            }
        }
        echo $this->twig->render('currencyController/addCurrency.html.twig', ['zones' => $zones]);
    }

    public function updateCurrency()
    {
        $zones = $this->economicZoneModel->getAllEconomicZones();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
            $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
            $idZone = filter_input(INPUT_POST, 'idZone',
                FILTER_SANITIZE_NUMBER_INT);
            if (!empty($id) && !empty($name) && !empty($idZone)) {
                $currency = $this->currencyModel->getOneCurrency(intVal($id));
                if ($currency == null) {
                    $_SESSION['message'] = 'Erreur sur la monnaie.';
                } else {
                    $zone = $this->economicZoneModel->getOneEconomicZone(intVal($idZone));
                    if ($zone == null) {
                        $_SESSION['message'] = 'Erreur sur la zone économique.';
                    } else {
                        $currency = new Currency(intVal($id), $name, $zone);
                        $success = $this->currencyModel->updateCurrency($currency);
                        if ($success) {
                            header('Location: index.php?page=listCurrency');
                        } else {
                            $_SESSION['message'] = 'Erreur sur la modification.';
                            header('Location: index.php?page=listCurrency');
                        }
                    }
                }
            } else {
                $_SESSION['message'] = 'Veuillez saisir toutes les données.';
            }
        } else {
            $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
            $currency = $this->currencyModel->getOneCurrency(intVal($id));
            if ($currency == null) {
                $_SESSION['message'] = 'Erreur sur le produit.';
                header('Location: index.php?page=listCurrency');
            }
        }
        echo $this->twig->render('currencyController/updateCurrency.html.twig',
            ['currency' => $currency, 'zones' => $zones]);
    }
}
