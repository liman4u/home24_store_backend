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
     * Create a product
     *
     * @param \Illuminate\Http\Request $request
     * @return \Dingo\Api\Http\Response|void
     */
    public function store(Request $request)
    {
        $validator = \Validator::make($request->input(), [
            'name'      => 'required|unique:products',
            'description'     => 'required',
            'price'    => 'required|regex:/^\d*(\.\d{2})?$/',
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

    /**
     * Update a product
     *
     * @param \Illuminate\Http\Request $request
     * @return \Dingo\Api\Http\Response|void
     */
    public function update($id,Request $request)
    {

        $validator = \Validator::make($request->input(), [
            'name'      => 'required|unique:products',
            'description'     => 'required',
            'price'    => 'required|regex:/^\d*(\.\d{2})?$/',
            'quantity' => 'required|numeric'
        ]);

        if ($validator->fails()) {


            return $this->errorBadRequest($validator);

        }

        $product = Product::find($id);

        $product->fill($request->all());

        return $this->response->item($product,new ProductsTransformer());

    }

    /**
     * Remove the specified product.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::find($id);

        if ($product) {

            $product->delete();

            return $this->response->noContent();

        }

        return $this->response->errorBadRequest();
    }
}
