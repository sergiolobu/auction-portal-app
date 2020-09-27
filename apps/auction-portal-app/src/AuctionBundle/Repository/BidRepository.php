<?php
namespace AuctionBundle\Repository;

use Doctrine\ORM\EntityRepository;

class BidRepository extends EntityRepository
{
    public function findBidsByAuctionIdAndOrderByBidPrice($auction)
    {
        $qb = $this->createQueryBuilder('b')
            ->select('b.bidPrice')
            ->where('b.auction = :auction')
            ->setParameter('auction', $auction)
            ->orderBy('b.bidPrice' , 'DESC');

        return $qb->getQuery()->getResult();
    }
}