<?php


namespace App\Services;


use App\Repositries\ProductRepository;
use Illuminate\Http\Request;

class ProductService
{

    /**
     * service to manage all logic code
     */
    /**
     * @var ProductRepository
     */
    private $productRepository;

    /**
     * @var BookRepository
     */

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function listItems(Request $request, $paginate = true, $conditions = [])
    {
        $result = $this->productRepository->listItems($request, $paginate, $conditions);
        if ($result)
            return $result;
        return false;
    }

    public function createDiscount(Request $request, int $productId)
    {
        $result = $this->productRepository->createDiscount($request, $productId);
        if ($result)
            return $result;
        return false;
    }


}
