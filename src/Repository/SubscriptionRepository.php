<?php

namespace App\Repository;

use App\Entity\Subscription;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Subscription|null find($id, $lockMode = null, $lockVersion = null)
 * @method Subscription|null findOneBy(array $criteria, array $orderBy = null)
 * @method Subscription[]    findAll()
 * @method Subscription[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SubscriptionRepository extends ServiceEntityRepository
{

    /**
     * SubscriptionRepository constructor.
     * @param ManagerRegistry $registry
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Subscription::class);
    }

    /**
     * @return array
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function findActiveSubscription(): array
    {
        return $this->createQueryBuilder('s')
            ->where('s.is_active = :active')
            ->setParameter('active', true)
            ->getQuery()
            ->getResult();
    }
}
