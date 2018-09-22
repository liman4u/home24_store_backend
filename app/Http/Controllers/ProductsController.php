<?php

namespace App\Http\Controllers;


use App\Models\Product;

class ProductsController extends Controller
{
    /**
     * @return mixed
     */
    public function index()
    {
        $products = Product::all();

        return response()->json(['data' => $products->toArray()], 200);
    }
}
