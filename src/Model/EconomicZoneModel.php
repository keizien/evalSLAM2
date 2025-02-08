<?php
declare (strict_types = 1);
namespace MyApp\Model;

use MyApp\Entity\EconomicZone;
use PDO;

class EconomicZoneModel
{
    private PDO $db;
    public function __construct(PDO $db)
    {
        $this->db = $db;
    }
    public function getAllEconomicZones(): array
    {
        $sql = "SELECT * FROM Economic_zone";
        $stmt = $this->db->query($sql);
        $zones = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $zones[] = new EconomicZone($row['id_zone'], $row['nom']);
        }
        return $zones;
    }

    public function getOneEconomicZone(int $id): ?EconomicZone
    {
        $sql = "SELECT * from Economic_zone where id_zone = :id_zone";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":id_zone", $id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }
        return new EconomicZone($row['id_zone'], $row['nom']);
    }

    public function updateEconomicZone(EconomicZone $zone): bool
    {
        $sql = "UPDATE Economic_zone SET nom = :nom WHERE id_zone = :id_zone";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':nom', $zone->getNom(), PDO::PARAM_STR);
        $stmt->bindValue(':id_zone', $zone->getId(), PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function createEconomicZone(EconomicZone $zone): bool
    {
        $sql = "INSERT INTO Economic_zone (nom) VALUES (:nom)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':nom', $zone->getNom(), PDO::PARAM_STR);
        return $stmt->execute();
    }

    public function deleteEconomicZone(int $id): bool
    {
        $sql = "DELETE FROM Economic_zone WHERE id_zone = :id_zone";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id_zone', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
