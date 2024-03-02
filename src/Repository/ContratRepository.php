<?php

namespace App\Repository;

use App\Entity\Contrat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Contrat>
 *
 * @method Contrat|null find($id, $lockMode = null, $lockVersion = null)
 * @method Contrat|null findOneBy(array $criteria, array $orderBy = null)
 * @method Contrat[]    findAll()
 * @method Contrat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContratRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Contrat::class);
    }
    public function searchcontrat($searchQuery)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.nom_client LIKE :query OR p.description LIKE :query')
            ->setParameter('query', '%' . $searchQuery . '%')
            ->getQuery()
            ->getResult();
    }
    /**
     * Recherche les logements par adresse.
     *
     * @param string $adresse L'adresse à rechercher
     * @return Contrat[] Retourne un tableau de logements correspondant à l'adresse
     */
    public function findByNomClient(string $nomclient): array
{
    try {
        return $this->createQueryBuilder('l')
            ->andWhere('l.nom_client LIKE :nom_client')
            ->setParameter('nom_client', '%'.$nomclient.'%')
            ->getQuery()
            ->getResult();
    } catch (\Doctrine\ORM\ORMException $e) {
        // Log l'erreur ou gérer selon le besoin
        throw new \Exception("Une erreur est survenue lors de la recherche par nom de client.");
    }
}
//    /**
//     * @return Contrat[] Returns an array of Contrat objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Contrat
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

}
