<?php
declare (strict_types = 1);
namespace MyApp\Model;

use MyApp\Entity\Avis;
use MyApp\Entity\Produit;
use MyApp\Entity\User;
use PDO;

class AvisModel
{
    private PDO $db;
    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function getAllAvis(): array
    {
        $sql = "SELECT a.id as idAvis, commentaire, note, p.id as idProduit, nom, prix, description, stock, idType, image
        FROM Avis a inner join Produit p on a.idProduit = p.id order by note";
        $sql = "SELECT a.id as idAvis, commentaire, note, u.id as idUser, nom, prenom, date_de_naissance, rue, ville, code_postal, telephone, email, password, roles
        FROM Avis a inner join User u on a.idUser = u.id order by note";         
        $stmt = $this->db->query($sql);
        $avis = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $users = new User($row['idUser'], $row['nom'], $row['prenom'], $row['date_de_naissance'], $row['rue'], $row['ville'], $row['code_postal'], $row['telephone'],
            $row['email'], $row['password'], json_decode($row['roles']));
            $products[] = new Produit($row['idProduit'], $row['nom'], 
                $row['prix'], $row['description'], $row['stock'], $type, $row['image']);
        }
        return $avis;
    }

    public function getOneAvis(int $id): ?Avis
    {
        $sql = "SELECT a.id as idAvis, commentaire, note, p.id as idProduit, nom, prix, description, stock, idType, image
        FROM Avis a inner join Produit p on a.idProduit = p.id order by note";
        $sql = "SELECT a.id as idAvis, commentaire, note, u.id as idUser, nom, prenom, date_de_naissance, rue, ville, code_postal, telephone, email, password, roles
        FROM Avis a inner join User u on a.idUser = u.id order by note";       
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":id", $id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }
        $users = new User($row['idUser'], $row['nom'], $row['prenom'], $row['date_de_naissance'], $row['rue'], $row['ville'], $row['code_postal'], $row['telephone'],
            $row['email'], $row['password'], json_decode($row['roles']));
        $products[] = new Produit($row['idProduit'], $row['nom'], 
            $row['prix'], $row['description'], $row['stock'], $type, $row['image']);
        return new Avis($row['id'], $row['commentaire'], $row['note'], $user, $product);
    }

    public function createAvis(Avis $avis): bool
    {
        $sql = "INSERT INTO Avis (commentaire, note, idUser, idProduit) VALUES (:commentaire, :note, :idUser, :idProduit)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':commentaire', $avis->getCommentaire(), PDO::PARAM_STR);
        $stmt->bindValue(':note', $avis->getNote(), PDO::PARAM_INT);
        $stmt->bindValue(':idUser', $products->getIdUser()->getId(), PDO::PARAM_INT);
        $stmt->bindValue(':idProduit', $products->getIdProduit()->getId(), PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function deleteAvis(int $id): bool
    {
        $sql = "DELETE FROM Avis WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}