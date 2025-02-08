<?php
declare (strict_types = 1);
namespace MyApp\Entity;

class EconomicZone
{
    private ?int $id = null;
    private string $nom;
    public function __construct(?int $id, string $nom)
    {
        $this->id = $id;
        $this->nom = $nom;
    }
    public function getId(): ?int
    {
        return $this->id;
    }
    public function setId(?int $id): void
    {
        $this->id = $id;
    }
    public function getNom(): string
    {
        return $this->nom;
    }
    public function setNom(string $nom): void
    {
        $this->nom = $nom;
    }
}
