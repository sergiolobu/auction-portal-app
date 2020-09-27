<?php

namespace AuctionBundle\Controller;

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

        $auction = $this->getAuctionRepository()->find($auctionId);
        $userLogged = $this->getUserLogged();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($form['bidPrice']->getData() <= $this->get('app.highestbidprice.service')->getHighestBidPrice($auction))
            {
                throw new Exception('PUJA INFERIOR, REALIZAR PUJA MAYOR QUE '.$this->get('app.highestbidprice.service')->getHighestBidPrice($auction));
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
        $userBids = $this->getBidRepository()->findBy(['user' => $this->getUserLogged()],['bidPrice' => 'DESC']);

        return $this->render(
            'bid/my_bids_list.html.twig', [
                'bids' => $userBids,
            ]
        );
    }

    public function cancelBidAction($idBid)
    {
        $bid = $this->getBidRepository()->findOneBy(['id' => $idBid]);

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

    protected function getDoctrineManager()
    {
        return $this->getDoctrine()->getManager();
    }

    protected function getAuctionRepository()
    {
        return $this->get('app.auction.repository');
    }

    protected function getBidRepository()
    {
        return $this->get('app.bid.repository');
    }

    protected function getUserLogged()
    {
        return $this->get('security.token_storage')->getToken()->getUser();
    }
}