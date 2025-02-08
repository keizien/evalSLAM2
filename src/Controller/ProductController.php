<?php
declare (strict_types = 1);
namespace MyApp\Controller;

use MyApp\Entity\Produit;
use MyApp\Model\ProduitModel;
use MyApp\Model\TypeModel;
use MyApp\Service\DependencyContainer;
use Twig\Environment;

class ProductController
{
    private $twig;
    private ProduitModel $produitModel;
    private TypeModel $typeModel;
    public function __construct(Environment $twig, DependencyContainer
         $dependencyContainer) {
        $this->twig = $twig;
        $this->produitModel = $dependencyContainer->get('ProduitModel');
        $this->typeModel = $dependencyContainer->get('TypeModel');
    }
    public function listProducts()
    {
        $products = $this->produitModel->getAllProducts();
        echo $this->twig->render('productController/listProducts.html.twig',
            ['products' => $products]);
    }

    public function produits(){
        $products = $this->produitModel->getAllProducts();
        echo $this->twig->render('productController/produits.html.twig', ['products' => $products]);        }


    public function addProducts()
    {
        $types = $this->typeModel->getAllTypes();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_STRING);
            $prix = filter_input(INPUT_POST, 'prix',
                FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $description = filter_input(INPUT_POST, 'description',
                FILTER_SANITIZE_STRING);
            $stock = filter_input(INPUT_POST, 'stock', FILTER_SANITIZE_NUMBER_INT);
            $idType = filter_input(INPUT_POST, 'idType',
                FILTER_SANITIZE_NUMBER_INT);
            if (!empty($nom) && !empty($prix) && !empty($description) && !empty($stock)
                && !empty($idType)) {
                $type = $this->typeModel->getOneType(intVal($idType));
                if ($type == null) {
                    $_SESSION['message'] = 'Erreur sur le type.';
                } else {
                    $product = new Produit(null, $nom, floatVal($prix), $description,
                        intVal($stock), $type);
                    $success = $this->produitModel->createProducts($product);
                }
            } else {
                $_SESSION['message'] = 'Veuillez saisir toutes les données.';
            }
        }
        echo $this->twig->render('productController/addProducts.html.twig',
            ['types' => $types]);
    }

    public function updateProducts()
    {
        $types = $this->typeModel->getAllTypes();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
            $nom = filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_STRING);
            $prix = filter_input(INPUT_POST, 'prix',
                FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $description = filter_input(INPUT_POST, 'description',
                FILTER_SANITIZE_STRING);
            $stock = filter_input(INPUT_POST, 'stock', FILTER_SANITIZE_NUMBER_INT);
            $idType = filter_input(INPUT_POST, 'idType',
                FILTER_SANITIZE_NUMBER_INT);
            if (!empty($id) && !empty($nom) && !empty($prix) && !empty($description)
                && !empty($stock) && !empty($idType)) {
                $product = $this->produitModel->getOneProduct(intVal($id));
                if ($product == null) {
                    $_SESSION['message'] = 'Erreur sur le produit.';
                } else {
                    $type = $this->typeModel->getOneType(intVal($idType));
                    if ($type == null) {
                        $_SESSION['message'] = 'Erreur sur le type.';
                    } else {
                        $product = new Produit(intVal($id), $nom, floatVal($prix), $description,
                            intVal($stock), $type);
                        $success = $this->produitModel->updateProducts($product);
                        if ($success) {
                            header('Location: index.php?page=products');
                        } else {
                            $_SESSION['message'] = 'Erreur sur la modification.';
                            header('Location: index.php?page=products');
                        }
                    }
                }
            } else {
                $_SESSION['message'] = 'Veuillez saisir toutes les données.';
            }
        } else {
            $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
            $product = $this->produitModel->getOneProduct(intVal($id));
            if ($product == null) {
                $_SESSION['message'] = 'Erreur sur le produit.';
                header('Location: index.php?page=products');
            }
        }
        echo $this->twig->render('productController/updateProducts.html.twig',
            ['product' => $product, 'types' => $types]);
    }
}
