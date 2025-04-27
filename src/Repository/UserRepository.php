<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\DBAL\Connection;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, Connection $connection)
    {
        parent::__construct($registry, User::class);
        $this->connection = $connection;
    }

    public function checkLogsin(string $email, string $password): bool
    {
        $sql = 'SELECT * FROM user WHERE email = :email ';
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue('email', $email);
        $result = $stmt->executeQuery()->fetchAssociative();

        // Si le résultat est vide, l'utilisateur n'existe pas sinon il existe et on renvoie true
        if (empty($result)) {
            return false;
        }
        //vérifie si le mot de passe est correct avec le hash
        if (password_verify($password, $result['password'])) {
            return true;
        }

        return false;
    }

    public function add(string $firstname, string $lastname, string $email, string $password): bool
    {
        //check si l'utilisateur existe sinon on l'ajoute
        if ($this->checkLogsin($email, $password) === false) {
            $sql = 'INSERT INTO user (firstname, lastname, email, password) VALUES (:firstname, :lastname, :email, :password)';
            $stmt = $this->connection->prepare($sql);
            $stmt->bindValue('firstname', $firstname);
            $stmt->bindValue('lastname', $lastname);
            $stmt->bindValue('email', $email);
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            $stmt->bindValue('password', $hashedPassword);
            $stmt->executeQuery();
            return true;
        }
        return false;
    }
}
