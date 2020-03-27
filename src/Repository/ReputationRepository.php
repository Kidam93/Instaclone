<?php

namespace App\Repository;

use App\Entity\Reputation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Reputation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reputation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reputation[]    findAll()
 * @method Reputation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReputationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reputation::class);
    }

    // /**
    //  * @return Reputation[] Returns an array of Reputation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Reputation
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findFriendComments($friendId){
        // JOIN reputation_wall ON wall.id = reputation_wall.wall_id
        $rawSql = "SELECT *
                    FROM reputation
                    JOIN reputation_wall 
                    ON reputation_wall.reputation_id = reputation.id
                    JOIN reputation_profil 
                    ON reputation.id = reputation_profil.reputation_id
                    JOIN profil
                    ON profil.id = reputation_profil.profil_id
                    WHERE reputation.user_id = $friendId";
        $stmt = $this->getEntityManager()->getConnection()->prepare($rawSql);
        $stmt->execute([$friendId]);
        return $stmt->fetchAll();
    }

    public function findMyComments($user){
        $rawSql = "SELECT *
                    FROM reputation
                    JOIN reputation_wall 
                    ON reputation_wall.reputation_id = reputation.id
                    JOIN reputation_profil 
                    ON reputation.id = reputation_profil.reputation_id
                    JOIN profil
                    ON profil.id = reputation_profil.profil_id
                    WHERE reputation.user_id = $user";
        $stmt = $this->getEntityManager()->getConnection()->prepare($rawSql);
        $stmt->execute([$user]);
        return $stmt->fetchAll();
    }
}
