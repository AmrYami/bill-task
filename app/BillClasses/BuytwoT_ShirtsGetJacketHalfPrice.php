<?php


namespace App\BillClasses;


use App\BillInterfaces\CalcInterface;
use Illuminate\Http\Request;

class BuytwoT_ShirtsGetJacketHalfPrice implements CalcInterface
{
    private $itemIdForOffer = 1;
    private $setOfferInItem = 3;


    /**
     * @var object
     */
    private $items;

    /**
     * DiscountClass constructor.
     * @param object $items
     */
    public function __construct(object $items)
    {
        $this->items = $items;
    }

    /**
     * @return int
     */
    public function getItemIdForOffer(): int
    {
        return $this->itemIdForOffer;
    }

    /**
     * @param int $itemIdForOffer
     */
    public function setItemIdForOffer(int $itemIdForOffer): void
    {
        $this->itemIdForOffer = $itemIdForOffer;
    }

    /**
     * @return int
     */
    public function getSetOfferInItem(): int
    {
        return $this->setOfferInItem;
    }

    /**
     * @param int $setOfferInItem
     */
    public function setSetOfferInItem(int $setOfferInItem): void
    {
        $this->setOfferInItem = $setOfferInItem;
    }

    public function calculate()
    {
        $keyCauserOffer = array_search($this->getItemIdForOffer(), array_column($this->items->toArray(), 'id'));
        $keySetOffer = array_search($this->getSetOfferInItem(), array_column($this->items->toArray(), 'id'));
        if ($this->items[$keyCauserOffer]['count'] > 1)
            $this->items[$keySetOffer]->discount_percentage = 50;
        return -($this->items[$keySetOffer]->unit_price * 50) / 100;
    }
}
