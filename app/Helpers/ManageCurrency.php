<?php


namespace App\Helpers;


class ManageCurrency
{
    private $currency = [
        'EGY' => ['place' => 'after', 'type' => ' e£', 'mount' => 15.742],
        'dollar' => ['place' => 'before', 'type' => '$', 'mount' => 1]
    ];

    /**
     * @return array
     */
    public function getCurrency($type): array
    {
        return $this->currency[$type];
    }

    /**
     * @param array $currency
     */
    public function setCurrency($type, array $currencyData): void
    {
        $this->currency[$type] = $currencyData;
    }

}
