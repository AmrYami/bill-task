<?php


namespace App\Services;


use App\BillClasses\BuytwoT_ShirtsGetJacketHalfPrice;
use App\BillClasses\DiscountClass;
use App\BillClasses\TaxClass;
use App\Helpers\ManageCurrency;
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

    /**
     * @param Request $request
     * @return array|string[] // proccess
     */
    public function checkOut(Request $request)
    {
        if (!$request->items)
            return ['error' => 'Please choose items'];
        if (isset($request->items) && !isset($request->items[0]))
            return ['error' => 'object has error'];
        $productIds = [];
        foreach ($request->items as $item) {
            $productIds[] = $item['id'];
        }
        $conditions = ['id' => $productIds];
        $products = $this->productService->listItems($request, false, $conditions);// list choosed items for bill
        if (!count($products))
            return ['error' => 'Please choose valid items'];

        $this->addCountItemsToObject($request, $products);//merge count items to object

        $resultTaxOffers = $this->calculate($products);//proccess offers and taxes

        $result = $this->manageResult($request, $products, $resultTaxOffers);//manage result for bil
        if ($result)
            return $result;
        return ['error' => 'Something Went Wrong Please Try Again Later'];
    }

    /**
     * @param $request
     * @param $products // add count items to object
     */
    public function addCountItemsToObject($request, $products): void// add count items in object
    {
        foreach ($products as $item) {
            $key = array_search($item->id, array_column($request->items, 'id'));
            $item->count = $request->items[$key]['count'];
        }
    }

    /**
     * @param object $items
     * @return array // set all tax offers
     */
    public function calculate(object $items): array
    {
//        every offer has its owen class
//        calculate tax
        $taxClass = new TaxClass($items);
//        $taxClass = $taxClass->setTaxPercentage(14);// you can set tax percentage if you need to change default val 14 %
        $this->allResults[] = $taxClass->calculate();//set tax
//        calculate tax

//        calculate all discounts
        $discountClass = new DiscountClass($items);
        $this->allResults[] = $discountClass->calculate();//set discount
//        calculate all discounts

//        Buy two t-shirts and get a jacket half its price
        $BuytwoT_ShirtsGetJacketHalfPriceClass = new BuytwoT_ShirtsGetJacketHalfPrice($items);
//        $BuytwoT_ShirtsGetJacketHalfPriceClass->setItemIdForOffer(1); //you can set any item for causer offer
//        $BuytwoT_ShirtsGetJacketHalfPriceClass->setSetOfferInItem(3); //you can set any item for add offer in it
        $this->allResults[] = $BuytwoT_ShirtsGetJacketHalfPriceClass->calculate();// set Buy two T_Shirts Get Jacket Half Price
//        Buy two t-shirts and get a jacket half its price
        return $this->allResults;
    }

    /**
     * @param Request $request
     * @param object $products
     * @param array $resultTaxOffers
     * @return array result what we need style
     */
    public function manageResult(Request $request, object $products, array $resultTaxOffers): array
    {
        $items = [];
        $beforeSign = '';
        $afterPSign = '';
        $currencyClass = new ManageCurrency();//manage currency
        $currency = $currencyClass->getCurrency($request->currency ?? 'dollar');

        if ($currency['place'] == 'after')//set currency sign depend in its currency
            $afterPSign = $currency['type'];
        else
            $beforeSign = $currency['type'];
        $Subtotal = 0;
        $Taxes = 0;
        $discount = [];
        if ($products) {//manage data for bill
            foreach ($products as $product) {
                $items[] = ['name' => $product->name,//set all items data in bill
                    'total_price' => $this->formatNumber($afterPSign, $product->unit_price * $currency['mount'] * $product->count, 2, $beforeSign), "count" => $product->count];
                $Subtotal += $product->unit_price * $product->count;// our subtotal
                $discount[] = $product->discount_percentage ? $product->discount_percentage . '% off ' . $product->name . ': ' . ($product->discount ? $this->formatNumber($afterPSign, $product->discount * $currency['mount'], 3, $beforeSign) : '') : '';// all discounts
            }
            $Taxes = $resultTaxOffers[0];//tax
            $total = ($Subtotal + array_sum($resultTaxOffers));// total price + tax with all discounts
            $result = ['items' => $items, 'Subtotal' => $this->formatNumber($afterPSign, $Subtotal * $currency['mount'], 2, $beforeSign),
                'Taxes' => $this->formatNumber($afterPSign, $Taxes * $currency['mount'], 2, $beforeSign),
                'discount' => array_filter($discount),//remove nullable
                'total' => $this->formatNumber($afterPSign, $total * $currency['mount'], 4, $beforeSign)];
            return $result;
        }
    }

    public function formatNumber($afterPSign = '', $resTotal_price, $countDicimals = 2, $beforeSign = '')// format numbers
    {
        return $afterPSign ? $beforeSign . round($resTotal_price) . $afterPSign : $beforeSign . number_format($resTotal_price, $countDicimals, '.', ',') . $afterPSign;
    }

}
