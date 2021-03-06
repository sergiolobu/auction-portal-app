<?php

namespace AuctionBundle\Twig;

use Twig\Extension\AbstractExtension;

use Twig\TwigFunction;

class GetHighestBidsPriceActiveExtension extends AbstractExtension
{
    public function getFunctions()
    {
        return [
            new TwigFunction('getHighestBidsPriceActive', [$this, 'getHighestBidsPriceActiveExtension']),
        ];
    }

    public function getHighestBidsPriceActiveExtension($auctionBids)
    {
        $bidsPriceActive = [0];

        if (is_null($auctionBids)) {
            return 0;
        }

        foreach ($auctionBids as $bid) {
            if ($bid->isActive() == true) {
                array_push($bidsPriceActive, $bid->getBidPrice());
            }
        }

        return max($bidsPriceActive);
    }
}