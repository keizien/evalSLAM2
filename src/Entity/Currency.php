<?php
declare (strict_types = 1);
namespace MyApp\Entity;

use MyApp\Entity\EconomicZone;

class Currency
{
    private ?int $id = null;
    private string $name;
    private EconomicZone $zone;
    public function __construct(?int $id, string $name, EconomicZone $zone)
    {
        $this->id = $id;
        $this->name = $name;
        $this->zone = $zone;
    }
    public function getId(): ?int
    {
        return $this->id;
    }
    public function setId(?int $id): void
    {
        $this->id = $id;
    }
    public function getName(): string
    {
        return $this->name;
    }
    public function setName(string $name): void
    {
        $this->name = $name;
    }
    public function getEconomicZone(): EconomicZone
    {
        return $this->zone;
    }
    public function setEconomicZone(EconomicZone $zone): void
    {
        $this->zone = $zone;
    }
}
