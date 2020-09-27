<?php

namespace AuctionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class AuctionController extends Controller
{
    public function auctionListAction()
    {
        $auctions = $this->getAuctionRepository()->findActiveAuctions();

        return $this->render('auction/auction_list.html.twig', [
            'auctions' => $auctions,
        ]);
    }

    public function auctionListAdminAction()
    {
        $auctions = $this->getAuctionRepository()->findAll();

        return $this->render('auction/auction_list.html.twig', [
            'auctions' => $auctions,
        ]);
    }

    protected function getAuctionRepository()
    {
        return $this->get('app.auction.repository');
    }

    protected function getDoctrineManager()
    {
        return $this->getDoctrine()->getManager();
    }
}
