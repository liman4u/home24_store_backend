<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Transformers\ProductsTransformer;

class ProductsController extends BaseController
{
    /**
     * Get all products
     *
     * @return mixed
     */
    public function index()
    {
        $products = Product::all();

        return $this->response->collection( $products, new ProductsTransformer());
    }

    /**
     * Get one product
     *
     * @param $id
     */
    public function show($id)
    {
        $product = Product::where('id', $id)->first();

        if ($product) {
            return $this->item($product, new ProductsTransformer());
        }

        return $this->response->errorNotFound();
    }
}
