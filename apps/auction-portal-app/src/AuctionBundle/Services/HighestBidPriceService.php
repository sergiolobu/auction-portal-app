<?php
namespace AuctionBundle\Services;

use AuctionBundle\Repository\BidRepository;

class HighestBidPriceService
{
    private $bidRepository;

    public function __construct(BidRepository $bidRepository)
    {
        $this->bidRepository = $bidRepository;
    }

    public function getHighestBidPrice($auction)
    {
        $bidsByAuctionArray = $this->bidRepository->findBidsByAuctionIdAndOrderByBidPrice($auction);

        $highestBid = array_shift($bidsByAuctionArray);

        if (is_null($highestBid)){
            $highestBid = ['bidPrice' => 0];
        }

        return $highestBid['bidPrice'];
    }
}