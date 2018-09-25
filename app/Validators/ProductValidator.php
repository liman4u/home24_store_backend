<?php
/**
 * Created by PhpStorm.
 * User: limanadamu
 * Date: 25/09/2018
 * Time: 9:55 AM
 */

namespace App\Validators;

use Prettus\Validator\LaravelValidator;

class ProductValidator extends LaravelValidator
{

    protected $rules = [
        'name'      => 'required|unique:products',
        'description'     => 'required',
        'price'    => 'required|regex:/^\d*(\.\d{2})?$/',
        'quantity' => 'required|numeric'
    ];

}