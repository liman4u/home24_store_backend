<?php
/**
 * Created by PhpStorm.
 * User: limanadamu
 * Date: 25/09/2018
 * Time: 9:47 AM
 */

namespace App\Exceptions;

/**
 * Class ProductNotFoundException
 * @package App\Exceptions
 */
class ProductNotFoundException extends \Exception
{

    protected $message = "Product Not Found";

}