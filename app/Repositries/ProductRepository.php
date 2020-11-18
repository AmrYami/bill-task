<?php


namespace App\Repositries;


use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class ProductRepository
{
    /**
     * @var Product
     */
    private $product;

    /**
     * repository to manage all proccess with database
     */

    public function __construct(Product $product)
    {

        $this->product = $product;
    }

    public function listItems(Request $request, $paginate = true, $conditions = [])
    {
        $pageNumber = $request->page ?? 1;
        $products = $this->product->query();
        if ($conditions) {

            foreach ($conditions as $key => $value) {
                if (is_array($value))
                    $products->whereIn($key, $value);
                else
                    $products->where($key, $value);
            }
        }
        if ($request->orderPrice) {
            $sort = $request->orderPrice == 'lowToHigh' ? 'ASC' : 'DESK';
            $products->orderBy('unit_price', $sort);
        }
        if ($paginate)
            $products = $products->paginate(5, ['*'], 'page', $pageNumber);
        else
            $products = $products->get();
        if ($products)
            return $products;
        return false;
    }

    public function createDiscount(Request $request, $productId)
    {
        $product = $this->product->where('id', $productId)->where('user_id', auth()->user()->id)->update($request->all());
        if ($product)
            return $product;
        return false;
    }

}
