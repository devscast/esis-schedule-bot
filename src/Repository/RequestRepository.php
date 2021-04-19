<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Request;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Request|null find($id, $lockMode = null, $lockVersion = null)
 * @method Request|null findOneBy(array $criteria, array $orderBy = null)
 * @method Request[]    findAll()
 * @method Request[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RequestRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Request::class);
    }

    /**
     * @param array $period
     * @return int|null
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function countByPeriod(array $period): ?int
    {
        try {
            return (int)$this->createQueryBuilder('r')
                ->select('COUNT(r.id)')
                ->where("r.requested_at BETWEEN :start AND :end")
                ->setParameter("start", $period[0])
                ->setParameter("end", $period[1])
                ->getQuery()
                ->getSingleScalarResult();
        } catch (NoResultException | NonUniqueResultException $e) {
            return 0;
        }
    }
}
