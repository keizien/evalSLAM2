<?php
declare (strict_types = 1);
namespace MyApp\Controller;

use MyApp\Entity\EconomicZone;
use MyApp\Entity\Type;
use MyApp\Entity\User;
use MyApp\Model\CurrencyModel;
use MyApp\Model\EconomicZoneModel;
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
    private $economicZoneModel;

    public function __construct(Environment $twig, DependencyContainer $dependencyContainer)
    {
        $this->twig = $twig;
        $this->typeModel = $dependencyContainer->get('TypeModel');
        $this->produitModel = $dependencyContainer->get('ProduitModel');
        $this->userModel = $dependencyContainer->get('UserModel');
        $this->currencyModel = $dependencyContainer->get('CurrencyModel');
        $this->economicZoneModel = $dependencyContainer->get('EconomicZoneModel');
    }

    public function types()
    {
        $types = $this->typeModel->getAllTypes();
        echo $this->twig->render('defaultController/types.html.twig', ['types' => $types]);
    }

    public function economicZone()
    {
        $zones = $this->economicZoneModel->getAllEconomicZones();
        echo $this->twig->render('defaultController/economicZone.html.twig', ['zones' => $zones]);
    }

    public function updateEconomicZone()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
            $nom = filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_STRING);
            if (!empty($_POST['nom'])) {
                $zone = new EconomicZone(intVal($id), $nom);
                $success = $this->economicZoneModel->updateEconomicZone($zone);
                if ($success) {
                    header('Location: index.php?page=economicZone');
                }
            }
        } else {
            $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        }
        $zone = $this->economicZoneModel->getOneEconomicZone(intVal($id));
        echo $this->twig->render('defaultController/updateEconomicZone.html.twig', ['zone' => $zone]);
    }

    public function addEconomicZone()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_STRING);
            if (!empty($_POST['nom'])) {
                $zone = new EconomicZone(null, $nom);
                $success = $this->economicZoneModel->createEconomicZone($zone);
                if ($success) {
                    header('Location: index.php?page=economicZone');
                }
            }
        }
        echo $this->twig->render('defaultController/addEconomicZone.html.twig', []);
    }

    public function deleteEconomicZone()
    {
        $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        $this->economicZoneModel->deleteEconomicZone(intVal($id));
        header('Location: index.php?page=economicZone');
    }

    public function home()
    {
        $products = $this->produitModel->getAllProducts();
        echo $this->twig->render('defaultController/home.html.twig', ['products' => $products]);
    }

    public function error404()
    {
        echo $this->twig->render('defaultController/error404.html.twig', []);
    }

    public function error403()
    {
        echo $this->twig->render('defaultController/error403.html.twig', []);
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

    public function users()
    {
        $users = $this->userModel->getAllUsers();
        echo $this->twig->render('defaultController/users.html.twig', ['users' => $users]);
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

    public function deleteCurrency()
    {
        $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        $this->currencyModel->deleteCurrency(intVal($id));
        header('Location: index.php?page=currency');
    }

    public function inscription()
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_STRING);
            $prenom = filter_input(INPUT_POST, 'prenom', FILTER_SANITIZE_STRING);
            $date_de_naissance = filter_input(INPUT_POST, 'date_de_naissance', FILTER_SANITIZE_STRING);
            $rue = filter_input(INPUT_POST, 'rue', FILTER_SANITIZE_STRING);
            $ville = filter_input(INPUT_POST, 'ville', FILTER_SANITIZE_STRING);
            $code_postal = filter_input(INPUT_POST, 'code_postal', FILTER_SANITIZE_STRING);
            $telephone = filter_input(INPUT_POST, 'telephone', FILTER_SANITIZE_STRING);
            $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
            $password = $_POST['password'];
            $confirmedpassword = $_POST['confirmedpassword'];

            $passwordLength = strlen($password);
            $containsDigit = preg_match('/\d/', $password);
            $containsUpper = preg_match('/[A-Z]/', $password);
            $containsLower = preg_match('/[a-z]/', $password);
            $containsSpecial = preg_match('/[^a-zA-Z\d]/', $password);

            if (!$nom || !$prenom || !$date_de_naissance || !$rue || !$ville || !$code_postal || !$telephone || !$email || !$password) {

                $_SESSION['message'] = 'Erreur : données invalides';
            } elseif ($passwordLength < 12 || !$containsDigit || !$containsUpper || !$containsLower || !
                $containsSpecial) {

                $_SESSION['message'] = 'Erreur : mot de passe non conforme';
            }
            elseif ($this->userModel->getUserByEmail($email) != null) {
                $_SESSION['message'] = 'Erreur : email déjà utilisée';
            } elseif ($password != $confirmedpassword) {
                $_SESSION['message'] = 'Erreur : mot de passe non conforme';
            }
            else {
                // Hachage du mot de passe
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $user = new User(null, $nom, $prenom, $date_de_naissance, $rue, $ville, $code_postal, $telephone, $email, $hashedPassword, ['user']);
                // Enregistrez les données de l'utilisateur dans la base de données
                $result = $this->userModel->createUser($user);
                if ($result) {
                    $_SESSION['message'] = 'Votre inscription est terminée';
                    header('Location: index.php?page=connexion');
                    exit;
                } else {
                    $_SESSION['message'] = 'Erreur lors de l\'inscription';
                }

            
            header('Location: index.php?page=inscription');
            exit;
            }
    
        }
        echo $this->twig->render('defaultController/inscription.html.twig', []);
    }



    public function connexion()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
            $password = $_POST['password'];
            $user = $this->userModel->getUserByEmail($email);
            if (!$user) {
                $_SESSION['message'] = 'Utilisateur ou mot de passe erroné';
                header('Location: index.php?page=connexion');
            } else {
                if ($user->verifyPassword($password)) {
                    $_SESSION['connexion'] = $user->getEmail(); 
                    $_SESSION['roles'] = $user->getRoles();
                    header('Location: index.php');
                    exit;
                } else {
                    $_SESSION['message'] = 'Utilisateur ou mot de passe erroné';
                    header('Location: index.php?page=connexion');
                    exit;
                }
            }
        }
        echo $this->twig->render('defaultController/connexion.html.twig', []);
    }

    public function deconnexion()
    {
        $_SESSION = array();
        session_destroy();
        header('Location: index.php');
        exit;
    }

    public function profil()
    {
        if (isset($_SESSION['connexion'])){ //si la clé login existe dans $_SESSION c'est que la personne est connectée
            $user = $this->userModel->getUserByEmail($_SESSION['connexion']);
        }
        echo $this->twig->render('defaultController/profil.html.twig', ['user'=>$user]);
    }

}
