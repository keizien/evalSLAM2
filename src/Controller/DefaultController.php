<?php
declare (strict_types = 1);
namespace MyApp\Controller;

use MyApp\Entity\Currency;
use MyApp\Entity\Produit;
use MyApp\Entity\Type;
use MyApp\Model\CurrencyModel;
use MyApp\Model\ProduitModel;
use MyApp\Model\TypeModel;
use MyApp\Model\UserModel;
use MyApp\Service\DependencyContainer;
use Twig\Environment;

class DefaultController
{
    private $twig;
    private $typeModel;
    private $produitModel;
    private $userModel;
    private $currencyModel;

    public function __construct(Environment $twig, DependencyContainer $dependencyContainer)
    {
        $this->twig = $twig;
        $this->typeModel = $dependencyContainer->get('TypeModel');
        $this->produitModel = $dependencyContainer->get('ProduitModel');
        $this->userModel = $dependencyContainer->get('UserModel');
        $this->currencyModel = $dependencyContainer->get('CurrencyModel');
    }

    public function types()
    {
        $types = $this->typeModel->getAllTypes();
        echo $this->twig->render('defaultController/types.html.twig', ['types' => $types]);
    }

    public function home()
    {
        echo $this->twig->render('defaultController/home.html.twig', []);
    }

    public function error404()
    {
        echo $this->twig->render('defaultController/error404.html.twig', []);
    }

    public function error500()
    {
        echo $this->twig->render('defaultController/error500.html.twig', []);
    }

    public function contact()
    {
        echo $this->twig->render('defaultController/contact.html.twig', []);
    }

    public function mentionsLegales()
    {
        echo $this->twig->render('defaultController/mentionsLegales.html.twig', []);
    }

    public function products()
    {
        $products = $this->produitModel->getAllProducts();
        echo $this->twig->render('defaultController/products.html.twig', ['products' => $products]);
    }

    public function users()
    {
        $users = $this->userModel->getAllUsers();
        echo $this->twig->render('defaultController/users.html.twig', ['users' => $users]);
    }

    public function currency()
    {
        $currency = $this->currencyModel->getAllCurrency();
        echo $this->twig->render('defaultController/currency.html.twig', ['currency' => $currency]);
    }

    public function updateType()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
            $label = filter_input(INPUT_POST, 'label', FILTER_SANITIZE_STRING);
            if (!empty($_POST['label'])) {
                $type = new Type(intVal($id), $label);
                $success = $this->typeModel->updateType($type);
                if ($success) {
                    header('Location: index.php?page=types');
                }
            }
        } else {
            $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        }
        $type = $this->typeModel->getOneType(intVal($id));
        echo $this->twig->render('defaultController/updateType.html.twig', ['type' => $type]);
    }

    public function updateProducts()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
            $nom = filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_STRING);
            $prix = filter_input(INPUT_POST, 'prix', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            if (!empty($_POST['nom'])&($_POST['prix'])) {
                $products = new Produit(intVal($id), $nom, floatVal($prix));
                $success = $this->produitModel->updateProducts($products);
                if ($success) {
                    header('Location: index.php?page=products');
                }
            }
        } else {
            $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        }
        $products = $this->produitModel->getOneProduct(intVal($id));
        echo $this->twig->render('defaultController/updateProducts.html.twig', ['products' => $products]);
    }

    public function addType()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $label = filter_input(INPUT_POST, 'label', FILTER_SANITIZE_STRING);
            if (!empty($_POST['label'])) {
                $type = new Type(null, $label);
                $success = $this->typeModel->createType($type);
                if ($success) {
                    header('Location: index.php?page=addType');
                }
            }
        }
        echo $this->twig->render('defaultController/addType.html.twig', []);
    }

    public function addProducts()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_STRING);
            $prix = filter_input(INPUT_POST, 'prix', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            if (!empty($_POST['nom'])&($_POST['prix'])) {
                $products = new Produit(null, $nom, floatVal($prix));
                $success = $this->produitModel->createProducts($products);
                if ($success) {
                    header('Location: index.php?page=addProducts');
                }
            }
        }
        echo $this->twig->render('defaultController/addProducts.html.twig', []);
    }

    public function deleteType()
    {
        $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        $this->typeModel->deleteType(intVal($id));
        header('Location: index.php?page=types');
    }

    public function deleteProducts()
    {
        $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        $this->produitModel->deleteProducts(intVal($id));
        header('Location: index.php?page=products');
    }


    public function updateCurrency()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
            $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
            if (!empty($_POST['name'])) {
                $currency = new Currency(intVal($id), $name);
                $success = $this->currencyModel->updateCurrency($currency);
                if ($success) {
                    header('Location: index.php?page=currency');
                }
            }
        } else {
            $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        }
        $currency = $this->currencyModel->getOneCurrency(intVal($id));
        echo $this->twig->render('defaultController/updateCurrency.html.twig', ['currency' => $currency]);
    }

    public function addCurrency()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
            if (!empty($_POST['name'])) {
                $currency = new Currency(null, $name);
                $success = $this->currencyModel->createCurrency($currency);
                if ($success) {
                    header('Location: index.php?page=currency');
                }
            }
        }
        echo $this->twig->render('defaultController/addCurrency.html.twig', []);
    }

    public function deleteCurrency()
    {
        $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        $this->currencyModel->deleteCurrency(intVal($id));
        header('Location: index.php?page=currency');
    }

}
