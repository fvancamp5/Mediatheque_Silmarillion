<?php

namespace App\Repository;

use App\Entity\Loan;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\DBAL\Connection;

/**
 * @extends ServiceEntityRepository<Loan>
 */
class LoanRepository extends ServiceEntityRepository
{
    private Connection $connection;
    public function __construct(ManagerRegistry $registry, Connection $connection)
    {
        parent::__construct($registry, Loan::class);
        $this->connection = $connection;
    }


    public function add(int $idUser, int $idMedias):bool
    {
        $sql = 'INSERT INTO loan (id_user, id_media) VALUES (:idUser, :idMedias)';
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue('idUser', $idUser);
        $stmt->bindValue('idMedias', $idMedias);
        try {
            $stmt->executeQuery();
            //si l'emprunt reussi le medias n'est plus dispo
            $newsql = 'UPDATE medias SET status = 0 WHERE id = :idMedias';
            $stmt = $this->connection->prepare($newsql);
            $stmt->bindValue('idMedias', $idMedias);
            $stmt->executeQuery();
            return true;
        }
        catch (\Exception $e) {
            return false;
        }
    }

    public function return(int $idLoan): bool
    {
        $sql = 'SELECT id_media FROM loan WHERE id_media = :idLoan';
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue('idLoan', $idLoan);
        $idMedia = $stmt->executeQuery()->fetchOne();

        if (!$idMedia) {
            return false;
        }

        try {
            $sql = 'DELETE FROM loan WHERE id_media = :idLoan';
            $stmt = $this->connection->prepare($sql);
            $stmt->bindValue('idLoan', $idLoan);
            $stmt->executeQuery();

            $newsql = 'UPDATE medias SET status = 1 WHERE id = :idMedia';
            $stmt = $this->connection->prepare($newsql);
            $stmt->bindValue('idMedia', $idMedia);
            $stmt->executeQuery();

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

}
