<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Models\Product;
use App\Transformers\ProductsTransformer;
use Illuminate\Http\Request;

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

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Dingo\Api\Http\Response|void
     */
    public function store(Request $request)
    {
        $validator = \Validator::make($request->input(), [
            'name'      => 'required|unique:products',
            'description'     => 'required',
            'price'    => 'required',
            'quantity' => 'required|numeric'
        ]);

        if ($validator->fails()) {

            return $this->errorBadRequest($validator);

        }

        if (Product::create($request->all())) {

            return $this->response->created();

        }

        return $this->response->errorBadRequest();
    }
}
