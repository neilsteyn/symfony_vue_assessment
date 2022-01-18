<?php

namespace App\Repository;

use App\Entity\Contact;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Contact|null find($id, $lockMode = null, $lockVersion = null)
 * @method Contact|null findOneBy(array $criteria, array $orderBy = null)
 * @method Contact[]    findAll()
 * @method Contact[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContactRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Contact::class);
    }

    // /**
    //  * @return Contact[] Returns an array of Contact objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Contact
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findFiltered($filters){
        $qb = $this->_em->createQueryBuilder();
        $qb->select('c.name, c.email, c.gender, c.content')
            ->from('App\Entity\Contact', 'c');

            if (!empty($filters['search']) && !empty($filters['gender'])){
                $qb->where('c.name LIKE :s')
                ->andWhere('c.email LIKE :s');

                $params = array('s' => '%'.addcslashes($filters['search'], '%_').'%');

                if ($filters['gender'] != 'All'){
                    $qb->andWhere('c.gender = :g');
                    $params['g'] = $filters['gender'];
                }

                $qb->setParameters($params);
            }
            else if (!empty($filters['search'])){
                $qb->where('c.name LIKE :s')
                ->andWhere('c.email LIKE :s')
                ->setParameters(array('s' => '%'.addcslashes($filters['search'], '%_').'%'));
            }
            else if (!empty($filters['gender'])){
                if ($filters['gender'] != 'All'){
                    $qb->where('c.gender = :g')
                    ->setParameters(array('g' => $filters['gender']));
                }
            }

        return $qb->getQuery()->getArrayResult();
    }
}
