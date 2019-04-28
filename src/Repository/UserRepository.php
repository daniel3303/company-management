<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends BaseRepository {
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * UserRepository constructor.
     * @param RegistryInterface $registry
     * @param UserPasswordEncoderInterface $encoder
     */
    public function __construct(RegistryInterface $registry, UserPasswordEncoderInterface $encoder) {
        parent::__construct($registry, User::class);
        $this->passwordEncoder = $encoder;
    }

    /**
     * @param $username string|null
     * @param $password string|null
     * @return User Returns a single User or null
     */
    public function findOneByUsernameAndPassword(?string $username, ?string $password): ?User{
        $user = $this->findOneBy(array('email' => $username));

        //Username not found
        if($user == null){ return null; }

        if($this->passwordEncoder->isPasswordValid($user, $password) === true){
            return $user;
        }else{
            return null;
        }
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
}
