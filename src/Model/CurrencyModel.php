<?php
declare (strict_types = 1);
namespace MyApp\Model;

use MyApp\Entity\Currency;
use MyApp\Entity\EconomicZone;
use PDO;

class CurrencyModel
{
    private PDO $db;
    public function __construct(PDO $db)
    {
        $this->db = $db;
    }
    public function getAllCurrency(): array
    {
        $sql = "SELECT c.id as idCurrency, name, z.id_zone as idZone, nom
        FROM Currency c inner join Economic_zone z on c.idZone = z.id_zone order by name";
        $stmt = $this->db->query($sql);
        $currency = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $zone = new EconomicZone($row['idZone'], $row['nom']);
            $currency[] = new Currency($row['idCurrency'], $row['name'], $zone);
        }
        return $currency;
    }

    public function getOneCurrency(int $id): ?Currency
    {
        $sql = "SELECT c.id as idCurrency, name, z.id_zone as idZone, nom
        FROM Currency c inner join Economic_zone z on c.idZone = z.id_zone where c.id = :id";     
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":id", $id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }
        $zone = new EconomicZone($row['idZone'], $row['nom']);
        return new Currency($row['idCurrency'], $row['name'], $zone);
    }

    public function updateCurrency(Currency $currency): bool
    {
        $sql = "UPDATE Currency SET name = :name, idZone = :idZone WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':name', $currency->getName(), PDO::PARAM_STR);
        $stmt->bindValue(':idZone', $currency->getEconomicZone()->getId(), PDO::PARAM_INT);
        $stmt->bindValue(':id', $currency->getId(), PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function createCurrency(Currency $currency): bool
    {
        $sql = "INSERT INTO Currency (name, idZone) VALUES (:name, :idZone)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':name', $currency->getName(), PDO::PARAM_STR);
        $stmt->bindValue(':idZone', $currency->getEconomicZone()->getId(), PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function deleteCurrency(int $id): bool
    {
        $sql = "DELETE FROM Currency WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
