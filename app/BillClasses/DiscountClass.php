<?php


namespace App\BillClasses;

use Illuminate\Http\Request;

use App\BillInterfaces\CalcInterface;

class DiscountClass implements CalcInterface
{
    private $totalDiscount;
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

    public function calculate()
    {
        foreach ($this->items as $item) {
            if ($item->discount_percentage) {
                $discount = ($item->unit_price * $item->discount_percentage) / 100;
                $item->discount = $discount;
                $this->totalDiscount += $discount * $item->count;
            }
        }
        return -$this->totalDiscount;
    }
}
