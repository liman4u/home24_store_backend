<?php

namespace App\Transformers;

use App\Models\Product;
use League\Fractal\TransformerAbstract;

/**
 * Class ProductsTransformer
 * @package App\Transformers
 */
class ProductsTransformer extends TransformerAbstract
{
    public function transform(Product $product)
    {
        return [
            'id'        => (int) $product->id,
            'name'      => ucfirst($product->name),
            'description'     => $product->description,
            'price'    => (float) $product->price,
            'quantity' => (int) $product->quantity,
        ];
    }
}