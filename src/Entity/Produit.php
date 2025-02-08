<?php
declare (strict_types = 1);
namespace MyApp\Entity;
use MyApp\Entity\Type;


class Produit
{
    private ?int $id = null;
    private string $nom;
    private float $prix;
    private string $description;
    private int $stock;
    private Type $type;
    public function __construct(?int $id, string $nom, float $prix, string $description, int $stock, Type $type)
    {
        $this->id = $id;
        $this->nom = $nom;
        $this->prix = $prix;
        $this->description = $description;
        $this->stock = $stock;
        $this->type = $type;
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

    public function getPrix(): float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): void
    {
        $this->prix = $prix;
    }

    public function getDescription(): string
    {
        return $this->description;
    }
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getStock(): int
    {
        return $this->stock;
    }
    public function setStock(int $stock): void
    {
        $this->stock = $stock;
    }
    
    public function getType(): Type
    {
    return $this->type;
    }
    public function setType(Type $type): void
    {
    $this->type = $type;
    }
    
}
