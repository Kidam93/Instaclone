<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    // /**
    //  * @return User[] Returns an array of User objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findIdUsername($value)
    {
        $query = $this->createQueryBuilder('u')
            ->andWhere('u.username = :val')
            ->setParameter('val', $value)
            ->getQuery();

        if(empty($query->getResult())){
            return null;
        }else{
            return $query->getResult()[0]->getId();
        }
    }

    public function findUsername($value)
    {
        $query = $this->createQueryBuilder('u')
            ->andWhere('u.username = :val')
            ->setParameter('val', $value)
            ->getQuery();

        if(empty($query->getResult())){
            return null;
        }else{
            return $query->getResult()[0]->getUsername();
        }
    }

    public function findPassword($value)
    {
        $query = $this->createQueryBuilder('u')
            ->andWhere('u.username = :val')
            ->setParameter('val', $value)
            ->getQuery();

        if(empty($query->getResult())){
            return null;
        }else{
            return $query->getResult()[0]->getPassword();
        }
    }

    public function findEmail($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.email = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findId($id){
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            'SELECT u
            FROM App\Entity\User u
            WHERE u.id = :id'
        )->setParameter('id', $id);
        // returns an array of Product objects
        if(empty($query->getResult())){
            return null;
        }else{
            return $query->getResult()[0]->getId();
        }
    }

    public function findToken($token){
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            'SELECT u
            FROM App\Entity\User u
            WHERE u.token = :token'
        )->setParameter('token', $token);
        // returns an array of Product objects
        if(empty($query->getResult())){
            return null;
        }else{
            return $query->getResult()[0]->getToken();
        }
    }

    public function findUser($id){
        $query = $this->createQueryBuilder('u')
            ->andWhere('u.id = :val')
            ->setParameter('val', $id)
            ->getQuery();
        if(empty($query->getResult())){
            return null;
        }else{
            return $query->getResult()[0]->getId();
        }
    }

    public function findUsernameProfil($id){
        $query = $this->createQueryBuilder('u')
            ->andWhere('u.id = :val')
            ->setParameter('val', $id)
            ->getQuery();
        if(empty($query->getResult())){
            return null;
        }else{
            return $query->getResult()[0]->getUsername();
        }
    }

    public function findCreatedAtProfil($id){
        $query = $this->createQueryBuilder('u')
            ->andWhere('u.id = :val')
            ->setParameter('val', $id)
            ->getQuery();
        if(empty($query->getResult())){
            return null;
        }else{
            return $query->getResult()[0]->getCreatedAt();
        }
    }

    public function findName($name){
        $query = $this->createQueryBuilder('p')
                ->andWhere('p.username LIKE :searchTerm')
                ->setParameter('searchTerm', '%'.$name.'%');
        return $query->getQuery()
                    ->getResult();
    }

    public function findJoinId($id){
        // $rawSql = "SELECT user.id FROM user JOIN friend ON user.id = friend.user_id WHERE friend.friend_id = $id";
        // EXPERIMENTAL
        // $rawSql = "SELECT * FROM (profil JOIN user ON user.user_id = profil.id) JOIN user JOIN friend ON user.id = friend.user_id WHERE friend.friend_id = $id";
        // $rawSql = "SELECT * FROM (profil JOIN user AS user_profil ON user_profil.id = profil.id) JOIN user JOIN friend ON user.id = friend.user_id WHERE friend.friend_id = $id";
        // $rawSql = "SELECT * FROM user JOIN friend ON user.id = friend.user_id WHERE friend.friend_id = $id";
        // $rawSql = "SELECT * FROM user JOIN friend ON user.id = friend.user_id WHERE friend.friend_id = $id";
        // $rawSql = "SELECT profil_id FROM (profil_user JOIN user ON user.id = profil_user.user_id) JOIN friend ON user.id = friend.user_id WHERE friend.friend_id = $id";
        $rawSql = "SELECT *
                    FROM (profil JOIN profil_user ON profil_user.profil_id = profil.id)
                    JOIN user ON user.id = profil_user.user_id
                    JOIN friend ON user.id = friend.user_id
                    WHERE friend.friend_id = $id";
        $stmt = $this->getEntityManager()->getConnection()->prepare($rawSql);
        $stmt->execute([$id]);
        return $stmt->fetchAll();
    }

    public function findMyProfil($id, $myFriend){
        $rawSql = "SELECT friend.user_id 
                    FROM profil_user 
                    JOIN friend ON friend.friend_id = profil_user.profil_id
                    WHERE profil_user.user_id = $id AND friend.user_id = $myFriend";
        $stmt = $this->getEntityManager()->getConnection()->prepare($rawSql);
        $stmt->execute([$id, $myFriend]);
        return $stmt->fetchAll();
    }

    public function findMyFriend($id){
        $rawSql = "SELECT user_id 
                    FROM profil_user 
                    WHERE profil_user.profil_id = $id";
        $stmt = $this->getEntityManager()->getConnection()->prepare($rawSql);
        $stmt->execute([$id]);
        return $stmt->fetchAll();
    }

    public function selectProfil($id){
        $rawSql = "SELECT *
                    FROM profil_user 
                    WHERE profil_user.user_id = $id";
        $stmt = $this->getEntityManager()->getConnection()->prepare($rawSql);
        $stmt->execute([$id]);
        return $stmt->fetchAll();
    }

    public function isRelation($myFriend, $profilId){
        $rawSql = "SELECT is_friend
                    FROM friend 
                    WHERE friend.user_id = $myFriend 
                    AND friend.friend_id = $profilId";
        $stmt = $this->getEntityManager()->getConnection()->prepare($rawSql);
        $stmt->execute([$myFriend, $profilId]);
        return $stmt->fetchAll();
    }
}
