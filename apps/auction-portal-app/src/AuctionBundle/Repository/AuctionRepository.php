<?php
namespace AuctionBundle\Repository;

use Doctrine\ORM\EntityRepository;

class AuctionRepository extends EntityRepository
{
    public function findActiveAuctions()
    {
        $qb = $this->createQueryBuilder('a')
            ->where('a.dateEnd > :now')
            ->andWhere('a.dateStart <= :now')
            ->setParameter('now', new \DateTime('now'))
            ->orderBy('a.id', 'ASC');

        return $qb->getQuery()->getResult();
    }
}