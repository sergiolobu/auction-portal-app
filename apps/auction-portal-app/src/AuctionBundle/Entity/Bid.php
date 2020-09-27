<?php

namespace AuctionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Table(name="app_bids")
 * @ORM\Entity(repositoryClass="AuctionBundle\Repository\BidRepository")
 */
class Bid
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Auction", inversedBy="auctionBids")
     * @ORM\JoinColumn(name="auction_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     *
     */
    private $auction;

    /**
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User", inversedBy="userBids")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     *
     */
    private $user;

    /**
     * @ORM\Column(type="integer", name="bid_price")
     */
    private $bidPrice;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $active = true;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getAuction()
    {
        return $this->auction;
    }

    /**
     * @param mixed $auction
     */
    public function setAuction($auction)
    {
        $this->auction = $auction;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getBidPrice()
    {
        return $this->bidPrice;
    }

    /**
     * @param mixed $bidPrice
     */
    public function setBidPrice($bidPrice)
    {
        $this->bidPrice = $bidPrice;
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return $this->active;
    }

    /**
     * @param bool $active
     */
    public function setActive($active)
    {
        $this->active = $active;
    }
}