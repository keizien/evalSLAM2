<?php
declare (strict_types = 1);
namespace MyApp\Controller;

use MyApp\Entity\Produit;
use MyApp\Model\ProduitModel;
use MyApp\Service\DependencyContainer;
use Twig\Environment;

class PanierController
{
    private ProduitModel $produitModel;
    private $twig;
    public function __construct(Environment $twig, DependencyContainer $dependencyContainer)
    {
        $this->produitModel = $dependencyContainer->get('ProduitModel');
        $this->twig = $twig;
    }
    
    public function ajoutPanier() 
    {
        $id = filter_input(INPUT_GET,'id', FILTER_SANITIZE_NUMBER_INT);
        if($id) {
            echo $id;
            if(isset($_SESSION['panier'])) {
                $panier = $_SESSION['panier']; 		
            }
            else {
                $panier = [];		
            }
            If(isset($panier[$id])) { 	
                $panier[$id] += 1;
            }
            else { 		
                $panier[$id] = 1;
            }
            $_SESSION['panier'] = $panier;
            var_dump($_SESSION); 
            header('Location: index.php?page=home');

        }
    }

    public function afficherPanier()
    {
        $contenu = [];
        if(isset($_SESSION['panier'])) {
            foreach($_SESSION['panier'] as $id => $qte) {
                $product = $this->produitModel->getOneProduct($id);
                $ligne['product'] = $product;
                $ligne['qte'] = $qte;
                $contenu[] = $ligne;
            }
        }
        echo $this->twig->render('panierController/afficherPanier.html.twig', ['contenu'=>$contenu]);
    }
}
