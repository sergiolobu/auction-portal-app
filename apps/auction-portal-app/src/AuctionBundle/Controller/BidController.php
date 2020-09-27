<?php

namespace AuctionBundle\Controller;

use AuctionBundle\Entity\Auction;
use AuctionBundle\Entity\Bid;
use AuctionBundle\Form\BidType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;

class BidController extends Controller
{
    public function setBidAction(Request $request, $auctionId)
    {
        $bid = new Bid();
        $form = $this->createForm(BidType::class, $bid);

        $auction = $this->getDoctrine()->getRepository(Auction::class)->find($auctionId);
        $userLogged = $this->getUserLogged();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($form['bidPrice']->getData() <= $this->getHighestBidPrice($auction))
            {
                throw new Exception('PUJA INFERIOR, REALIZAR PUJA MAYOR DE '.$this->getHighestBidPrice($auction));
            }

            $bid->setUser($userLogged);
            $bid->setAuction($auction);

            $this->getDoctrineManager()->persist($bid);
            $this->getDoctrineManager()->flush();

            return $this->render('bid/bid_succesfully.html.twig', [
            ]);
        }
        return $this->render(
        'bid/bid_form.html.twig', [
            'form' => $form->createView(),
            'auctionName' => $auction->getName()
            ]
        );
    }

    public function viewBidsByUserAction()
    {
        $userBids = $this->getDoctrine()->getRepository(Bid::class)->findBy(['user' => $this->getUserLogged()],['bidPrice' => 'DESC']);

        return $this->render(
            'bid/my_bids_list.html.twig', [
                'bids' => $userBids,
            ]
        );
    }

    public function cancelBidAction($idBid)
    {
        $bid = $this->getDoctrine()->getRepository(Bid::class)->findOneBy(['id' => $idBid]);

        if (!$bid) {
            throw $this->createNotFoundException(
                'Puja no encontrada'
            );
        }

        $bid->setActive(false);
        $this->getDoctrineManager()->flush();

        return $this->render('bid/bid_cancel.html.twig', [
        ]);
    }

    //Realizar en el Manager
    protected function getHighestBidPrice($auction)
    {
        $bidsByAuctionArray = $this->getDoctrine()->getRepository(Bid::class)->findBidsByAuctionIdAndOrderByBidPrice($auction);

        $highestBid = array_shift($bidsByAuctionArray);

        if (is_null($highestBid)){
            $highestBid = ['bidPrice' => 0];
        }

        return $highestBid['bidPrice'];

    }

    protected function getDoctrineManager()
    {
        return $this->getDoctrine()->getManager();
    }


    protected function getUserLogged()
    {
        return $this->get('security.token_storage')->getToken()->getUser();
    }
}