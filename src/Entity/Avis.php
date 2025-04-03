<?php
declare (strict_types = 1);
namespace MyApp\Entity;
use MyApp\Entity\Produit;
use MyApp\Entity\User;

class Avis
{
    private ?int $id = null;
    private string $commentaire;
    private int $note;
    private User $user;
    private Produit $produit;
    public function __construct(?int $id, string $commentaire, float $note, User $user, Produit $produit)
    {
        $this->id = $id;
        $this->commentaire = $commentaire;
        $this->note = $note;
        $this->user = $user;
        $this->produit = $produit;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getCommentaire(): string
    {
        return $this->commentaire;
    }

    public function setCommentaire(string $commentaire): void
    {
        $this->commentaire = $commentaire;
    }

    public function getNote(): float
    {
        return $this->note;
    }

    public function setNote(float $note): void
    {
        $this->note = $note;
    }

    public function getIdProduit(): Produit
    {
    return $this->produit;
    }
    public function setIdProduit(Produit $produit): void
    {
    $this->produit = $produit;
    }
 
    public function getIdUser(): User
    {
    return $this->user;
    }
    public function setIdUser(User $user): void
    {
    $this->user = $user;
    }

    
    
}
