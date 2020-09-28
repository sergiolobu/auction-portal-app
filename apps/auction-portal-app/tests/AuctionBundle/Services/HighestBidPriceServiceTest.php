<?php

namespace Test\AuctionBundle\Services;

use AuctionBundle\Entity\Auction;
use AuctionBundle\Repository\BidRepository;
use AuctionBundle\Services\HighestBidPriceService;
use PHPUnit\Framework\TestCase;

class HighestBidPriceServiceTest extends TestCase
{
    protected $auction;

    protected $bidRepository;

    protected function setUp()
    {
        $this->auction = new Auction();

        $this->bidRepository = $this->createMock(BidRepository::class);

        parent::setUp();
    }

    public function test_empty()
    {
        $stack = [];
        $this->assertEmpty($stack);

        return $stack;
    }

    public function test_get_last_bid_price()
    {
        $this->bidRepository
            ->expects($this->any())
            ->method('findBidsByAuctionIdAndOrderByBidPrice')
            ->willReturn([['bidPrice' => 100], ['bidPrice' => 99], ['bidPrice' => 98]]);

        $highestBidPriceService = new HighestBidPriceService($this->bidRepository);

        $this->assertEquals(100, $highestBidPriceService->getHighestBidPrice($this->auction));

    }

    public function test_if_highestBid_is_null_set_bidPrice_0()
    {
        $this->bidRepository->expects($this->any())->method('findBidsByAuctionIdAndOrderByBidPrice')->willReturn([]);

        $highestBidPriceService = new HighestBidPriceService($this->bidRepository);

        $this->assertEquals(0, $highestBidPriceService->getHighestBidPrice($this->auction));
    }
}