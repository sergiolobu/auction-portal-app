<?php

namespace AuctionBundle\Controller;

use AuctionBundle\Entity\Auction;
use AuctionBundle\Form\AuctionType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class AuctionController extends Controller
{
    public function createAuctionAction(Request $request)
    {
        $auction = new Auction();
        $form = $this->createForm(AuctionType::class, $auction);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $this->getDoctrineManager()->persist($auction);
            $this->getDoctrineManager()->flush();

            $auctions = $this->getAuctionRepository()->findAll();

            return $this->render('auction/auction_list.html.twig', [
                'auctions' => $auctions,
            ]);
        }

        return $this->render(
            'auction/auction_form.html.twig',
            ['form' => $form->createView()]
        );
    }

    public function editAuctionAction(Request $request, $id)
    {
        $auction = $this->getAuctionRepository()->findOneBy(['id' => $id]);

        $this->ifAuctionIsNullThrowException($auction);

        $form = $this->createForm(AuctionType::class, $auction);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $this->getDoctrineManager()->persist($auction);
            $this->getDoctrineManager()->flush();

            $auctions = $this->getAuctionRepository()->findAll();

            return $this->render('auction/auction_list.html.twig', [
                'auctions' => $auctions,
            ]);
        }

        return $this->render(
            'auction/auction_form.html.twig',
            ['form' => $form->createView()]
        );
    }

    public function removeAuctionAction($id)
    {
        $auction = $this->getAuctionRepository()->findOneBy(['id' => $id]);

        $this->ifAuctionIsNullThrowException($auction);

        $this->getDoctrineManager()->remove($auction);
        $this->getDoctrineManager()->flush();

        return $this->render('auction/auction_remove_succesfully.html.twig',);
    }

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

    protected function ifAuctionIsNullThrowException($auction)
    {
        if (!$auction) {
            throw $this->createNotFoundException(
                'Subasta no encontrada'
            );
        }
    }
}
