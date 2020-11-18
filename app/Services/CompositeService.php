<?php


namespace App\Services;


use App\BillClasses\BuytwoT_ShirtsGetJacketHalfPrice;
use App\BillClasses\DiscountClass;
use App\BillClasses\TaxClass;
use App\Repositries\CompositeRepositry;
use Illuminate\Http\Request;

class CompositeService
{
    private array $allResults;
    /**
     * @var CompositeRepositry
     */
    private $compositeRepositry;
    /**
     * @var ProductService
     */
    private $productService;

    /**
     * CompositeService constructor.
     * @param CompositeRepositry $compositeRepositry
     * @param ProductService $productService
     */
    public function __construct(CompositeRepositry $compositeRepositry, ProductService $productService)
    {
        $this->compositeRepositry = $compositeRepositry;
        $this->productService = $productService;
    }

    public function checkOut(Request $request)
    {
        $productIds = [];
        foreach ($request->items as $item) {
            $productIds[] = $item['id'];
        }
        $conditions = ['id' => $productIds];
        $products = $this->productService->listItems($request, false, $conditions);

        $this->addCountItemsToObject($request, $products);

        $resultTaxOffers = $this->calculate($products);

        return  $this->manageResult($products , $resultTaxOffers);
return $resultTaxOffers;
//        $this->compositeRepositry->checkOut();
    }

    public function addCountItemsToObject($request, $products): void
    {
        foreach ($products as $item) {
            $key = array_search($item->id, array_column($request->items, 'id'));
            $item->count = $request->items[$key]['count'];
        }
    }

    public function calculate(object $items): array
    {
//        calculate tax
        $taxClass = new TaxClass($items);
//        $taxClass = $taxClass->setTaxPercentage(14);// you can set tax percentage if you need to change default val 14 %
        $this->allResults[] = $taxClass->calculate();
//        calculate tax

//        calculate all discounts
        $discountClass = new DiscountClass($items);
        $this->allResults[] = $discountClass->calculate();
//        calculate all discounts

//        Buy two t-shirts and get a jacket half its price
        $BuytwoT_ShirtsGetJacketHalfPriceClass = new BuytwoT_ShirtsGetJacketHalfPrice($items);
//        $BuytwoT_ShirtsGetJacketHalfPriceClass->setItemIdForOffer(1); //you can set any item for causer offer
//        $BuytwoT_ShirtsGetJacketHalfPriceClass->setSetOfferInItem(3); //you can set any item for add offer in it
        $this->allResults[] = $BuytwoT_ShirtsGetJacketHalfPriceClass->calculate();
//        Buy two t-shirts and get a jacket half its price
        return $this->allResults;
    }

    public function manageResult(object $products ,array $resultTaxOffers): array{
        $items = [];
        $Subtotal = 0;
        $Taxes = 0;
        $discount = [];
        if ($products) {
            foreach ($products as $product){
                $items[] = ['name' => $product->name,
                    'total_price' => $product->unit_price * $product->count, "count" => $product->count];
                $Subtotal += $product->unit_price * $product->count;
               $Taxes =  $resultTaxOffers[0];
               $discount[] = $product->discount_percentage ? $product->discount_percentage . '% off '. $product->name . ': '. $product->discount ?? '' : '';
            }
            $total = $Subtotal - array_sum($resultTaxOffers);
            $result = ['items' => $items, 'Subtotal' => $Subtotal, 'Taxes' => $Taxes, 'discount' => $discount, 'total' => $total];
            return $result;
        }
    }

}
//$products->map(function ($row) {
//    return [
//        "id" => $row->id,
//        "name" => $row->name,
//        "description" => $row->description,
//        "unit_price" => $row->unit_price,
//        "discount_percentage" => $row->discount_percentage,
//        "discount" => $row->discount ?? '',
//        "count" => $row->count,
//        "total_price" => $row->unit_price * $row->count,
//    ];
//});
