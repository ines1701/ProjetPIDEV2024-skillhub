<?php

namespace App\Repository;

use App\Entity\Jsp;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Jsp>
 *
 * @method Jsp|null find($id, $lockMode = null, $lockVersion = null)
 * @method Jsp|null findOneBy(array $criteria, array $orderBy = null)
 * @method Jsp[]    findAll()
 * @method Jsp[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JspRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Jsp::class);
    }

//    /**
//     * @return Jsp[] Returns an array of Jsp objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('j')
//            ->andWhere('j.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('j.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Jsp
//    {
//        return $this->createQueryBuilder('j')
//            ->andWhere('j.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
