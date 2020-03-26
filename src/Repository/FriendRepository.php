<?php

namespace App\Repository;

use App\Entity\Friend;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Friend|null find($id, $lockMode = null, $lockVersion = null)
 * @method Friend|null findOneBy(array $criteria, array $orderBy = null)
 * @method Friend[]    findAll()
 * @method Friend[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FriendRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Friend::class);
    }

    // /**
    //  * @return Friend[] Returns an array of Friend objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Friend
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findFriendId($id){
        $rawSql = "SELECT * FROM friend WHERE friend_id = $id";
        $stmt = $this->getEntityManager()->getConnection()->prepare($rawSql);
        $stmt->execute([$id]);
        return $stmt->fetchAll();
    }

    public function findFriend($id){
        $rawSql = "SELECT * FROM friend WHERE id = $id";
        $stmt = $this->getEntityManager()->getConnection()->prepare($rawSql);
        $stmt->execute([$id]);
        return $stmt->fetchAll();
    }

    public function findUserId($id){
        $rawSql = "SELECT user_id FROM friend WHERE friend_id = $id";
        $stmt = $this->getEntityManager()->getConnection()->prepare($rawSql);
        $stmt->execute([$id]);
        return $stmt->fetchAll();
    }

    public function findFriendUser($id){
        $rawSql = "SELECT * FROM friend WHERE user_id = $id";
        $stmt = $this->getEntityManager()->getConnection()->prepare($rawSql);
        $stmt->execute([$id]);
        return $stmt->fetchAll();
    }
    public function findIsFriend($id, $myId){
        $rawSql = "SELECT is_friend FROM friend WHERE friend_id = $id AND user_id = $myId";
        $stmt = $this->getEntityManager()->getConnection()->prepare($rawSql);
        $stmt->execute([$id, $myId]);
        return $stmt->fetchAll();
    }

    public function affFriend($myId, $id, $myFriend, $profilId){
        $rawSql = "SELECT is_friend 
                    FROM friend 
                    WHERE (friend.user_id = $myId AND friend.friend_id = $id)
                    OR (friend.user_id = $myFriend AND friend.friend_id = $profilId)";
        $stmt = $this->getEntityManager()->getConnection()->prepare($rawSql);
        $stmt->execute([$myId, $id, $myFriend, $profilId]);
        return $stmt->fetchAll();
    }

    public function affUser($user, $data){
        // DARK
        // profil 98 user 17
        // $user     $data
        // BEN
        // profil 97 user 16
        // $user     $data
        $rawSql = "SELECT * FROM friend
                    WHERE (friend.user_id = $data)
                    OR (friend.friend_id = $user)
                    AND friend.is_friend = 1";
        $stmt = $this->getEntityManager()->getConnection()->prepare($rawSql);
        $stmt->execute([$data]);
        return $stmt->fetchAll();
    }

    public function findJoinFriend($id, $user){
        $rawSql = "SELECT *, user.id
                    FROM (profil JOIN profil_user ON profil_user.profil_id = profil.id)
                    JOIN user ON user.id = profil_user.user_id
                    JOIN friend ON profil.id = friend.friend_id
                    WHERE (friend.friend_id = $id
                    OR friend.user_id = $user)
                    AND friend.is_friend = 1
                    AND profil_user.user_id != $user";
        $stmt = $this->getEntityManager()->getConnection()->prepare($rawSql);
        $stmt->execute([$id, $user]);
        return $stmt->fetchAll();
    }
}
