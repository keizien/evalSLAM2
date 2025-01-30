<?php
declare (strict_types = 1);
namespace MyApp\Model;

use MyApp\Entity\Produit;
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
        $sql = "SELECT * FROM Produit";
        $stmt = $this->db->query($sql);
        $products = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $products[] = new Produit($row['id'], $row['nom'], $row['prix']);
        }
        return $products;
    }

    public function getOneProduct(int $id): ?Produit
    {
        $sql = "SELECT * from Produit where id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":id", $id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }
        return new Produit($row['id'], $row['nom'], $row['prix']);
    }

    public function updateProducts(Produit $products): bool
    {
        $sql = "UPDATE Produit SET nom = :nom, prix = :prix WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':nom', $products->getNom(), PDO::PARAM_STR);
        $stmt->bindValue(':prix', $products->getPrix(), PDO::PARAM_STR);
        $stmt->bindValue(':id', $products->getId(), PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function createProducts(Produit $products): bool
    {
        $sql = "INSERT INTO Produit (nom, prix) VALUES (:nom, :prix)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':nom', $products->getNom(), PDO::PARAM_STR);
        $stmt->bindValue(':prix', $products->getPrix(), PDO::PARAM_STR);
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
