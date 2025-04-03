<?php
declare (strict_types = 1);
namespace MyApp\Controller;

use MyApp\Entity\Avis;
use MyApp\Entity\Produit;
use MyApp\Entity\User;
use MyApp\Model\AvisModel;
use MyApp\Model\ProduitModel;
use MyApp\Model\UserModel;
use MyApp\Service\DependencyContainer;
use Twig\Environment;

class AvisController
{
    private $twig;
    private AvisModel $avisModel;
    private ProduitModel $produitModel;
    private UserModel $userModel;
    public function __construct(Environment $twig, DependencyContainer $dependencyContainer) {
        $this->twig = $twig;
        $this->avisModel = $dependencyContainer->get('AvisModel');
        $this->produitModel = $dependencyContainer->get('ProduitModel');
        $this->userModel = $dependencyContainer->get('UserModel');
    }

    public function avisProduct()
    {
        echo $this->twig->render('productController/avisProduct.html.twig', []);       
    }

    public function addAvis()
    {
        $products = $this->produitModel->getAllProducts();
        $users = $this->userModel->getAllUsers();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $commentaire = filter_input(INPUT_POST, 'commentaire', FILTER_SANITIZE_STRING);
            $note = filter_input(INPUT_POST, 'note', FILTER_SANITIZE_NUMBER_INT);
            $idUser = filter_input(INPUT_POST, 'idUser', FILTER_SANITIZE_NUMBER_INT);
            $idProduit = filter_input(INPUT_POST, 'idProduit', FILTER_SANITIZE_NUMBER_INT);
            if (!empty($commentaire) && !empty($note) && !empty($idUser) && !empty($idProduit)) {
                    $avis = new Avis(null, $commentaire, intVal($note));
                $user = $this->userModel->getOneUser(intVal($idUser));
                $product = $this->productModel->getOneProduct(intVal($idProduit));
                if ($user == null && $product == null) {
                    $_SESSION['message'] = 'Erreur.';
                } else {
                    $avis = new Avis(null, $commentaire, intVal($note), $user, $product);
                    $success = $this->avisModel->createAvis($avis);
                }
            } else {
                $_SESSION['message'] = 'Veuillez saisir toutes les données.';
            }
        }
        echo $this->twig->render('avisController/addAvis.html.twig',
            ['users' => $users, 'products' => $products]);
    }

    public function showAvis()
    {
        $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        $users = $this->userModel->getAllUsers();
        $products = $this->produitModel->getAllProducts();
        if (empty($id)) {
            $_SESSION['message'] = 'Identifiant du commentaire manquant.';
            header('Location: index.php?page=home');
            exit();
        }
        $avis = $this->avisModel->getOneAvis(intVal($id));
        if ($avis == null) {
            $_SESSION['message'] = 'Commentaire introuvable.';
            header('Location: index.php?page=home');
            exit();
        }
        echo $this->twig->render('productController/showAvis.html.twig', ['avis' => $avis,'user'=> $user, 'product' => $product]);
    }

    public function listAvis()
    {
        $avis = $this->avisModel->getAllAvis();
        echo $this->twig->render('productController/listAviss.html.twig',
            ['avis' => $avis]);
    }

} 