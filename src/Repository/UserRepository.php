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
    private Connection $connection;

    private function containsSpecialChars(string $value): bool {
        //si un caractere n'est pas dans la liste on renvoie true
        if (preg_match('/[^a-zA-Z0-9\s._@éàèùçâêîôûäëïöüÿÉÀÈÙÇÂÊÎÔÛÄËÏÖÜŸ-]/u', $value)) {
            return true;
        }
        return false;
    }

    public function __construct(ManagerRegistry $registry, Connection $connection)
    {
        parent::__construct($registry, User::class);
        $this->connection = $connection;
    }

    public function checkLogsin(string $email, string $password): bool
    {
        if (empty($email) || empty($password || $this->containsSpecialChars($email) || $this->containsSpecialChars($password))) {
            return false;
        }
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
        //ouais elle est longue mais j'allais pas faire 1 if par var
        if (empty($firstname) || empty($lastname) || empty($email) || empty($password) || $this->containsSpecialChars($firstname) || $this->containsSpecialChars($lastname) || $this->containsSpecialChars($email) || $this->containsSpecialChars($password)) {
            return false;
        }
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

    public function getUserDetails(string $email)
    {
        if (empty($email) || $this->containsSpecialChars($email)) {
            return false;
        }
        $sql = 'SELECT id, firstname, lastname, email, password, status FROM user WHERE email = :email';
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue('email', $email);
        $result = $stmt->executeQuery()->fetchAssociative();

        return $result ;
    }
}
