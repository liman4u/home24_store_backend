<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Transformers\ProductsTransformer;

class ProductsController extends BaseController
{
    /**
     * @return mixed
     */
    public function index()
    {
        $products = Product::all();

        return $this->response->collection( $products, new ProductsTransformer());
    }
}
