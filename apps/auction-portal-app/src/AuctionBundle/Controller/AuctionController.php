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

            return $this->render('auction/auction_list.html.twig');
        }

        return $this->render(
            'auction/auction_form.html.twig',
            ['form' => $form->createView()]
        );
    }

    public function editAuctionAction(Request $request, $id)
    {
        $auction = $this->getDoctrine()->getRepository(Auction::class)->findOneBy(['id' => $id]);

        if (!$auction) {
            throw $this->createNotFoundException(
                'Subasta no encontrada'
            );
        }

        $form = $this->createForm(AuctionType::class, $auction);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $this->getDoctrineManager()->persist($auction);
            $this->getDoctrineManager()->flush();

            return $this->render('auction/auction_list.html.twig');
        }

        return $this->render(
            'auction/auction_form.html.twig',
            ['form' => $form->createView()]
        );
    }

    public function removeAuctionAction(Request $request, $id)
    {
        $auction = $this->getDoctrine()->getRepository(Auction::class)->findOneBy(['id' => $id]);

        if (!$auction) {
            throw $this->createNotFoundException(
                'Subasta no encontrada'
            );
        }

        $this->getDoctrineManager()->remove($auction);
        $this->getDoctrineManager()->flush();

        return $this->render('auction/auction_list.html.twig');
    }

    public function auctionListAction()
    {
        $auctions = $this->getDoctrine()->getRepository(Auction::class)->findActiveAuctions();

        return $this->render('auction/auction_list.html.twig', [
            'auctions' => $auctions,
        ]);
    }

    protected function getDoctrineManager()
    {
        return $this->getDoctrine()->getManager();
    }
}
