<?php

namespace App\Repository;

use App\Entity\Medias;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\DBAL\Connection;

/**
 * @extends ServiceEntityRepository<Medias>
 */
class MediasRepository extends ServiceEntityRepository
{
    private Connection $connection;

    public function __construct(ManagerRegistry $registry, Connection $connection)
    {
        parent::__construct($registry, Medias::class);
        $this->connection = $connection;
    }

    public function update(int $id, string $title, string $author, string $type, string $description, string $image): void
    {
        $sql = 'UPDATE medias SET title = :title, author = :author, type = :type, description = :description, image = :image WHERE id = :id';
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue('title', $title);
        $stmt->bindValue('author', $author);
        $stmt->bindValue('type', $type);
        $stmt->bindValue('description', $description);
        $stmt->bindValue('image', $image);
        $stmt->bindValue('id', $id);
        $stmt->executeQuery();
    }

    public function search(string $query): array
    {
        $sql = 'SELECT * FROM medias WHERE title LIKE :query OR author LIKE :query OR type LIKE :query OR description LIKE :query';
        //on utilise connection a la place de pdo car pas inclus dans doctrine
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue('query', '%' . $query . '%');
        $result = $stmt->executeQuery();

        //la methode renvoie un tableau associatif et pas un tableau d'objets
        return $result->fetchAllAssociative(); 
    }

    //verifie si le media est un emprunt du user actuel
    public function isLoan(int $idUser, int $idMedias): bool
    {
        $sql = 'SELECT * FROM loan WHERE id_user = :idUser AND id_media = :idMedias';
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue('idUser', $idUser);
        $stmt->bindValue('idMedias', $idMedias);
        $result = $stmt->executeQuery();
        return !empty($result->fetchAllAssociative());
    }

    public function add(string $title, string $author, string $type, string $description, string $image): void
    {
        $sql = 'INSERT INTO medias (title, author, type, description, image) VALUES (:title, :author, :type, :description, :image)';
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue('title', $title);
        $stmt->bindValue('author', $author);
        $stmt->bindValue('type', $type);
        $stmt->bindValue('description', $description);
        $stmt->bindValue('image', $image);
        $stmt->executeQuery();
    }

    public function delete(int $id): void
    {
        $sql = 'DELETE FROM medias WHERE id = :id';
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue('id', $id);
        $stmt->executeQuery();
    }
}
