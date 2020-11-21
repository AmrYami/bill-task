<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateDiscountRequest;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.auth', ['only' => ['createDiscount']]);
        $this->middleware('MerchantUser', ['only' => ['index', 'createDiscount']]);
    }

    /**
     * @param Request $request
     * @param ProductService $productService
     * @return \Illuminate\Http\JsonResponse
     * list items
     */
    public function index(Request $request, ProductService $productService)
    {
        $products = $productService->listItems($request);
        if ($products)
            return response()->json(array(
                'products' => $products,
            ), 200);
        return Response::json(array('error' => __('Something Went Wrong Please Try Again Later')), 200);
    }


    /**
     * @param int $productId
     * @param CreateDiscountRequest $request
     * @param ProductService $productService
     * @return \Illuminate\Http\JsonResponse
     * set discount any items
     */
    public function createDiscount(int $productId, CreateDiscountRequest $request, ProductService $productService)
    {
        $product = $productService->createDiscount($request, $productId);
        if ($product)
            return response()->json(array(
                'productId' => $product,
            ), 200);
        return Response::json(array('error' => __('you are dont owner for this product')), 200);
    }

}
