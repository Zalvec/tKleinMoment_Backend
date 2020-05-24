<?php

namespace App\Repository;

use App\Entity\DownloadLog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DownloadLog|null find($id, $lockMode = null, $lockVersion = null)
 * @method DownloadLog|null findOneBy(array $criteria, array $orderBy = null)
 * @method DownloadLog[]    findAll()
 * @method DownloadLog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DownloadLogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DownloadLog::class);
    }

    // /**
    //  * @return DownloadLog[] Returns an array of DownloadLog objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DownloadLog
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
