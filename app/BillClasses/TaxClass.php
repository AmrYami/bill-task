<?php


namespace App\BillClasses;


use App\BillInterfaces\CalcInterface;

class TaxClass implements CalcInterface
{
    /**
     * @var object
     */
    private $Items;

    /**
     * TaxClass constructor.
     * @param object $Items
     */
    public function __construct(object $Items)
    {
        $this->Items = $Items;
    }

    private $taxPercentage = 14;

    /**
     * @return mixed
     */
    public function getTaxPercentage()
    {
        return $this->taxPercentage;
    }

    /**
     * @param mixed $taxPercentage
     */
    public function setTaxPercentage($taxPercentage): void
    {
        $this->taxPercentage = $taxPercentage;
    }

    public function calculate()
    {
        $total = 0;
        foreach ($this->Items as $item){
            $total += $item->unit_price * $item->count;
        }
        return ($total * $this->getTaxPercentage()) / 100;
    }
}
