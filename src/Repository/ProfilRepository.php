<?php

namespace App\Repository;

use App\Entity\Profil;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Profil|null find($id, $lockMode = null, $lockVersion = null)
 * @method Profil|null findOneBy(array $criteria, array $orderBy = null)
 * @method Profil[]    findAll()
 * @method Profil[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProfilRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Profil::class);
    }

    // /**
    //  * @return Profil[] Returns an array of Profil objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Profil
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findLastProfil(){
        $query = $this->createQueryBuilder('p')
            ->orderBy('p.id', 'DESC')
            ->setMaxResults(1)
            ->getQuery();
        if(empty($query->getResult())){
            return null;
        }else{
            return $query->getResult();
        }
    }

    public function findJoinProfil($user_id){
        $rawSql = "SELECT profil_id FROM profil_user WHERE user_id = $user_id";
        $stmt = $this->getEntityManager()->getConnection()->prepare($rawSql);
        $stmt->execute([$user_id]);
        return $stmt->fetchAll();
    }

    public function deleteProfil_user($profil_id){
        $rawSql = "DELETE FROM profil_user WHERE profil_id = $profil_id";
        $stmt = $this->getEntityManager()->getConnection()->prepare($rawSql);
        return $stmt->execute([$profil_id]);
    }

    public function deleteProfil($id){
        $rawSql = "DELETE FROM profil WHERE id = $id";
        $stmt = $this->getEntityManager()->getConnection()->prepare($rawSql);
        return $stmt->execute([$id]);
    }

    public function findName($name){
        $query = $this->createQueryBuilder('p')
                ->andWhere('p.prenom LIKE :searchTerm OR p.nom LIKE :searchTerm')
                ->setParameter('searchTerm', '%'.$name.'%');
        // return $query->orderBy('p.id', 'DESC')
        //             ->setMaxResults(1)

        return $query->getQuery()
                    ->getResult();
    }
}
