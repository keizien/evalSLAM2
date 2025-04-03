<?php
declare (strict_types = 1);
namespace MyApp\Model;

use MyApp\Entity\User;
use PDO;

class UserModel
{
    private PDO $db;
    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function createUser(User $user): bool
    {
        $sql = "INSERT INTO User (nom, prenom, date_de_naissance, rue, ville, code_postal, telephone, email, password, roles) VALUES
        (:nom, :prenom, :date_de_naissance, :rue, :ville, :code_postal, :telephone, :email, :password, :roles)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':nom', $user->getNom());
        $stmt->bindValue(':prenom', $user->getPrenom());
        $stmt->bindValue(':date_de_naissance', $user->getDateDeNaissance());
        $stmt->bindValue(':rue', $user->getRue());
        $stmt->bindValue(':ville', $user->getVille());
        $stmt->bindValue(':code_postal', $user->getCodePostal());
        $stmt->bindValue(':telephone', $user->getTelephone());
        $stmt->bindValue(':email', $user->getEmail());
        $stmt->bindValue(':password', $user->getPassword());
        $stmt->bindValue(':roles', json_encode($user->getRoles()));
        return $stmt->execute();
    }

    public function getUserByEmail(string $email): ?User
    {
        $sql = "SELECT * FROM User WHERE email = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':email', $email);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }
        return new User($row['id'], $row['nom'], $row['prenom'], $row['date_de_naissance'], $row['rue'], $row['ville'], $row['code_postal'], $row['telephone'],
            $row['email'], $row['password'], json_decode($row['roles']));
    }

    public function getUserById(int $id): ?User
    {
        $sql = "SELECT * FROM User WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }
        return new User($row['id'], $row['nom'], $row['prenom'], $row['date_de_naissance'], $row['rue'], $row['ville'], $row['code_postal'], $row['telephone'],
            $row['email'], $row['password'], json_decode($row['roles']));
    }

    public function updateUser(User $user): bool
    {
        $sql = "UPDATE User SET nom = :nom, prenom = :prenom, date_de_naissance = :date_de_naissance, rue = :rue, ville = :ville, code_postal = :code_postal, telephone = :telephone,
        email = :email, password = :password WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':nom', $user->getNom());
        $stmt->bindValue(':prenom', $user->getPrenom());
        $stmt->bindValue(':date_de_naissance', $user->getDateDeNaissance());
        $stmt->bindValue(':rue', $user->getRue());
        $stmt->bindValue(':ville', $user->getVille());
        $stmt->bindValue(':code_postal', $user->getCodePostal());
        $stmt->bindValue(':telephone', $user->getTelephone());
        $stmt->bindValue(':email', $user->getEmail());
        $stmt->bindValue(':password', $user->getPassword());
        $stmt->bindValue(':id', $user->getId());
        return $stmt->execute();
    }

    public function getAllUsers(): array
    {
        $sql = "SELECT * FROM User";
        $stmt = $this->db->query($sql);
        $users = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $users[] = new User($row['id'], $row['nom'], $row['prenom'], $row['date_de_naissance'], $row['rue'], $row['ville'], $row['code_postal'], $row['telephone'], $row['email'], $row['password'], json_decode($row['roles']));
        }
        return $users;
    }

    public function deleteUser(int $id): bool
    {
        $sql = "DELETE FROM User WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id);
        return $stmt->execute();
    }

}
