<?php
/**
 * Created by PhpStorm.
 * User: limanadamu
 * Date: 25/09/2018
 * Time: 9:08 AM
 */

namespace App\Presenters;

use App\Transformers\ProductsTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

class ProductPresenter extends FractalPresenter
{

    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new ProductsTransformer();
    }
}