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

    public function getAllUsers(): array
    {
        $sql = "SELECT * FROM User";
        $stmt = $this->db->query($sql);
        $users = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $users[] = new User($row['id'], $row['nom'], $row['prenom'], $row['date_de_naissance'], $row['rue'], $row['ville'], $row['code_postal'], $row['telephone'], $row['email']);
        }
        return $users;
    }
}
