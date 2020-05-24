<?php

namespace App\Repository;

use App\Entity\AlbumTag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AlbumTag|null find($id, $lockMode = null, $lockVersion = null)
 * @method AlbumTag|null findOneBy(array $criteria, array $orderBy = null)
 * @method AlbumTag[]    findAll()
 * @method AlbumTag[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AlbumTagRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AlbumTag::class);
    }

    // /**
    //  * @return AlbumTag[] Returns an array of AlbumTag objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AlbumTag
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
