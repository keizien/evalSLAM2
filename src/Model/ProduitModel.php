<?php
declare (strict_types = 1);
namespace MyApp\Model;

use MyApp\Entity\Produit;
use MyApp\Entity\Type;
use PDO;

class ProduitModel
{
    private PDO $db;
    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function getAllProducts(): array
    {
        $sql = "SELECT p.id as idProduit, nom, prix, description, stock, t.id as idType, label
        FROM Produit p inner join Type t on p.idType = t.id order by nom";        
        $stmt = $this->db->query($sql);
        $products = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $type = new Type($row['idType'], $row['label']);
            $products[] = new Produit($row['idProduit'], $row['nom'], 
                $row['prix'], $row['description'], $row['stock'], $type);
        }
        return $products;
    }

    public function getOneProduct(int $id): ?Produit
    {
        $sql = "SELECT p.id as idProduit, nom, prix, description, stock, t.id as idType, label
        FROM Produit p inner join Type t on p.idType = t.id where p.id = :id";        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":id", $id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }
        $type = new Type($row['idType'], $row['label']);
        return new Produit($row['idProduit'], $row['nom'], $row['prix'], $row['description'], $row['stock'], $type);
    }

    public function updateProducts(Produit $products): bool
    {
        $sql = "UPDATE Produit SET nom = :nom, prix = :prix, description = :description, 
        stock = :stock, idType = :idType WHERE id = :id";        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':nom', $products->getNom(), PDO::PARAM_STR);
        $stmt->bindValue(':prix', $products->getPrix(), PDO::PARAM_STR);
        $stmt->bindValue(':description', $products->getDescription(), PDO::PARAM_STR);
        $stmt->bindValue(':stock', $products->getStock(), PDO::PARAM_INT);
        $stmt->bindValue(':idType', $products->getType()->getId(), PDO::PARAM_INT);
        $stmt->bindValue(':id', $products->getId(), PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function createProducts(Produit $products): bool
    {
        $sql = "INSERT INTO Produit (nom, prix, description, stock, idType) VALUES (:nom, :prix, :description, :stock, :idType)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':nom', $products->getNom(), PDO::PARAM_STR);
        $stmt->bindValue(':prix', $products->getPrix(), PDO::PARAM_STR);
        $stmt->bindValue(':description', $products->getDescription(), PDO::PARAM_STR);
        $stmt->bindValue(':stock', $products->getStock(), PDO::PARAM_INT);
        $stmt->bindValue(':idType', $products->getType()->getId(), PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function deleteProducts(int $id): bool
    {
        $sql = "DELETE FROM Produit WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
